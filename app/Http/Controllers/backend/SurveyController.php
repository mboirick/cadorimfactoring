<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Sondage;
use App\Reponse_sondage;
use Excel;
use Auth;
use App\Abonne;
use App\Analyse;
use App\Envoie_sondage;

class SurveyController extends BaseController
{
    
    public function index()
    {
        $sondages   = $this->pollsRepository->getAll();
        $envoie     = $this->sendPollsRepository->getCountAll();
        $reponse    = $this->sendPollsRepository->getCountByAnswered();

        return view('backend.survey.index', 
                        ['sondages' => $sondages, 
                        'envoie' => $envoie, 
                        'reponse' => $reponse]);
    }

    public function answers($idPoll)
    {
        $reponses = $this->pollResponseRepository->getByIdPoll($idPoll);
     
       return  view('backend.survey.reponses',['reponses' => $reponses]);
   }

   public function store(Request $request)
   {
        if ($request['action'] == 'valider') {
            for ($i = 1; $i <= $request['nombre_question']; $i++) {

                $reponse =  $request['nombre_R' . $i];

                for ($y = 1; $y <= $reponse; $y++) {

                    $input = [
                        'id_sondage' => $request['nomsonadage'],
                        'id_question' => 'q' . $i,
                        'text_question' => $request['q' . $i],
                        'type_question' => $request['R' . $i . '-0'],
                        'id_reponse' => 'R' . $i . '-' . $y,
                        'reponse' => $request['R' . $i . '-' . $y]
                    ];
                    
                    $this->pollsRepository->create($input);
                    //Sondage::create($input);
                }
            }
        }

        $questions  = $this->pollsRepository->getQuestionsByName($request['nomsonadage']);
        $sondage    = $this->pollsRepository->getByName($request['nomsonadage']);
        $questions  = $questions->toArray();
        $questions  = array_unique($questions);

        return view('backend.survey.store', ['questions' => $questions, 'sondage' => $sondage]);
    }

    public function update(Request $request)
    {
        $abonne = $this->subscribersRepository->getByEmailId($request['id'], $request['email']);

        if($abonne){
            $repondu = $this->pollsRepository->getByEmailName($request['email'], $request['nomsonadage']);
            if($repondu ){
                echo ' <div style="margin-top: 50px;" align="center">
                <h1>Désolé, ce contenu n\'est plus disponible. </h1>
                <p>
                <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
                    <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
                    RETOUR AU SITE</div>
                </a>
                </p>
        
                </div>'  ;
       
                die;
            }else{
                for ($y = 1; $y <= $request['nombre_question']; $y++) {
                    $input = [
                        'id_sondage' => $request['nomsonadage'],
                        'id_client' => $request['email'],
                        'id_question' => 'q' . $y,
                        'text_question' => $request['q' . $y],
                        'response' => $request['R' . $y] . "-" . $request['R' . $y . '-text'],
                        'repondu' => 1
                    ];

                    $this->pollsRepository->create($input);
                    $this->sendPollsRepository->updateByEmail(['repondu' => 1]);
                }
                
                return   view('backend.survey.message');
            }
        } 
    }

    public function emailing($id)
    {
        return view('backend.survey.emailing', ['id_sondage' => $id]);
    }

    public function envoiemail(Request $request)
    {
        if ($request['emailto'] == 'all@cadorim.com' || $request['emailto'] == 'relance@cadorim.com' ) {
            $this->mailsondage($request);
        } else {
            $abonne = Abonne::select('username', 'email')->where('email', $request['emailto'])->first();
            if ($abonne) {
                $this->mailsondage($request);
            }else {
                echo ' <div style="margin-top: 50px;" align="center">
                    <h1>Désolé, ce contenu n\'est plus disponible. </h1>
                    <p>
                    <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
                        <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
                        RETOUR AU SITE</div>
                    </a>
                    </p>
            
                </div>'  ;
                die; 
            }
        }

        return redirect()->intended('/sondage-management/survey');
    }

    private function mailsondage($request)
    {
        $abonne = Abonne::select('id', 'email')->where('email', $request['emailto'])->first();
        $to = $request['emailto'];

        if ($to == 'all@cadorim.com') {
            $abonnes = Abonne::select('*')->get();
            foreach($abonnes as $abonne){
                $to= $abonne->email;
                $iduser = $abonne->id;
                $email_subject = $request['subject'];
                $email_body = str_replace("*email*", $to, $request['text']);
                $email_body = str_replace("*iduser*", $iduser, $email_body);
                $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
                $headers .= "Reply-To: noreply@cadorim.com\n";
                //$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
                $headers .= "Content-Type: text/html; charset=\"utf8\"";
                  
                if(mail($to, $email_subject, $email_body, $headers)){
                     $input = [
                        'id_sondages' => $request['nomsonadage'],
                        'email' => $to         
                    ];
                    Envoie_sondage::create($input);
                }
            }
        } elseif($to == 'relance@cadorim.com') {
            $abonnes = DB::table('abonnes')
            ->leftJoin('reponse_sondages', 'reponse_sondages.id_client', '=', 'abonnes.email')->whereNull('reponse_sondages.id_client')->select('abonnes.email', 'abonnes.id')->get();
            //dd($abonnes);
            foreach($abonnes as $abonne){
                $to= $abonne->email;
                $iduser = $abonne->id;
                $email_subject = $request['subject']; 
                $email_body = str_replace("*email*", $to, $request['text']);
                $email_body = str_replace("*iduser*", $iduser, $email_body);
                
                $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
                $headers .= "Reply-To: noreply@cadorim.com\n";
    
                //$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
                $headers .= "Content-Type: text/html; charset=\"utf8\"";
                mail($to, $email_subject, $email_body, $headers);
            }
        }else{

            $iduser = $abonne->id;

            $email_subject = $request['subject'];
            $email_body = $request['text'];

            $email_body = str_replace("*email*", $to, $request['text']);
            $email_body = str_replace("*iduser*", $iduser, $email_body);
            
            $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
            $headers .= "Reply-To: noreply@cadorim.com\n";

            //$headers .= 'Bcc:  cadorimc@gmail.com, bmneine@gmail.com, cadorim.stat@gmail.com' . "\r\n";
            $headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
            $headers .= "Content-Type: text/html; charset=\"utf8\"";

            mail($to, $email_subject, $email_body, $headers);
        }
    }
}
