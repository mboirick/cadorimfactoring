<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use Session;
use App\Coordonnes_commande;
use App\Paiement;
use App\Commande;
use App\Stat_cash;
use App\Solde_client;
use App\Cache_table;
use Excel;
use Auth;
use App\Client;
use App\Agence;


class CacheController extends Controller
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
    $constraints = [
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ", strtotime(" 1 day"))

    ];


    $clients = DB::table('users')->where('user_type', '=', 'business')->whereNotNull('email_verified_at')->get();

    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    $caches = DB::table('cache_tables')
      ->select('cache_tables.*')
      ->where('created_at', '>=', $constraints['from'])
      ->where('created_at', '<=', $constraints['to'])
      ->whereNull('deleted_at')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);



    return view('cache-mgmt/index', [
      'caches' => $caches,
      'clients' => $clients,
      'idmax' => $idmax,
      'searchingVals' => $constraints

    ]);
  }*/

  public function details($id)
  {


    $constraints = [
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ")

    ];

    $caches = DB::table('cache_tables')

      ->select('cache_tables.*')
      ->where('id_client', '=', $id)
      ->whereNull('deleted_at')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);


    return view('cache-mgmt/details', [
      'caches' => $caches,
      'searchingVals' => $constraints

    ]);
  }





  private function prepareExportingData($request)
  {
    $author = Auth::user()->username;
    $employees = $this->getExportingData(['from' => $request['from'], 'to' => $request['to']]);
    return Excel::create('Rapport_from_' . $request['from'] . '_to_' . $request['to'], function ($excel) use ($employees, $request, $author) {

      // Set the title
      $excel->setTitle('Liste de transaction from ' . $request['from'] . ' to ' . $request['to']);

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


  private function getExportingData($constraints)
  {
    return DB::table('cache_tables')
      ->select('*')
      ->where('created_at', '>=', $constraints['from'])
      ->where('created_at', '<=', $constraints['to'])
      ->whereNull('deleted_at')
      ->get()
      ->map(function ($item, $key) {
        return (array) $item;
      })
      ->all();
  }






  public function exportExcel_cashout(Request $request)
  {
    $this->prepareExportingData_cashout($request)->export('xlsx');
    redirect()->intended('atlpay-mgmt/index');
  }

  private function prepareExportingData_cashout($request)
  {
    $author = Auth::user()->username;
    $employees = $this->getExportingData_cashout([
      'from' => $request['from'], 'to' => $request['to'], 'expediteur' => $request['expediteur'], 'beneficiaire' => $request['beneficiaire'],
      'statut' => $request['statut']
    ]);
    return Excel::create('Rapport_from_' . $request['from'] . '_to_' . $request['to'], function ($excel) use ($employees, $request, $author) {

      // Set the title
      $excel->setTitle('Liste de transaction from ' . $request['from'] . ' to ' . $request['to']);

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


  private function getExportingData_cashout($constraints)
  {
    return DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
      ->leftJoin('abonnes', 'abonnes.email', '=', 'coordonnes_commandes.mail_exp')
      ->select(
        'coordonnes_commandes.nom_exp',
        'coordonnes_commandes.nom_benef',
        'paiements.payment_amount',
        'coordonnes_commandes.frais_gaza',
        'paiements.somme_mru',
        'paiements.updated_at',
        'coordonnes_commandes.tracker_status'
      )
      ->where('coordonnes_commandes.phone_exp', 'like', '%' . $constraints['expediteur'] . '%')
      ->where('coordonnes_commandes.phone_benef', 'like', '%' . $constraints['beneficiaire'] . '%')
      ->where('coordonnes_commandes.tracker_status', 'like', '%' . $constraints['statut'] . '%')
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('commandes.date_commande', '>=', $constraints['from'])
      ->where('commandes.date_commande', '<=', $constraints['to'])
      ->get()
      ->map(function ($item, $key) {
        return (array) $item;
      })
      ->all();
  }







  public function editSolde($id)
  {



    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    if ($id == $idmax) {

      $soldedit = DB::table('cache_tables')

        ->where('id', '=', $id)->first();

      return view('cache-mgmt/editsolde', ['soldedit' => $soldedit]);
    }
  }

  public function updatesolde(Request $request)
  {

    // dd($request->request);
    if ($request['action'] == 'modifier') {


      $infos_avant = Cache_table::where('id',  $request['id_operation'])->first();


      if ($infos_avant) {

        if ($infos_avant->operation == 'depot') {

          $newsolde =  $infos_avant->solde_avant + $request['montant'];
        } else {

          $newsolde =  $infos_avant->solde_avant - $request['montant'];
        }


        DB::table('cache_tables')->where('id', '=', $request['id_operation'])->update([
          'montant' => $request['montant'], 'solde_apres' => $newsolde,
          'solde' => $newsolde,
          'phone_benef' => $request['phone_benef']
        ]);
      }



      $infos_agence = Agence::where('id_client',  $infos_avant->id_client)->where('indice',  1)->first();

      if ($infos_agence) {

        Agence::where('id',   $infos_agence->id)->update([
          'montant_mru' => $request['montant'],
          'solde_avant_mru' => $infos_agence->solde_avant_mru,
          'solde_mru' => $infos_agence->solde_avant_mru + $request['montant']
        ]);
      }
      $infos_business = Solde_client::where('id_client',  $infos_avant->id_client)->where('indice',  1)->first();

      if ($infos_business) {

        Solde_client::where('id',   $infos_business->id)->update([
          'montant_mru' => $request['montant'],
          'solde_avant_mru' => $infos_business->solde_avant_mru,
          'solde_mru' => $infos_business->solde_avant_mru - $request['montant']
        ]);
      }

      return redirect()->intended('/cache-management');
    }
    if ($request['action'] == 'Supprimer') {


      $idmax = Cache_table::max('id');
      $solde = Cache_table::where('id',  $idmax)->value('solde');

      $this->validateInput($request);
      $code_confirmation = str_shuffle(hexdec(uniqid()));

      $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant', 'solde', 'operation'];

      $input = $this->createQueryInput($keys, $request);
      // Not implement yet
      $input['code_confirmation'] =  $code_confirmation;


      if ($request['operation'] == 'depot') {

        $solde =  $solde - $request['montant'];
        $this->soldeclientupdate($request);
      } else {
        $this->soldeclientupdate($request);
        $solde =  $solde + $request['montant'];
      }
      DB::table('cache_tables')->where('id', '=', $request['id_operation'])->delete();


      $idmax = Cache_table::max('id');
      DB::table('cache_tables')->where('id', '=', $idmax)->update(['solde' => $solde]);
      //dd($request);
      //Cache_table::create($input);

      return redirect()->intended('/cache-management');
    }
  }


  private function soldeclientupdate($request)
  {

    //dd($request->request);

    $soldeclient = DB::table('clients')->where('id', '=', $request['nom_benef'])->get();

    $solde = $soldeclient[0]->solde;

    if ($request['operation'] == 'depot') {

      $solde = $solde - $request['montant'];
      DB::table('clients')->where('id', '=', $request['nom_benef'])
        ->update(['cash_in' => $request['montant'], 'solde' => $solde]);
    }
    if ($request['operation'] == 'retrait') {
      $solde = $solde  + $request['montant'];
      DB::table('clients')->where('id', '=', $request['nom_benef'])
        ->update(['cash_out' => $request['montant'], 'solde' => $solde]);
    }
  }




  public function addcash()
  {
    //$clients = Client::all();
    $clients = DB::table('users')
      ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
      ->select('users.id_client', 'users.firstname')
      ->where('user_type', '=', 'business')->where('solde_client.indice', '=', '1')->get();

    return view('cache-mgmt/create', ['clients' => $clients]);
  }



  public function retraitcash()
  {


    // $clients = Client::all();
    $clients = DB::table('users')
      ->leftJoin('solde_client', 'solde_client.id_client', '=', 'users.id_client')
      ->select('users.id_client', 'users.firstname')
      ->where('user_type', '=', 'business')->where('solde_client.indice', '=', '1')->get();

    return view('cache-mgmt/retrait', ['clients' => $clients]);
  }



  public function addcashstore(Request $request)
  {


    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    $solde = Cache_table::where('id',  $idmax)->value('solde');

    $this->validateInput($request);
    $code_confirmation = str_shuffle(hexdec(uniqid()));

    $keys = ['expediteur', 'phone_benef',  'montant_euro', 'montant', 'operation'];

    $input = $this->createQueryInput($keys, $request);
    // Not implement yet

    $input['code_confirmation'] =  $code_confirmation;


    if ($request['operation'] == 'depot') {

      $input['solde_avant'] = $solde;
      $input['solde'] =  $solde + $request['montant'];
      $input['solde_apres'] = $solde + $request['montant'];

      $this->depotclient($request);
    } elseif ($request['operation'] == 'retrait') {
      $input['solde_avant'] = $solde;
      $input['solde'] =  $solde - $request['montant'];
      $input['solde_apres'] = $solde - $request['montant'];
    }

    $societe = DB::table('users')->where('id_client', '=', $request['nom_benef'])->value('firstname');
    if ($societe) {
      $input['nom_benef'] = $societe;
    } else {

      $input['nom_benef'] = $request['nom_benef'];
    }


    $input['id_client'] = $request['nom_benef'];


    $this->soldeclient($request);

    Cache_table::create($input);

    Session::flash('message', ' Modification effectuée avec success!  تم التعديل بنجاح!'); 

    return redirect()->intended('/cash/flow/home');
  }

  private function soldeclient($request)
  {

    $id = $request['nom_benef'];
    $solde = Client::where('id',  $id)->value('solde');

    if ($request['operation'] == 'depot') {
      $solde = $solde + $request['montant'];

      Client::where('id', '=', $id)->update(['cash_in' => $request['montant'], 'solde' => $solde]);
    }
    if ($request['operation'] == 'retrait') {

      $solde = $solde - $request['montant'];

      Client::where('id', '=', $id)->update(['cash_out' => $request['montant'], 'solde' => $solde]);
    }
  }


  public function edit($id)
  {
    //$editecash = Coordonnes_commande::where('id_commande', '=', $id)->get();

    $editecash = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
      ->select('coordonnes_commandes.*', 'paiements.*', 'abonnes.kyc')
      ->where('coordonnes_commandes.id_commande', '=', $id)->get();
    $somme_mru = (float) $editecash[0]->somme_mru;
    $Ghaza = $this->fraisGhaza($somme_mru);


    // dd( $Ghaza);
    return view('cache-mgmt/edit', ['editecash' => $editecash, 'Ghaza' => $Ghaza]);
  }


  public function update(Request $request, $id)
  {


    if ($request['operation'] == 'retrait') {

      //echo "ici retait ";
      $etat = Coordonnes_commande::select('tracker_status')->where('id_commande',  $id)->value('tracker_status');

      if ($etat == "attente") {
        $input['frais_gaza'] = $request['frais_gaza'];
        $charge = $request['somme_mru'] + $request['frais_gaza'];

        $input['tracker_status'] = 'retire';


        $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
        $solde = Cache_table::select('solde')->where('id',  $idmax)->value('solde');

        $solde = $solde - $charge;

        $inputsolde['solde'] = $solde;

        Coordonnes_commande::where('id_commande', $id)
          ->update($input);
        Cache_table::where('id', $idmax)
          ->update($inputsolde);

        $this->mailconfirmation($id, 'retrait');
      }
      $etat = Coordonnes_commande::select('tracker_status')->where('id_commande',  $id)->value('tracker_status');
    } elseif ($request['operation'] == 'transfert') {
      //echo "ici transfert";

    } elseif ($request['operation'] == 'email') {
      ///echo "ici email";
      $this->mailconfirmation($id, 'injoignable');
    } elseif ($request['operation'] == 'kyc_demande') {

      $this->mailconfirmation($id, 'kyc');
    }


    return redirect()->intended('/cache-management/cashout');
  }


  public function destroy($id)
  {
    Paiement::where('id_commande', $id)->delete();
    Coordonnes_commande::where('id_commande', $id)->delete();
    Commande::where('id_commande', $id)->delete();
    return redirect()->intended('/cache-management/virement');
  }



  public function search(Request $request)
  {



    if ($request['search'] == 'excel') {

      $this->prepareExportingData($request)->export('xlsx');
      redirect()->intended('cache-mgmt/index');
    } else {
      $solde = $this->solde();
      $constraints = [
        'from' => $request['from'],
        'to' => $request['to'],
        'type' => $request['type'],
        'id_client' => $request['id_client']
      ];

      $cashs = $this->getCashs($constraints);
      $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
      $clients = DB::table('users')->where('user_type', 'business')->get();

      return view('cache-mgmt/index', [
        'caches' => $cashs,
        'solde' => $solde,
        'idmax' => $idmax,
        'clients' =>  $clients,
        'searchingVals' => $constraints
      ]);
    }
  }




  private function getCashs($constraints)
  {


    $cashs = Cache_table::where('created_at', '>=', $constraints['from'])
      ->where('created_at', '<=', $constraints['to'])
      ->where('operation', 'like', '%' . $constraints['type'] . '%')
      ->where('id_client',  $constraints['id_client'])
      ->orWhere('nom_benef', 'like', '%' . $constraints['id_client'] . '%')
      ->whereNull('deleted_at')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);
    return $cashs;
  }


  public function searchCashOut(Request $request)
  {

    $constraints = [
      'expediteur' => $request['expediteur'],
      'beneficiaire' => $request['beneficiaire'],
      'from' => $request['from'],
      'to' => $request['to'],
      'statut' => $request['statut']
    ];
    $abonnes = DB::table('abonnes')->count();
    $solde = $this->solde();
    $soldecashout = $this->soldecashout();
    $cashout = $this->getCashsOut($constraints);
    $virementEUR = $this->soldevirementEUR();
    $virementUSD = $this->soldevirementUSD();
    $abonnesmois = $this->abonnesparmois();
    //dd($cashs);
    return view('cache-mgmt/cash-out', [
      'cashout' => $cashout,
      'searchingVals' => $constraints,
      'solde' => $solde,
      'abonnes' => $abonnes,
      'soldecashout' => $soldecashout,
      'abonnesmois' => $abonnesmois,
      'virementEUR' => $virementEUR,
      'virementUSD' => $virementUSD

    ]);
  }


  private function getCashsOut($constraints)
  {

    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
      ->leftJoin('abonnes', 'abonnes.email', '=', 'coordonnes_commandes.mail_exp')
      ->select('coordonnes_commandes.*', 'paiements.*', 'commandes.*', 'abonnes.kyc')
      ->where('coordonnes_commandes.phone_exp', 'like', '%' . $constraints['expediteur'] . '%')
      ->where('coordonnes_commandes.phone_benef', 'like', '%' . $constraints['beneficiaire'] . '%')
      ->where('coordonnes_commandes.tracker_status', 'like', '%' . $constraints['statut'] . '%')
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('commandes.date_commande', '>=', $constraints['from'])
      ->where('commandes.date_commande', '<=', $constraints['to'])
      ->orderBy('commandes.date_commande', 'DESC')
      ->paginate(20);
    //dd($cashout);
    //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

    return $cashout;
  }

  public function rapportquotidien($date)
  {

    $cachesout = $this->cashesout($date);
    $cashs_in = $this->cashin($date);

    $solde_client =  $this->soldebusiness($date);


    $solde_cadorim =  $this->soldecadorim($date);
    //



    return view('cache-mgmt/rapportquotidien', [
      'cachesout' => $cachesout,
      'cashs_in' => $cashs_in,
      'solde_client' => $solde_client,
      'solde_cadorim' => $solde_cadorim


    ]);
  }




  public function rapportquotidien1()
  {


    $day =  DB::table('stat_cashs')->latest('jours')->value('jours');


    if ($day) {
    } else {

      $day = '2020-08-18';
    }


    $operations =  DB::table('cache_tables as w')
      ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
      ->where('operation', '=', 'retrait')
      ->whereDate('created_at', '>', $day)
      ->select(array(
        DB::Raw('count(*) as nbr'),
        DB::Raw('count(c.frais_gaza) as nbrtrans'),
        DB::Raw('sum(w.montant_euro) as somme_euro'),
        DB::Raw('sum(w.montant) as somme'),
        DB::Raw('sum(c.frais_gaza) as somme_gaza'),
        DB::Raw('DATE(w.created_at) day'),
        DB::Raw('max(w.id) maxsold'),
        DB::Raw('min(w.id) minsold')
      ))
      ->groupBy('day')
      ->get();
    foreach ($operations as $operation) {
      $maxsold =  DB::table('cache_tables')->whereDate('created_at', '>', $operation->day)->latest()->value('solde');
      $minsold = DB::table('cache_tables')->whereDate('created_at', '>', $operation->day)->oldest()->value('solde');
      $operation->maxsold = $maxsold;
      $operation->minsold = $minsold;
    }


    $update =  DB::table('cache_tables as w')
      ->leftJoin('coordonnes_commandes', 'w.code_confirmation', '=', 'coordonnes_commandes.id_commande')
      ->where('expediteur', '=', $email)
      ->whereDate('created_at', '=', $day)
      ->select(array(
        DB::Raw('count(*) as nbr'),
        DB::Raw('sum(w.montant_euro) as somme_euro'),
        DB::Raw('sum(w.montant) as somme'),
        DB::Raw('sum(coordonnes_commandes.frais_gaza) as somme_gaza'),
        DB::Raw('DATE(w.created_at) day')
      ))
      ->groupBy('day')
      ->get();


    foreach ($operations as $operation) {
      $input = [
        'id_agence' => $id,
        'email_agence' => $email,
        'jours' => $operation->day,
        'nbr_operation' =>  $operation->nbr,
        'total' =>  $operation->somme,
        'total_gaza' =>  $operation->somme_gaza,
        'total_euro' =>  $operation->somme_euro
      ];
      Stat_agence::create($input);
    }

    foreach ($update as $operation) {
      DB::table('stat_agences')
        ->where('jours', '=', $day)
        ->where('email_agence', '=', $email)
        ->update([
          'id_agence' => $id,
          'email_agence' => $email,
          'jours' => $operation->day,
          'nbr_operation' =>  $operation->nbr,
          'total' =>  $operation->somme,
          'total_gaza' =>  $operation->somme_gaza,
          'total_euro' =>  $operation->somme_euro

        ]);
    }

    $operations =  DB::table('stat_agences')
      ->where('email_agence', '=', $email)
      ->orderBy('jours', 'DESC')
      ->paginate(20);

    return view('agence-mgmt/operationstory', [
      'operations' => $operations,


    ]);
  }





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

    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }


  private function add_solde($somme)
  {

    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }

  private function remove_solde($somme)
  {

    $idmax = Cache_table::max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }

  private function update_solde($somme)
  {

    $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }


  private function depotclient($request)
  {


    $montant_euro = $request['montant_euro'];
    $montant_mru = $request['montant'];

    $solde_crediteur = DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['nom_benef'])->first();

    DB::table('solde_client')->where('indice', '=', '1')->where('id_client', '=', $request['nom_benef'])->update([
      'indice' => 0
    ]);

    Solde_client::create([
      'id_client' => $request['nom_benef'],
      'id_client_debiteur' => 'CADORIM',
      'solde_avant_euros' => $solde_crediteur->solde_euros,
      'solde_avant_mru' => $solde_crediteur->solde_mru,
      'solde_euros' => $solde_crediteur->solde_euros + $montant_euro,
      'solde_mru' => $solde_crediteur->solde_mru +  $montant_mru,
      'montant_euros' =>  $montant_euro,
      'taux' => '0',
      'montant_mru' => $montant_mru,
      'motif' => $request['phone_benef'],
      'type_opperation' => 'Dépot'
    ]);


    return 0;
  }

  private function cashesout($date)
  {

    $opps =  DB::table('cache_tables')
      ->where('operation', '=', 'retrait')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select(array(
        DB::Raw('DISTINCT(expediteur)'),
      ))
      ->get();

    foreach ($opps as $opp) {

      $cachesout[$opp->expediteur] =  DB::table('cache_tables as w')
        ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
        ->where('operation', '=', 'retrait')
        ->where('expediteur', '=', $opp->expediteur)
        ->whereDate('created_at', 'like', '%' . $date . '%')
        ->select(array(
          DB::Raw('count(*) as nbr'),
          DB::Raw('sum(w.montant) as somme'),
          DB::Raw('sum(c.frais_gaza) as somme_gaza'),
          DB::Raw('sum(CASE WHEN (frais_gaza = 0) THEN w.montant ELSE 0 END) as somme_local'),

        ))

        ->first();
    }

    $cachesout['Total'] = DB::table('cache_tables as w')
      ->leftJoin('coordonnes_commandes as c', 'w.code_confirmation', '=', 'c.id_commande')
      ->where('operation', '=', 'retrait')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select(array(
        DB::Raw('count(*) as nbr'),
        DB::Raw('sum(w.montant) as somme'),
        DB::Raw('sum(c.frais_gaza) as somme_gaza'),
        DB::Raw('sum(CASE WHEN (frais_gaza = 0) THEN w.montant ELSE 0 END) as somme_local'),
      ))
      ->first();

    return $cachesout;
  }

  private function cashin($date)
  {
    $cash_in =  DB::table('cache_tables')

      ->where('operation', '=', 'depot')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select('*')

      ->get();


    return $cash_in;
  }

  private function soldebusiness($date)
  {
    $clientsolde =  DB::table('solde_client as s')
      ->leftJoin('users as u', 's.id_client', '=', 'u.id_client')
      ->where('s.indice', '=', '1')
      ->whereNotNull('u.email_verified_at')
      ->select('s.id_client', 'u.firstname')->orderByDesc('s.created_at')->get();



    $y = 0;
    foreach ($clientsolde as $client) {

      $soldeFin =  DB::table('solde_client as s')
        ->whereDate('s.created_at', 'like', '%' . $date . '%')
        ->where('s.id_client', '=', $client->id_client)
        ->select('solde_euros', 'solde_mru')->orderByDesc('s.id')->first();

      if (!$soldeFin) {

        $soldeFin =  DB::table('solde_client as s')
          ->where('s.id_client', '=', $client->id_client)
          ->select('solde_euros', 'solde_mru')->orderByDesc('s.id')->first();
      }

      $soldeDebut =  DB::table('solde_client as s')
        ->whereDate('s.created_at', 'like', '%' . $date . '%')
        ->where('s.id_client', '=', $client->id_client)
        ->select('solde_avant_euros', 'solde_avant_mru')->orderBy('s.id')->first();

      if (!$soldeDebut) {

        $soldeDebut =  DB::table('solde_client as s')
          ->where('s.id_client', '=', $client->id_client)
          ->select('solde_avant_euros', 'solde_avant_mru')->orderByDesc('s.id')->first();
      }

      // dd($soldeFin);

      $result['email' . $y] = $client->firstname;
      $result['soldeFinMru' . $y] = $soldeFin->solde_mru;
      $result['soldeDebutMru' . $y] = $soldeDebut->solde_avant_mru;
      $result['soldeFinEur' . $y] = $soldeFin->solde_euros;
      $result['soldeDebutEur' . $y] = $soldeDebut->solde_avant_euros;

      $y++;
    }

    return $result;
  }



  private function soldecadorim($date)
  {


    $opps =  DB::table('agences')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select(array(
        DB::Raw('DISTINCT(id_client)'),
      ))
      ->get();

    $soldeFin =  DB::table('cache_tables')
      ->where('id_client', '!=', '99')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select('solde', 'solde_avant')->orderByDesc('id')->first();
    if (!$soldeFin) {
      $soldeFin = DB::table('cache_tables')
        ->where('id_client', '!=', '99')
        ->select('solde', 'solde_avant')->orderByDesc('id')->first();
    }

    $soldeDebut =  DB::table('cache_tables')
      ->where('id_client', '!=', '99')
      ->whereDate('created_at', 'like', '%' . $date . '%')
      ->select('solde_avant')->orderBy('id')->first();
    if (!$soldeDebut) {

      $soldeDebut =  DB::table('cache_tables')
      ->where('id_client', '!=', '99')
    
      ->select('solde_avant')->orderByDesc('id')->first();
    }



    $result['compte0'] = 'CADORIM';
    $result['soldeActuel0'] = $soldeFin->solde;
    $result['soldeFin0'] = $soldeFin->solde_avant;
    $result['soldeDebut0'] = $soldeDebut->solde_avant;

    $i = 1;
    foreach ($opps as $opp) {

      $soldeFin =  DB::table('agences as A')
        ->leftJoin('users as U', 'A.id_client', '=', 'U.id_client')
        ->where('A.id_client', '=', $opp->id_client)
        ->whereDate('A.created_at', 'like', '%' . $date . '%')
        ->select('A.solde_avant_mru','A.solde_mru', 'U.email')->orderByDesc('A.id')->first();

      $soldeDebut  =  DB::table('agences')
        ->where('id_client',  '=', $opp->id_client)
        ->whereDate('created_at', 'like', '%' . $date . '%')
        ->select('solde_avant_mru')->orderBy('id')->first();


      $result['compte' . $i] = $soldeFin->email;
      $result['soldeActuel' . $i] = $soldeFin->solde_mru;
      $result['soldeFin' . $i] = $soldeFin->solde_avant_mru;
      $result['soldeDebut' . $i] = $soldeDebut->solde_avant_mru;
      $i++;
    }

  
    return $result;
  }
}
