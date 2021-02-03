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
use App\Agence;
use Session;
use App\Document;
use App\Cache_table;
use Excel;
use Auth;
use PDF;



class CashoutController extends Controller
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
      'expediteur' => '',
      'beneficiaire' => '',
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ", strtotime(" 1 day"))

    ];

    if (Auth::user()->user_type == 'operateur') {

      $soldedispo = Agence::where('id_client',  Auth::user()->id_client)->where('indice', '=', '1')->latest()->value('solde_mru');
    } else {
      $soldedispo = Cache_table::where('id_client', '!=', '99')->latest()->value('solde');
    }
    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
      ->select(
       
        'coordonnes_commandes.*',
        'paiements.payment_date',
        'paiements.payment_currency',
        'paiements.somme_mru',
        'paiements.payment_amount',
        'abonnes.id',
        'abonnes.kyc'
      )
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('coordonnes_commandes.tracker_status', '!=', 'retire')

      ->orderBy('paiements.payment_date', 'DESC')

      ->paginate(20);

      //var_dump($cashout->gaza_confirm);die;
    //dd($cashout);
    return view('cashout-mgmt/index', [
      'cashout' => $cashout,
      'soldedispo' => $soldedispo,
      'searchingVals' => $constraints
    ]);
  }


  public function gaza()
  {


    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      
      ->select(
        'coordonnes_commandes.*',
        
        'paiements.payment_date',
        
        'paiements.somme_mru'        
        
      )
      ->Where('point_retrait', 'like', '%5f63cc77d3b00%')
      ->where('tracker_status', '!=', 'retire')  
      

      ->orderBy('coordonnes_commandes.gaza_confirm', 'ASC')

      ->paginate(20);
 
    return view('cashout-mgmt/gaza', [
      'cashout' => $cashout
     
    ]);
    
  }

  public function gazaconfirmation($id){
    

    DB::table('coordonnes_commandes')->where('id_commande', '=', $id)->update(['gaza_confirm' => 1,'updated_at' => Carbon::now() ]);

    return back() ->with('message', 'تم التحويل بنجاح');
   
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

      ->leftJoin('abonnes', 'abonnes.email', '=', 'coordonnes_commandes.mail_exp')
      ->select('coordonnes_commandes.nom_exp', 'coordonnes_commandes.nom_benef', 'coordonnes_commandes.phone_exp', 'coordonnes_commandes.phone_benef', 'paiements.payment_amount', 'coordonnes_commandes.frais_gaza', 'paiements.somme_mru', 'paiements.updated_at', 'coordonnes_commandes.tracker_status')
      ->where('coordonnes_commandes.nom_exp', 'like', '%' . $constraints['expediteur'] . '%')
      ->where('coordonnes_commandes.nom_benef', 'like', '%' . $constraints['beneficiaire'] . '%')
      ->where('coordonnes_commandes.tracker_status', 'like', '%' . $constraints['statut'] . '%')
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('paiements.updated_at', '>=', $constraints['from'])
      ->where('paiements.updated_at', '<=', $constraints['to'])
      ->get()
      ->map(function ($item, $key) {
        return (array) $item;
      })
      ->all();
  }



  private function soldecashout()
  {

    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')

      ->select('paiements.somme_mru')
      ->where('coordonnes_commandes.tracker_status', '=', 'attente')
      ->Where('paiements.payment_status', 'like', '%omplet%')

      ->sum('paiements.somme_mru');



    //dd($cashout);
    //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

    return $cashout;
  }

  public function cashout()
  {

    $constraints = [
      'expediteur' => '',
      'beneficiaire' => '',
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ")

    ];
    $abonnes = DB::table('abonnes')->count();
    $abonnesmois = $this->abonnesparmois();
    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
      ->select('coordonnes_commandes.*', 'paiements.*', 'commandes.*', 'abonnes.*')
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('coordonnes_commandes.tracker_status', '=', 'attente')

      ->orderBy('paiements.payment_date', 'DESC')

      ->paginate(20);
    //dd($cashout);

    $solde = $this->solde();
    $soldecashout = $this->soldecashout();
    $virementEUR = $this->soldevirementEUR();
    $virementUSD = $this->soldevirementUSD();

    return view('cache-mgmt/cash-out', [
      'cashout' => $cashout,
      'solde' => $solde,
      'abonnes' => $abonnes,
      'abonnesmois' => $abonnesmois,
      'soldecashout'  => $soldecashout,
      'virementEUR' => $virementEUR,
      'virementUSD' => $virementUSD,
      'searchingVals' => $constraints
    ]);
  }




  public function editSolde($id)
  {

    $soldedit = DB::table('cache_tables')

      ->where('id', '=', $id)->get();

    //dd( $soldedit);

    return view('cache-mgmt/editsolde', ['soldedit' => $soldedit]);
  }

  public function updatesolde(Request $request)
  {

    //dd($request);
    if ($request['action'] == 'modifier') {
      // echo $request['operation'];

      $montant_avant = Cache_table::where('id',  $request['id_operation'])->value('Montant');
      $montant_apres = $request['montant'];

      // echo $montant_avant .'---' .$montant_apres ;

      $idmax = Cache_table::max('id');
      $solde = Cache_table::where('id',  $idmax)->value('solde');

      $this->validateInput($request);
      $code_confirmation = str_shuffle(hexdec(uniqid()));

      $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant', 'solde', 'operation'];

      $input = $this->createQueryInput($keys, $request);
      // Not implement yet
      $input['code_confirmation'] =  $code_confirmation;


      if ($request['operation'] == 'depot') {

        $solde =  $solde - $montant_avant;
        $solde =  $solde + $montant_apres;
      } else {

        $solde =  $solde + $montant_avant;
        $solde =  $solde - $montant_apres;
      }
      //DB::table('cache_tables')->where('id', '=', $request['id_operation'])->delete();

      $idmax = Cache_table::max('id');
      DB::table('cache_tables')->where('id', '=', $idmax)->update(['solde' => $solde]);
      DB::table('cache_tables')->where('id', '=', $request['id_operation'])->update(['nom_benef' => $request['nom_benef'], 'phone_benef' => $request['phone_benef'], 'montant' => $montant_apres]);
      //dd($request);
      //Cache_table::create($input);

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
      } else {

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
      $this->mailconfirmation($id, 'virement');
    }


    return redirect()->intended('/cache-management/virement');
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editcashout($id)
  {

    $troisderniermois = [
      'from' => date("Y/n/j", strtotime("- 90 day")),
      'to' => date("Y/n/j ", strtotime(" 1 day"))

    ];

    $editecash = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
      ->select(
       
        'coordonnes_commandes.*',
        'paiements.payment_date',
        'paiements.payment_currency',
        'paiements.somme_mru',
        'paiements.payment_amount',
        'paiements.payment_type',
        'paiements.payment_status',
        'abonnes.*'
      )
      ->where('coordonnes_commandes.id_commande', '=', $id)->get();

    $email = $editecash[0]->email;

    $total = DB::table('paiements')
      ->where('payer_email', '=', $email)
      ->Where('payment_status', 'like', '%omplet%')
      ->sum('payment_amount');

    $transaction = DB::table('paiements')
      ->where('payer_email', '=', $email)
      ->Where('payment_status', 'like', '%omplet%')
      ->count();

    $troismois = DB::table('paiements')
      ->where('payer_email', '=', $email)
      ->Where('payment_status', 'like', '%omplet%')
      ->where('updated_at', '>=', $troisderniermois['from'])
      ->where('updated_at', '<=', $troisderniermois['to'])
      ->sum('payment_amount');

    $nombre_benef = DB::table('coordonnes_commandes')
      ->where('mail_exp', '=', $email)
      ->distinct('phone_benef')
      ->count('phone_benef');

    $id_user = $editecash[0]->id;
    $documents = DB::table('documents')->where('id_user', '=', $id_user)->get();


    return view(
      'cashout-mgmt/edit',
      [
        'editecash' => $editecash,
        'documents' => $documents,
        'total' => $total,
        'troismois' => $troismois,
        'transaction' => $transaction,
        'nombre_benef' => $nombre_benef
      ]
    );
  }



  public function infos(Request $request, $id)
  {
    //dd($request);

    if ($request['operation'] == 'revenu') {

      $this->mailconfirmation($id, 'revenu');
    } elseif ($request['operation'] == 'details') {

      $cashout = DB::table('coordonnes_commandes')
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
        ->select(
          'coordonnes_commandes.*',
          'paiements.payment_date',
          'paiements.payment_currency',
          'paiements.somme_mru',
          'paiements.payment_amount'
        )
        ->Where('paiements.payment_status', 'like', '%omplet%')
        ->where('paiements.payer_email', 'like',  $request['email'])

        ->orderBy('paiements.payment_date', 'DESC')

        ->paginate(20);
      //dd($cashout);
      return view('cashout-mgmt/infos', [
        'cashout' => $cashout,

      ]);
    } elseif ($request['operation'] == 'kyc_demande') {

      $this->mailconfirmation($id, 'kyc');
      return redirect()->intended('/cashout-management');
    } elseif ($request['operation'] == 'kyc_verifier') {


      $id = $request['id_user'];

      return redirect()->intended('/abonnes-management/' . $id . '/editabonnes');
    }
  }



  public function update(Request $request, $id)
  {

   
    
    if ($request['operation'] == 'retrait') {


      $etat = Coordonnes_commande::select('tracker_status')->where('id_commande',  $id)->value('tracker_status');

      if ($etat == "attente") {
        $input['frais_gaza'] = $request['frais_gaza'];
        $charge = $request['somme_mru'] + $request['frais_gaza'];

        $input['tracker_status'] = 'retire';
        if ($request['agence_gaza'] == "") {
          $input['agence_gaza'] = 'CADORIM';
        } else {
          $input['agence_gaza'] = $request['agence_gaza'];
        }

        $input['point_retrait'] = $request['transfert_vers'];


        $this->soldeupdate($request, $id);

        Coordonnes_commande::where('id_commande', $id)
          ->update($input);

        $this->mailconfirmation($id, 'retrait');
      } elseif ($etat == "transfert") {
        $input['tracker_status'] = 'retire';

        Coordonnes_commande::where('id_commande', $id)
          ->update($input);
        $this->mailconfirmation($id, 'retrait');
      }
    } elseif ($request['operation'] == 'transfert') {

      $etat = Coordonnes_commande::select('tracker_status')->where('id_commande',  $id)->value('tracker_status');

      if ($etat == "attente") {
        $input['frais_gaza'] = $request['frais_gaza'];

        $input['tracker_status'] = 'transfert';

        if ($request['agence_gaza'] == "") {
          $input['agence_gaza'] = 'CADORIM';
        } else {
          $input['agence_gaza'] = $request['agence_gaza'];
        }
        $input['point_retrait'] = $request['transfert_vers'];
        $this->soldeupdate($request, $id);

        Coordonnes_commande::where('id_commande', $id)
          ->update($input);
      }
    } elseif ($request['operation'] == 'email') {
      ///echo "ici email";
      $this->mailconfirmation($id, 'injoignable');
    }

    Session::flash('message', ' Modification effectuée avec success!  تم التعديل بنجاح!');
    return redirect()->intended('/cashout-management');
  }



  public function destroy($id)
  {
    Paiement::where('id_commande', $id)->delete();
    Coordonnes_commande::where('id_commande', $id)->delete();
    Commande::where('id_commande', $id)->delete();
    return redirect()->intended('/cache-management/virement');
  }



  public function searchCashOut(Request $request)
  {

    if ($request['search'] == 'excel') {

      $this->prepareExportingData_cashout($request)->export('xlsx');
      redirect()->intended('cashout-mgmt/index');
    } else {

      $constraints = [
        'expediteur' => $request['expediteur'],
        'beneficiaire' => $request['beneficiaire'],
        'from' => $request['from'],
        'to' => $request['to'],
        'statut' => $request['statut']
      ];

      if (Auth::user()->user_type == 'operateur') {

        $soldedispo = Agence::where('id_client',  Auth::user()->id_client)->where('indice', '=', '1')->latest()->value('solde_mru');
      } else {
        $soldedispo = Cache_table::where('id_client', '!=', '99')->latest()->value('solde');
      }
      $cashout = $this->getCashsOut($constraints);

      //dd($cashs);
      return view('cashout-mgmt/index', [
        'cashout' => $cashout,
        'soldedispo' => $soldedispo,
        'searchingVals' => $constraints


      ]);
    }
  }


  private function getCashsOut($constraints)
  {


    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->leftJoin('abonnes', 'coordonnes_commandes.mail_exp', '=', 'abonnes.email')
      ->select(
        
        'coordonnes_commandes.*',
        'paiements.payment_date',
        'paiements.payment_currency',
        'paiements.somme_mru',
        'paiements.payment_amount',
        'abonnes.id',
        'abonnes.kyc'
      )

      ->where(function ($query) use ($constraints) {
        $query->orwhere('coordonnes_commandes.phone_exp', 'like', '%' . $constraints['expediteur'] . '%')
          ->orWhere('coordonnes_commandes.mail_exp', 'like', '%' . $constraints['expediteur'] . '%')
          ->orWhere('coordonnes_commandes.nom_exp', 'like', '%' . $constraints['expediteur'] . '%');
      })
      ->where(function ($query) use ($constraints) {
        $query->orwhere('coordonnes_commandes.nom_benef', 'like', '%' . $constraints['beneficiaire'] . '%')
          ->orWhere('coordonnes_commandes.phone_benef', 'like', '%' . $constraints['beneficiaire'] . '%');
      })

      ->where('coordonnes_commandes.tracker_status', 'like', '%' . $constraints['statut'] . '%')
      ->Where('paiements.payment_status', 'like', '%omplet%')
      ->where('coordonnes_commandes.date_commande', '>=', $constraints['from'])
      ->where('coordonnes_commandes.date_commande', '<=', $constraints['to'])
      ->orderBy('coordonnes_commandes.date_commande', 'DESC')
      ->paginate(20);
    //dd($cashout);
    //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

    return $cashout;
  }


  private function soldeupdate($request, $id)
  {

    if ($request['transfert_vers'] == 'cadorim') {

      $row =  Cache_table::where('code_confirmation', $id)->value('code_confirmation');

      $id_client = Auth::user()->id_client;

      $agence_solde =  Agence::where('id_client', $id_client)->where('indice', '=', '1')->latest()->first();

      if ($agence_solde) {

        $charge = $request['somme_mru'] + $request['frais_gaza'];
        // if ($agence_solde->solde_mru > $charge)
        if (1) {
          $update_solde = $agence_solde->solde_mru - $charge;

          DB::table('agences')->where('id_client', '=', $id_client)->where('indice', '=', '1')->update(['indice' => 0]);
          Agence::create([
            "id_client" => $id_client,
            "id_client_debiteur" => 'ID Commande :' . $id,
            "solde_avant_mru" => $agence_solde->solde_mru,
            "solde_mru" => $update_solde,
            "montant_mru" => $charge,
            "motif" => 'Transfert d\'argent ',
            "type_opperation" => 'retrait'

          ]);

          $idmax = Cache_table::max('id');
          $solde = Cache_table::select('*')->where('id',  $idmax)->first();
          $charge = $request['somme_mru'] + $request['frais_gaza'];

          //$solde = $solde - $charge;
          $input['id_client'] = 99;
          $input['nom_benef'] = 'Transfert: (' . $request['email_exp'] . ' )';
          $input['expediteur'] = Auth::user()->email;
          $input['montant_euro'] = $request['somme_eur'];
          $input['montant'] = $request['somme_mru'];
          $input['solde_avant'] = $agence_solde->solde_mru;
          $input['phone_benef'] = 'ID COMMANDE = ' . $id;
          $input['code_confirmation'] = $id;
          $input['operation'] = 'retrait';
          $input['solde'] =  $update_solde;
          $input['solde_apres'] = $update_solde;
          Cache_table::create($input);
        } else {

          dd("SOLDE INSIFUSANT");
        }
      } else {


        if ($row) {
        } else {
          $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
          $solde = Cache_table::where('id',  $idmax)->value('solde');
          $charge = $request['somme_mru'] + $request['frais_gaza'];

          //$solde = $solde - $charge;
          $input['id_client'] = 999;
          $input['nom_benef'] = 'Transfert: (' . $request['email_exp'] . ' )';
          $input['expediteur'] = Auth::user()->email;
          $input['montant_euro'] = $request['somme_eur'];
          $input['montant'] = $request['somme_mru'];
          $input['solde_avant'] = $solde;
          $input['phone_benef'] = 'ID COMMANDE = ' . $id;
          $input['code_confirmation'] = $id;
          $input['operation'] = 'retrait';
          $input['solde'] =  $solde - $charge;
          $input['solde_apres'] = $solde -  $charge;
          Cache_table::create($input);
        }
      }
    } else {

      $row =  Cache_table::where('code_confirmation', $id)->value('code_confirmation');

      $id_client = $request['transfert_vers'];

      $agence_solde =  Agence::where('id_client', $id_client)->where('indice', '=', '1')->latest()->first();

      if ($agence_solde) {

        $charge = $request['somme_mru'] + $request['frais_gaza'];
        // if ($agence_solde->solde_mru > $charge)
        if (1) {
          $update_solde = $agence_solde->solde_mru - $charge;

          DB::table('agences')->where('id_client', '=', $id_client)->where('indice', '=', '1')->update(['indice' => 0]);
          Agence::create([
            "id_client" => $id_client,
            "id_client_debiteur" => 'ID Commande :' . $id,
            "solde_avant_mru" => $agence_solde->solde_mru,
            "solde_mru" => $update_solde,
            "montant_mru" => $charge,
            "motif" => 'Transfert d\'argent ',
            "type_opperation" => 'retrait'

          ]);

          $idmax = Cache_table::max('id');
          $solde = Cache_table::select('*')->where('id',  $idmax)->first();
          $charge = $request['somme_mru'] + $request['frais_gaza'];

          //$solde = $solde - $charge;
          $input['id_client'] = 99;
          $input['nom_benef'] = 'Transfert: (' . $request['email_exp'] . ' )';
          $input['expediteur'] = Auth::user()->email;
          $input['montant_euro'] = $request['somme_eur'];
          $input['montant'] = $request['somme_mru'];
          $input['solde_avant'] = $agence_solde->solde_mru;
          $input['phone_benef'] = 'ID COMMANDE = ' . $id;
          $input['code_confirmation'] = $id;
          $input['operation'] = 'retrait';
          $input['solde'] =  $update_solde;
          $input['solde_apres'] = $update_solde;
          Cache_table::create($input);
        } else {

          dd("SOLDE INSIFUSANT");
        }
      } else {


        if ($row) {
        } else {
          $idmax = Cache_table::where('id_client', '!=', '99')->max('id');
          $solde = Cache_table::where('id',  $idmax)->value('solde');
          $charge = $request['somme_mru'] + $request['frais_gaza'];

          //$solde = $solde - $charge;
          $input['id_client'] = 999;
          $input['nom_benef'] = 'Transfert: (' . $request['email_exp'] . ' )';
          $input['expediteur'] = Auth::user()->email;
          $input['montant_euro'] = $request['somme_eur'];
          $input['montant'] = $request['somme_mru'];
          $input['solde_avant'] = $solde;
          $input['phone_benef'] = 'ID COMMANDE = ' . $id;
          $input['code_confirmation'] = $id;
          $input['operation'] = 'retrait';
          $input['solde'] =  $solde - $charge;
          $input['solde_apres'] = $solde -  $charge;
          Cache_table::create($input);
        }
      }
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



  public function correction()
  {


    $dat = Cache_table::where('statut',  '1')->value('created_at');



    $data = DB::table('cache_tables')
      ->leftJoin('coordonnes_commandes', 'coordonnes_commandes.id_commande', '=', 'cache_tables.code_confirmation')

      ->select('coordonnes_commandes.frais_gaza', 'cache_tables.*')
      ->Where('created_at', '>=', $dat)
      ->Where('expediteur', 'LIKE', 'naji%')
      ->orWhere('expediteur', 'LIKE', 'admin%')
      ->orderBy('created_at')
      ->get();

    foreach ($data as $infos) {

      $charge = $infos->montant + $infos->frais_gaza;

      $solde_avant = Cache_table::where('statut',  '1')->latest()->value('solde');

      if ($infos->operation == 'depot') {
        $var =  $solde_avant + $charge;

        Cache_table::where('id',  $infos->id)->update(['statut' => 1]);
        //echo   $solde_avant.'__'.$charge.'___'.$var.'__id_'.$infos->id.'</br>';
        if ($infos->id != 5174)
          Cache_table::where('id',  $infos->id)->update(['solde_avant' => $solde_avant, 'solde_apres' => $var, 'solde' => $var]);
      } else {
        $var =  $solde_avant - $charge;
        Cache_table::where('id',  $infos->id)->update(['statut' => 1]);
        //echo   $solde_avant.'__'.$charge.'___'.$var.'__id_'.$infos->id.'</br>';
        if ($infos->id != 5174)
          Cache_table::where('id',  $infos->id)->update(['solde_avant' => $solde_avant, 'solde_apres' => $var, 'solde' => $var]);
      }

      //echo $charge.'*****'.$infos->created_at.'</br>';
    }


    die;
  }








  private function mailconfirmation($idcommande, $type)
  {




    $tracker = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')

      ->select('coordonnes_commandes.*', 'paiements.*')
      ->where('coordonnes_commandes.id_commande', '=', $idcommande)->get();



    $reqpanier = DB::table('commandes')
      ->where('id_commande', '=', $idcommande)->get();

    $panier = "";

    foreach ($reqpanier as $line) {

      $panier .= ' <tr>
      <td><div align="left">' . $line->nom_produit . '</div></td>
      <td><div align="left">' . $line->quantite . '</div></td>
      <td><div align="left">' . $line->prix_produit . '</div></td>
    </tr>';
    }





    $to = $tracker[0]->mail_exp; // Email de l'expediteur.
    //$to = 'mboirick@yahoo.fr';
    $iduser = DB::table('abonnes')->where('email', '=', $to)->value('id');

    if ($type == 'retrait') {

      $email_subject = "CADORIM : Retrait";
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
    
    <p align="left"> Bonjour ' . $tracker[0]->nom_exp . ',  </p>
    <p align="left">
    Votre destinataire a retiré l\'argent que vous avez envoyé. Les détails de votre commande figurent ci-dessous. Pour toute question, n\'hésitez pas à nous contacter à tout moment.    </p>
    <p align="left"><br>
    </p></td>
  </tr>
</table>

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
    

  
  <br />
    
  <p>Merci d\'avoir utilisé CADORIM.    </p>
  </td>
</tr>
</tbody>
<tr><td>
<table width="100%" height="40" border="0" style="background-color:#212529">
  <tr>
    <td  align="center"><a href="https://www.facebook.com/cradmin1/ ">
                </a><a href="https://twitter.com/Cadorim2"><img src="https://cadorim.com/img/logos/rs_twit.PNG"></a><a href="https://www.facebook.com/cradmin1/ "><img src="https://cadorim.com/img/logos/rs_fb.PNG">
                </a><a href="https://www.linkedin.com/company/35526670/admin/"><img src="https://cadorim.com/img/logos/rs_in.PNG"></a></td>
  </tr>
</table></td></tr>
</table>
</div>

';
    } elseif ($type == 'virement') {

      $email_subject = "CADORIM : Virement reçu ";

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
    
    <p align="left"> Bonjour Mrs, Mme :' . $tracker[0]->nom_exp . '   </p>
    <p align="left">Nous vous confirmons la récéption de votre virement pour la commande ci dessous, Votre numéro de suivi  est : <strong>' . $idcommande . '</strong>.
      Veuillez communiquer ce numéro au beneficiaire pour  le retrait.<br>
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

  <div>
    <div align="left"><strong> Paiement status : ' . $tracker[0]->payment_status . ' </strong></div>
  </div>
  
  <br />
    
  <p>L\'equipe CADORIM vous remerci pour votre commande, esperant vous revoir prochainement pour un nouvel envoi. </p>
  
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
          
          <p align="left"> Bonjour ' . $tracker[0]->nom_exp . ',  </p>
          <p align="left">
          Merci d’avoir utilisé notre service pour le transfert numéro ' . $idcommande  . ', on vous prie de bien vouloir nous envoyer le plutôt 
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
    } elseif ($type == 'revenu') {

      $email_subject = "CADORIM: Justificatif de revenus";

      $email_body = '
  
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
          
          <p align="left"> Bonjour ' . $tracker[0]->nom_exp . ',  </p>
          <p align="left">
          Merci d’avoir utilisé notre service pour le transfert numéro ' . $idcommande  . ', on vous prie de bien vouloir nous envoyer le plutôt 
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
}
