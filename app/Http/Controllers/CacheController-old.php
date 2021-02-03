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
use App\Cache_table;
use Excel;
use Auth;
use App\Client;


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
  public function index()
  {
    $constraints = [
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ", strtotime(" 1 day"))

    ];


    $clients = DB::table('clients')->get();

    $caches = DB::table('cache_tables')

      ->select('cache_tables.*')
      ->where('created_at', '>=', $constraints['from'])
      ->where('created_at', '<=', $constraints['to'])
      ->whereNull('deleted_at')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);



    return view('cache-mgmt/index1', [
      'caches' => $caches,
      'clients' => $clients,
      'searchingVals' => $constraints

    ]);
  }

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

  public function client()
  {

    $clients = DB::table('clients')

      ->select('*')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);


    return view('cache-mgmt/client', [
      'clients' => $clients
    ]);
  }

  public function creatclient(Request $request)
  {

    //$path = $request->file('picture')->store('avatars');   

    $keys = [
      'societe', 'nom_prenom', 'adresse',
      'telephone', 'remarque'
    ];
    $input = $this->createQueryInput($keys, $request);
    // $input['picture'] = $path;

    Client::create($input);

    return redirect()->intended('/cache-management/clients');
  }



  public function editClient($id)
  {

    $clientedit = DB::table('clients')

      ->where('id', '=', $id)->get();

    return view('cache-mgmt/editclient', ['clientedit' => $clientedit]);
  }

  public function updateClient($id, Request $request)
  {


    $keys = [
      'societe', 'nom_prenom', 'adresse',
      'telephone', 'remarque'
    ];
    $input = $this->createQueryInput($keys, $request);
    $inputcash['nom_benef'] = $request['societe'];

    DB::table('clients')->where('id', '=', $id)->update($input);
    DB::table('cache_tables')->where('id_client', '=', $id)->update($inputcash);

    $clients = DB::table('clients')

      ->select('*')
      ->orderBy('created_at', 'DESC')
      ->paginate(20);


    return view('cache-mgmt/client', [
      'clients' => $clients
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
      ->select('coordonnes_commandes.nom_exp', 'coordonnes_commandes.nom_benef', 'paiements.payment_amount', 'coordonnes_commandes.frais_gaza', 'paiements.somme_mru', 'paiements.updated_at', 'coordonnes_commandes.tracker_status')
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
    $clients = Client::all();
    $soldedit = DB::table('cache_tables')

      ->where('id', '=', $id)->get();

    //dd( $soldedit);

    return view('cache-mgmt/editsolde', ['soldedit' => $soldedit, 'clients' => $clients]);
  }

  public function updatesolde(Request $request)
  {

   // dd($request->request);
    if ($request['action'] == 'modifier')
     {


      $montant_avant = Cache_table::where('id',  $request['id_operation'])->value('Montant');
      $montant_apres = $request['montant'];

      // echo $montant_avant .'---' .$montant_apres ;

      $idmax = Cache_table::max('id');
      $solde = Cache_table::where('id',  $idmax)->value('solde');


      $this->validateInput($request);

      $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant_euro', 'montant', 'solde', 'operation'];

      $input = $this->createQueryInput($keys, $request);


      if ($request['operation'] == 'depot') {

        $solde =  $solde - $montant_avant;
        $solde =  $solde + $montant_apres;
      } else {

        $solde =  $solde + $montant_avant;
        $solde =  $solde - $montant_apres;
      }

      $client = DB::table('clients')->where('id', '=', $request['nom_benef'])->value('societe');
      $idmax = Cache_table::max('id');
      DB::table('cache_tables')->where('id', '=', $idmax)->update(['solde' => $solde]);
      DB::table('cache_tables')->where('id', '=', $request['id_operation'])->update(['id_client' => $request['nom_benef'], 'nom_benef' => $client, 'phone_benef' => $request['phone_benef'], 'montant_euro' => $request['montant_euro'], 'montant' => $montant_apres]);

    //$this->soldeclientupdate($request);

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
      }
       else 
       
       {
        $this->soldeclientupdate($request);
        $solde =  $solde + $request['montant'];
      }
      DB::table('cache_tables')->where('id', '=', $request['id_operation'])->update(['deleted_at' => date("Y-n-j")]);


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
    $clients = Client::all();
    return view('cache-mgmt/create', ['clients' => $clients]);
  }



  public function retraitcash()
  {


    $clients = Client::all();
    return view('cache-mgmt/retrait', ['clients' => $clients]);
  }



  public function addcashstore(Request $request)
  {

    $idmax = Cache_table::max('id');
    $solde = Cache_table::where('id',  $idmax)->value('solde');


    $this->validateInput($request);
    $code_confirmation = str_shuffle(hexdec(uniqid()));

    $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant_euro',  'montant', 'solde', 'operation'];

    $input = $this->createQueryInput($keys, $request);
    // Not implement yet
    $input['code_confirmation'] =  $code_confirmation;


    if ($request['operation'] == 'depot') {

      $input['solde_avant'] = $solde;
      $input['solde'] =  $solde + $request['montant'];
      $input['solde_apres'] = $solde + $request['montant'];
    } elseif ($request['operation'] == 'retrait') {
      $input['solde_avant'] = $solde;
      $input['solde'] =  $solde - $request['montant'];
      $input['solde_apres'] = $solde - $request['montant'];;
    }

    $societe = DB::table('clients')->where('id', '=', $request['nom_benef'])->value('societe');
    $input['nom_benef'] = $societe;
    $input['id_client'] = $request['nom_benef'];

    $this->soldeclient($request);
    Cache_table::create($input);



    return redirect()->intended('/cache-management');
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


        $idmax = Cache_table::max('id');
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
        'type_client' => $request['type_client']
      ];

      $cashs = $this->getCashs($constraints);
      $clients = DB::table('clients')->get();

      return view('cache-mgmt/index', [
        'caches' => $cashs,
        'solde' => $solde,
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
      ->where('id_client', 'like', '%' . $constraints['type_client'] . '%')
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


  private function add_solde($somme)
  {

    $idmax = Cache_table::max('id');
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

    $idmax = Cache_table::max('id');
    $solde = Cache_table::select('solde', 'montant', 'operation')->where('id',  $idmax)->get();
    //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));

    return $solde;
  }
}
