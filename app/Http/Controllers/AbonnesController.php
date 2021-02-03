<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Taux_echange;
use App\Coordonnes_commande;
use App\Paiement;
use App\Commande;
use App\Abonne;
use App\Division;
use App\Document;
use App\Cache_table;
use Excel;
use Auth;
use PDF;
use Storage;
use File;
use Illuminate\Validation\Rules\Unique;

class AbonnesController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  /*public function index()
  {


    $abonnes = DB::table('abonnes')
      ->orderBy('confirmed_at', 'DESC')
      ->paginate(20);

    $stat = DB::table('abonnes')
      ->select(array(
        DB::Raw('sum(CASE WHEN (kyc = 3) THEN 1 ELSE 0 END) as nbr_check'),
        DB::Raw('sum(CASE WHEN (kyc = 1) THEN 1 ELSE 0 END) as nbr_approuved'),
        DB::Raw('sum(CASE WHEN (kyc = 2) THEN 1 ELSE 0 END) as nbr_rejeter'),
        DB::Raw('sum(CASE WHEN (kyc = 0) THEN 1 ELSE 0 END) as nbr_attente'),

      ))
      ->first();

    $actif = DB::table('abonnes as A')
      ->leftJoin('paiements as P', 'A.email', '=', 'P.payer_email')
      ->where('A.kyc', '=', '0')
      ->where('P.payment_status', '=', 'Complet')
      ->select('A.email')
      ->groupBy('A.email')
      ->get();



    return view('abonnes-mgmt/index', [
      'abonnes' => $abonnes,
      'stat' => $stat,
      'actif' => $actif,
    ]);
  }*/



  public function details()
  {

    $abonnes = DB::table('abonnes as A')
      ->leftJoin('paiements as P', 'A.email', '=', 'P.payer_email')
      ->where('A.kyc', '=', '0')
      ->where('P.payment_status', '=', 'Complet')
      ->select(array(
        DB::Raw('count(*) as nbr'),
        DB::Raw('A.email'),
        DB::Raw('sum(P.payment_amount) as somme'),
      
      ))
      ->groupBy('A.email')
      ->paginate(20);



    return view('abonnes-mgmt/details', [

      'abonnes' => $abonnes,
    ]);
  }

  public function kyc($email)
  {

    $this->mailconfirmation($email, 'kyc');

    echo "Une demande de KYC a été envoyé par mail.";

    return redirect()->intended('/abonnes-details');
  }

  public function telechargerdocument($id)
  {
    $document = DB::table('documents')->where('id', $id)->value('path');

    //dd($document);
    $path = storage_path("app/" . $document);
    return response()->download($path);
  }

  public function visualiser($id)
  {
    $document = DB::table('documents')->where('id', $id)->value('path');

    $path = storage_path("app/" . $document);
    $ext = File::extension($document);
    // dd(  $ext);
    if ($ext == 'pdf') {
      $content_types = 'application/pdf';
    } elseif ($ext == 'jpeg' || $ext == 'jpg') {
      $content_types = 'image/jpeg';
    } elseif ($ext == 'png') {
      $content_types = 'image/png';
    }

    return response()->file($path, [
      'Content-Type' => $content_types
    ]);
  }



  public function supprimerdocument($id)
  {

    $document = DB::table('documents')->where('id', $id)->get();
    $id_user = $document[0]->id_user;
    $path_doc = $document[0]->path;
    $path = storage_path("app/" . $path_doc);


    if (File::exists($path)) {

      File::delete($path);
      DB::table('documents')
        ->where('id', $id)
        ->delete();
      return $this->editabonnes($id_user);
    } else {
      DB::table('documents')
        ->where('id', $id)
        ->delete();

      return $this->editabonnes($id_user);
    }
  }



  /*public function editabonnes($id)
  {

    $user = DB::table('abonnes')

      ->where('id', '=', $id)->get();

    $documents = DB::table('documents')->where('id_user', '=', $id)->get();



    return view('abonnes-mgmt/editabonnes', ['user' => $user, 'documents' => $documents]);
  }*/

  public function updateAbonnes(Request $request)
  {

    $id = $request['idUser'];

    $files = $request->file('document');

    if ($request->hasFile('document')) {
      foreach ($files as $file) {
        $path = $file->store('avatars');
        $input['id_user'] = $id;
        $input['path'] = $path;
        Document::create($input);
      }
    }


    $input = [
      'genre' => $request['genre'],
      'username' => $request['username'],
      'prenom' => $request['prenom'],
      'phone' => $request['phone'],
      'email' => $request['email'],
      'adress' => $request['adresse'],
      'ville' => $request['Ville'],
      'code_postal' => $request['code_postal'],
      'pays_residence' => $request['pays_residence'],
      'date_naissance' => $request['date_naissance'],
      'type_doc' => $request['type_doc'],
      'numero_doc' => $request['numero_doc'],
      'date_emission' => $request['date_emission'],
      'date_expiration' => $request['date_expiration'],
      'kyc' => $request['kyc'],
    ];

    //dd($input);

    //$this->validate($request, $constraints);
    Abonne::where('id', $id)
      ->update($input);

    if ($request['kyc'] == 1) {

      //echo "approuvé";
      $this->mailconfirmation($id, 'approuve');
    } elseif ($request['kyc'] == 2) {
      //echo "rejeter";
      return  view('abonnes-mgmt/rejet', ['email' => $request['email']]);
    }

    return redirect()->intended('/abonnes-management');
  }




  public function mailrejet(Request $request)
  {


    //echo "approuvé";
    $this->mailconfirmationrejet($request);
    return redirect()->intended('/abonnes-management');
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request)
  {

    $idmax = Cache_table::max('id');
    $solde = Cache_table::where('id',  $idmax)->value('solde');


    $this->validateInput($request);
    $code_confirmation = str_shuffle(hexdec(uniqid()));

    $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant', 'solde', 'operation'];

    $input = $this->createQueryInput($keys, $request);
    // Not implement yet
    $input['code_confirmation'] =  $code_confirmation;


    if ($request['operation'] == 'depot') {

      $input['solde'] =  $solde + $request['montant'];
    } else {



      $input['solde'] =  $solde - $request['montant'];
    }


    //dd($request);
    Cache_table::create($input);

    return redirect()->intended('/cache-management');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {

    $pieces = explode("@", $id);
    $id = $pieces[0]; // piece1
    $action = $pieces[1]; // piece2

    if ($action == 'Virement') {


      $datacommande =  Paiement::select('payment_amount', 'payment_currency')->where('id_commande', $id)->get();
      $Taux_echange  = Taux_echange::select('taux_euros', 'taux_dollar')->get();

      $cigle = $datacommande[0]->payment_currency;

      if ($cigle == 'EUR') {
        $sommeMRU = ($datacommande[0]->payment_amount) * ($Taux_echange[0]->taux_euros);
      } else {
        $sommeMRU = ($datacommande[0]->payment_amount) * ($Taux_echange[0]->taux_dollar);
      }
      $input['payment_status'] = "Complet";
      $input['somme_mru'] = $sommeMRU;
      Paiement::where('id_commande', $id)
        ->update($input);

      //envoie de mail de reception virement
      //$this->mailconfirmation($id, 'virement');
    }


    return redirect()->intended('/cache-management/virement');
  }



  public function searchabonnes(Request $request)
  {

    if ($request['search'] == 'recherche') {

      $constraints = [
        'username' => $request['nom'],
        'email' => $request['email'],
        'phone' => $request['telephone'],
        'kyc' => $request['statut_kyc']
      ];
      //dd( $request);

      $stat = DB::table('abonnes')
        ->select(array(
          DB::Raw('sum(CASE WHEN (kyc = 3) THEN 1 ELSE 0 END) as nbr_check'),
          DB::Raw('sum(CASE WHEN (kyc = 1) THEN 1 ELSE 0 END) as nbr_approuved'),
          DB::Raw('sum(CASE WHEN (kyc = 2) THEN 1 ELSE 0 END) as nbr_rejeter'),
          DB::Raw('sum(CASE WHEN (kyc = 0) THEN 1 ELSE 0 END) as nbr_attente'),

        ))
        ->first();

      $actif = DB::table('abonnes as A')
        ->leftJoin('paiements as P', 'A.email', '=', 'P.payer_email')
        ->where('A.kyc', '=', '0')
        ->where('P.payment_status', '=', 'Complet')
        ->select('A.email')
        ->groupBy('A.email')
        ->get();

      $abonnes = $this->getabonnes($constraints);

      //dd($cashs);
      return view('abonnes-mgmt/index', [
        'abonnes' => $abonnes,
        'stat' => $stat,
        'actif' => $actif


      ]);
    } elseif ($request['search'] == 'excel') {

      $this->prepareExportingData($request)->export('xlsx');
      redirect()->intended('abonnes-mgmt/index');
    }
  }



  private function prepareExportingData($request)
  {

    $author = Auth::user()->username;

    $resultat = DB::table('abonnes')
      //->leftJoin('documents','abonnes.id', '=', 'documents.id_user')->whereNull('documents.id_user')

      ->Select('prenom', 'username', 'email', 'adress', 'phone', 'kyc', 'date_expiration')
      ->where('username', 'like', '%' . $request['nom'] . '%')
      ->where('email', 'like', '%' . $request['email'] . '%')
      ->where('phone', 'like', '%' . $request['telephone'] . '%')
      ->where('kyc', 'like', '%' . $request['statut_kyc'] . '%')
      ->get()
      ->map(function ($item, $key) {
        return (array) $item;
      })
      ->all();



    return Excel::create('Rapport_Abonnes', function ($excel) use ($resultat, $request, $author) {

      // Set the title
      $excel->setTitle('Liste des abonnes');

      $excel->setCreator($author)
        ->setCompany('HoaDang');

      // Call them separately
      $excel->setDescription('rapport');

      $excel->sheet('Rapport', function ($sheet) use ($resultat) {

        $sheet->fromArray($resultat);
      });
    });
  }


  private function getabonnes($constraints)
  {

    $abonnes = DB::table('abonnes')
      ->where('username', 'like', '%' . $constraints['username'] . '%')
      ->where('email', 'like', '%' . $constraints['email'] . '%')
      ->where('phone', 'like', '%' . $constraints['phone'] . '%')
      ->where('kyc', 'like', '%' . $constraints['kyc'] . '%')

      ->orderBy('confirmed_at', 'DESC')
      ->paginate(20);
    //dd($cashout);
    //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

    return $abonnes;
  }




  private function doSearchingQuery($constraints)
  {
    $query = DB::table('cache_tables')

      ->select('cache_tables.*');
    $fields = array_keys($constraints);
    $index = 0;
    foreach ($constraints as $constraint) {
      if ($constraint != null) {
        $query = $query->where($fields[$index], 'like', '%' . $constraint . '%');
      }

      $index++;
    }
    return $query->paginate(8);
  }

  /**
   * Load image resource.
   *m
   * @param  string  $name
   * @return \Illuminate\Http\Response
   */
  public function load($name)
  {
    $path = storage_path() . '/app/avatars/' . $name;
    if (file_exists($path)) {
      return Response::download($path);
    }
  }

  private function validateInput($request)
  {
    $this->validate($request, [

      'expediteur' => 'required',
      'nom_benef' => 'required',

      'montant' => 'required|numeric|min:0|not_in:0',

    ]);
  }

  private function createQueryInput($keys, $request)
  {
    $queryInput = [];
    for ($i = 0; $i < sizeof($keys); $i++) {
      $key = $keys[$i];
      $queryInput[$key] = $request[$key];
    }

    return $queryInput;
  }

  private function solde()
  {

    $idmax = Cache_table::max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }

  private function fraisGhaza($somme)
  {

    //$Ghaza = Division::where('somme_min','<=',$somme) -> where('somme_max','>=' ,$somme)-> value('taux');


    return 0;
  }





  public function parrainage()
  {


    $abonnes = Abonne::all('id');

    foreach ($abonnes as $abonne) {

      $id = uniqid();

      Abonne::where('id', $abonne->id)->update(['unique_id' => $id]);
    }
  }






  private function mailconfirmation($id, $type)
  {




    $tracker = DB::table('abonnes')

      ->select('id', 'email')
      ->where('id', '=', $id)
      ->orWhere('email', '=', $id)->get();







    $to = $tracker[0]->email; // Email de l'expediteur.
    $iduser = $tracker[0]->id; //$to = 'mboirick@yahoo.fr';


    if ($type == 'approuve') {

      $email_subject = "CADORIM : approbation des documents KYC";
      $email_body = '
      
      <div align="center">
      

<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #fff;padding: 5% 0;">
                                <tr>
                                    <td align="center" valign="top">
                                    <div align="left"><img src="https://dev.cadorim.com/mg/logos/cryp_log.png" alt="" width="180" /></div>
                                    </td>
                                    </tr>
                                    <tr>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;background-color: white">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;text-align: center;padding-top: 3%">
                                                                            <tr>
                                                                                <td>

                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                               
                                                                <tr style="background-color: #f7f8fb;">
                                                                    <td>
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                            <tr>
                                                                                <td style="text-align: center;width: 100%;">
                                                                                    <p style="text-align: justify;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                    Félicitations, votre compte cadorim a été approuvé. Vous pouvez désormais profiter pleinement de nos services.
                                                                                    </br>
                                                                                    Si vous avez des questions, n\'hésitez pas à nous envoyer un courriel à: compliance@cadorim.com.
                                                                                    </p>
                                                                                    </br></br>
                                                                                   
                                                                                    </br>
                                                                                    <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                        Meilleures salutations,
                                                                                    </p>
                                                                                    <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                        Cadorim compliance
                                                                                    </p>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                            <tr>
                                                                                <td style="text-align: center;width: 100%;background-color: white">
                                                                                    <p style="text-align: center;font-size: 14pxcolor: #cacaca;line-height: 1.5;">
                                                                                        Pour plus de d&#233;tails, connectez-vous &#224;
                                                                                    </p>
                                                                                    <p>
                                                                                        <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
                                                                                            <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
                                                                                                RETOUR AU SITE</div>
                                                                                        </a>
                                                                                    </p>
                                                                                </td>
                                                                                <td style="text-align: right;width: 40%;">
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                            <tr>
                                                                                <td style="text-align: center;width: 100%;">

                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: transparent">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td style="text-align: center;"><span style="font-size: 10px;color: #999999;">Copyright © 2020 cadorim.com. All Rights Reserved.</span></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                          
                                    </td>
                                </tr>
                            </table>

    
  

</div>

';
    } elseif ($type == 'rejeter') {

      $email_subject = "CADORIM : Documents KYC rejetès ";

      $email_body = '


      
      <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #fff;padding: 5% 0;">
                                      <tr>
                                          <td align="center" valign="top">
                                          <div align="left"><img src="https://dev.cadorim.com/mg/logos/cryp_log.png" alt="" width="180" /></div></td>
                                          

                                          </tr>  
                                          
                                          <tr>
                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                                                  <tbody>
                                                      <tr>
                                                          <td>
                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;background-color: white">
                                                                  <tbody>
                                                                      <tr>
                                                                          <td>
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;text-align: center;padding-top: 3%">
                                                                                  <tr>
                                                                                      <td>
      
                                                                                      </td>
                                                                                  </tr>
                                                                              </table>
                                                                          </td>
                                                                      </tr>
                                                                      
                                                                      <tr style="background-color: #f7f8fb;">
                                                                          <td>
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                                  <tr>
                                                                                      <td style="text-align: center;width: 100%;">
                                                                                          <p style="text-align: justify;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                          Vos informations KYC ont été rejetées. Vous pouvez afficher les détails sur le portail.</br>
                                                                                          Si vous avez des questions, n\'hésitez pas à nous envoyer un courriel à: compliance@cadorim.com.
                                                                                          </p>
                                                                                          </br>
                                                                                         
                                                                                          </br>
                                                                                          <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                              Meilleures salutations,
                                                                                          </p>
                                                                                          <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                                                              Cadorim compliance
                                                                                          </p>
                                                                                      </td>
                                                                                  </tr>
                                                                              </table>
                                                                          </td>
                                                                      </tr>
                                                                      <tr>
                                                                          <td>
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                                  <tr>
                                                                                      <td style="text-align: center;width: 100%;background-color: white">
                                                                                          <p style="text-align: center;font-size: 14pxcolor: #cacaca;line-height: 1.5;">
                                                                                              Pour plus de d&#233;tails, connectez-vous &#224;
                                                                                          </p>
                                                                                          <p>
                                                                                              <a href="https://cadorim.com/" style="color: #fff; text-decoration: none">
                                                                                                  <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px;border-radius: 20px;color: #f7f8fb">
                                                                                                      RETOUR AU SITE</div>
                                                                                              </a>
                                                                                          </p>
                                                                                      </td>
                                                                                      <td style="text-align: right;width: 40%;">
                                                                                      </td>
                                                                                  </tr>
                                                                              </table>
                                                                          </td>
                                                                      </tr>
                                                                      <tr>
                                                                          <td>
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                                                  <tr>
                                                                                      <td style="text-align: center;width: 100%;">
      
                                                                                      </td>
                                                                                  </tr>
                                                                              </table>
                                                                          </td>
                                                                      </tr>
                                                                  </tbody>
                                                              </table>
                                                          </td>
                                                      </tr>
                                                      <tr>
                                                          <td>
                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
                                                                  <tbody>
                                                                      <tr>
                                                                          <td>
                                                                              <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: transparent">
                                                                                  <tbody>
                                                                                      <tr>
                                                                                          <td style="text-align: center;"><span style="font-size: 10px;color: #999999;">Copyright © 2020 cadorim.com. All Rights Reserved.</span></td>
                                                                                      </tr>
                                                                                  </tbody>
                                                                              </table>
                                                                          </td>
                                                                      </tr>
                                                                  </tbody>
                                                              </table>
                                                          </td>
                                                      </tr>
                                                  </tbody>
                                              </table>
                                
                                          </td>
                                      </tr>
                                  </table>
      
          
        
        </td>
      </tr>
      </tbody>
      
</table>
   
      



';
    } elseif ($type == 'injoignable') {

      $email_subject = "Bénéficiaire injoignable - CADORIM";

      $email_body = '


<div align="center">
<table width="74%" border="0" style="height: 420px; width: 80%; border:#000000 solid 0px">
<tbody>
<tr>
<td bgcolor="#000000" height="50">
<div align="left"><img src="https://cadorim.com/mg/logos/logo.png" alt="" width="200" /></div></td>
</tr>
<tr align="center">
<td style="text-align: center; margin:auto" align="center">
<table width="100%" border="0">
  <tr>
    <td>
    
    <p align="left"> Bonjour Mme(Mrs) :' . $tracker[0]->nom_exp . '   </p>
    <p align="left">
    
    L\'éuiqpe cadorim a essayé de joindre par téléphone, Mme(Mrs) ' . $tracker[0]->nom_benef . ' au numéro de téléphone :<strong>' . $tracker[0]->phone_benef . '</strong>. sans succès<br>
    Nous vous invitons à lui demander de nous contacter au  <strong>  Tel: +222 48 13 30 66  |  Tel2: +222 45 29 40 07.</strong> afin de procéder au retrait.<br>
    </p></td>
  </tr>
</table>
<div>
      <div align="left" ><strong> Details de l\'envoi: </strong></div>
    </div>
    <table cellpadding="5" cellspacing="3" width="100%" border="0"  >
      <tbody>
        <tr>
          <td width="46%" style="border:#999999 solid 1px"><div align="left"><strong>Expéditeur :</strong><br>
          </div></td>
          
          <td width="52%" style="border:#999999 solid 1px"><div align="left"><strong>Bénéficiaire :</strong></div></td>
        </tr>
        <tr>
          <td ><div align="left">' . $tracker[0]->nom_exp . '<br>
          </div></td>
          
          <td ><div align="left">' . $tracker[0]->nom_benef . '<br>
          </div></td>
        </tr>
        <tr>
          <td ><div align="left">Tel : ' . $tracker[0]->phone_exp . '<br>
          </div></td>
          
          <td ><div align="left">Tel : ' . $tracker[0]->phone_benef . '<br>
          </div></td>
        </tr>
        <tr>
          <td > <div align="left">Email : ' . $tracker[0]->mail_exp . '</div></td>
          
          <td ><div align="left">Adresse : ' . $tracker[0]->adress_benef . '<br>
          </div></td>
        </tr>
      </tbody>
    </table>
    <br />
    <div>
      <div align="left" ><strong>Details de la commande:</strong></div>
    </div>
    
  <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td style="border:#999999 solid 1px"><div align="left"><strong>Produit</strong></div></td>
    <td style="border:#999999 solid 1px"><div align="left"><strong>Quantite</strong></div></td>
    <td style="border:#999999 solid 1px"><div align="left"><strong>Prix</strong></div></td>
  </tr>
  
  ' . $panier . '
  
    <tr>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px"><div align="left">Frais d\'envoi : 0 ' . $tracker[0]->payment_currency . '</div></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ><div align="left"><strong>Total : ' . $tracker[0]->payment_amount . ' ' . $tracker[0]->payment_currency . '</strong></div></td>
  </tr>
</table>

  
  <br />
    
  <p>L\'equipe CADORIM  </p>
  
  N’hésitez pas a visiter notre page Facebook et LinkeDin et partager votre expérience avec vos amis & proches.
  </p>
   <br>
	
	 <table width="100%" height="40" border="0" style="background-color:#212529">
  <tr>
    <td  align="center"><a href="https://www.facebook.com/cradmin1/ ">
                </a><a href="https://twitter.com/Cadorim2"><img src="https://cadorim.com/img/logos/rs_twit.PNG"></a><a href="https://www.facebook.com/cradmin1/ "><img src="https://cadorim.com/img/logos/rs_fb.PNG">
                </a><a href="https://www.linkedin.com/company/35526670/admin/"><img src="https://cadorim.com/img/logos/rs_in.PNG"></a></td>
  </tr>
</table>
    
    </td>
</tr>
</tbody>
</table>
</div>




';
    } elseif ($type == 'kyc') {

      $email_subject = "KYC- CADORIM";

      $email_body =  '
  

      <div align="center">
      <table width="74%" border="0" style=" width: 80%; ">
      <tbody>
      <tr>
      <td >
      <div align="left"><img src="https://dev.cadorim.com/mg/logos/cryp_log.png" alt="" width="150" /></div></td>
      </tr>
      <tr align="center">
      <td style="text-align: center; margin:auto" align="center">
      <table width="100%" border="0">
        <tr>
          <td>
          
          <p align="left"> Bonjour   </p>
          <p align="left">
          Merci d’avoir utilisé notre service pour vos transfert d\'argent, on vous prie de bien vouloir nous envoyer le plutôt 
          possible un document d’identité (Passeport, pièce d’identité ou un permis de conduire), à l’adresse : compliance@cadorim.com ou sur le lien Ci-dessous

          <a href="https://admin.cadorim.com/kyc-management/' . $iduser  . '/' . $to . '" style="color: #fff; text-decoration: none"> 
          <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px; border-radius: 20px;color: #f7f8fb">
          LIEN KYC</div>
          </a>
          
          
          
          </p>

          <p align="left">
          Cette démarche s\'inscrit dans le cadre de la réglementation européenne et internationale en matière de transfert d\'argent,
          dont l\'objectif est de lutter contre le blanchiment d\'argent et le financement du terrorisme.
         
          </p>
          <p align="left">
          
          Veuillez ne pas tenir compte de ce message si vous nous avez déja envoyé ce document.
          </p></td>
        </tr>
      </table>

        <p>L\'équipe CADORIM. </p>
        </td>
      </tr>
      </tbody>
      <tr><td>
     </td></tr>
      </table>
      </div>
      
';
    }

    $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
    $headers .= "Reply-To: noreply@cadorim.com\n";

    $headers .= 'Bcc:  cadorimc@gmail.com, bmneine@gmail.com, cadorim.stat@gmail.com' . "\r\n";
    //$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
    $headers .= "Content-Type: text/html; charset=\"utf8\"";



    mail($to, $email_subject, $email_body, $headers);
  }

  private function mailconfirmationrejet($request)
  {


    $to = $request['emailto'];
    $email_subject = $request['subject'];
    $email_body = $request['text'];

    $headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
    $headers .= "Reply-To: noreply@cadorim.com\n";

    $headers .= 'Bcc:  cadorimc@gmail.com, bmneine@gmail.com, cadorim.stat@gmail.com' . "\r\n";
    //$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
    $headers .= "Content-Type: text/html; charset=\"utf8\"";

    mail($to, $email_subject, $email_body, $headers);
  }
}
