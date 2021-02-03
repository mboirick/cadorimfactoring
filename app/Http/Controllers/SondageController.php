<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Sondage;
use App\Reponse_sondage;
use Excel;
use Auth;
use App\Abonne;
use App\Analyse;
use App\Envoie_sondage;

class SondageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($email, $id)
    {
        $abonne = Abonne::select('id')->where('email', $email)->where('id', $id)->first();

        if($abonne){

            $questions =  Sondage::where('id_sondage', 'sondage')->select('*')->orderBy('id')->pluck('text_question');

            $sondage = Sondage::select('*')->where('id_sondage', 'sondage')->orderBy('id')->get();

            $questions = $questions->toArray();
            $questions = array_unique($questions);


            return view('sondage-mgmt/viewsondage', ['questions' => $questions, 'sondage' => $sondage, 'email' => $email, 'id' => $id]);

        }else{

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

    public function update(Request $request)
    {
        $abonne = Abonne::select('id')->where('email', $request['email'])->where('id', $request['id'])->first();

        if($abonne){
            $repondu = Reponse_sondage::select('id')->where('id_client', $request['email'])->where('id_sondage', $request['nomsonadage'])->first();

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

                    Reponse_sondage::create($input);
                    DB::table('envoie_sondages')->where('email', $request['email'])->update([
                        'repondu' => 1
                    ]);
                }
                
                return   view('sondage-mgmt/message');
            }
        } 
    }


    public function creation(Request $request)
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

                    Sondage::create($input);
                }
            }

            $questions =  Sondage::where('id_sondage', $request['nomsonadage'])->select('*')->orderBy('id')->pluck('text_question');
            $sondage = Sondage::select('*')->where('id_sondage', $request['nomsonadage'])->orderBy('id')->get();

            $questions = $questions->toArray();
            $questions = array_unique($questions);

            return view('sondage-mgmt/viewsondage', ['questions' => $questions, 'sondage' => $sondage]);
        } elseif ($request['action'] == 'view') {
            $questions =  Sondage::where('id_sondage', $request['nomsonadage'])->select('*')->orderBy('id')->pluck('text_question');
            $sondage = Sondage::select('*')->where('id_sondage', $request['nomsonadage'])->orderBy('id')->get();
            $questions = $questions->toArray();
            $questions = array_unique($questions);

            return view('sondage-mgmt/viewsondage', ['questions' => $questions, 'sondage' => $sondage]);
        }
    }

    public function survey()
    {
        $sondages = Sondage::select('id_sondage')->groupBy('id_sondage')->paginate(20);
        $envoie = Envoie_sondage::count();
        $reponse = Envoie_sondage::where('repondu','1')->count();

        return view('sondage-mgmt/index', ['sondages' => $sondages, 'envoie' => $envoie, 'reponse' => $reponse]);
    }

    public function voir($id_sondages)
    {
        $questions =  Sondage::where('id_sondage', $id_sondages)->select('*')->orderBy('id')->pluck('text_question');
        $sondage = Sondage::select('*')->where('id_sondage', $id_sondages)->orderBy('id')->get();

        $questions = $questions->toArray();
        $questions = array_unique($questions);


        return view('sondage-mgmt/show', ['questions' => $questions, 'sondage' => $sondage]);
    }

    public function emailing($id_sondage)
    {
        return view('sondage-mgmt/emailing', ['id_sondage' => $id_sondage]);
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

    public function reponses($id_sondage){
         $reponses = DB::table('reponse_sondages')
                    ->leftJoin('abonnes', 'reponse_sondages.id_client', '=', 'abonnes.email')->where('id_sondage',$id_sondage)->select('*')->orderBy('reponse_sondages.id')->paginate('24');
      
        return   view('sondage-mgmt/reponses',['reponses' => $reponses]);
    }

    public function searchsondage(Request $request){
        if($request['search']=='excel'){
            $reponses = Reponse_sondage::orderBy('id', 'DESC')->get();
            foreach($reponses as $reponse) {
                $client = DB::table('analyses')->where('client',$reponse ->id_client)->value('client');
                if($client){

                }else{
                    for($i=1; $i <= 12; $i++){
                        if($reponse ->id_question=='q'.$i){
                            if($reponse ->id_question=='q5'){
                                if($reponse ->response=='-')
                                $reponse ->response=0;
                                $input['q'.$i]=  (str_replace("-", "", $reponse ->response)+1).' Etoiles'; 
                            }else
                                $input['q'.$i]= $reponse ->response;
                           // echo  $reponse ->id_question.'_________'.$reponse ->response.'</br>';
                        }
                    }if($reponse ->id_question=='q1'){
                        $input['client']=$reponse ->id_client;
                        Analyse::create($input);       
                    }
                } 
            }       

            $this->prepareExportingData()->export('xlsx');
        }else{
            return redirect()->intended('/sondage-management/survey');
        }     
    }

    private function prepareExportingData()
    {
        $author = Auth::user()->username;
        $employees = $this->getExportingData();
        return Excel::create('Rapport', function ($excel) use ($employees, $author) {
            // Set the title
            $excel->setTitle('Liste de reponse from ');
            // Chain the setters
            $excel->setCreator($author)
                ->setCompany('HoaDang');
            // Call them separately
            $excel->setDescription('rapport');
            $excel->sheet('Rapport', function ($sheet) use ($employees) {
                $sheet->fromArray($employees);
            });
        });
    }

  
    private function getExportingData()
    {
        return DB::table('analyses')
        ->select('*')
        ->get()
        ->map(function ($item, $key) {
            return (array) $item;
        })
        ->all();
    }
}
