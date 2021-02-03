<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Employee;
use App\City;
use App\State;
use App\Cache_table;
use App\Transfert_table;
use App\Agence;
use Auth;


class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
  {

    if(Auth::user()->user_type =='operateur') {
      return redirect()->intended('/cashout-management');
     }
    if(Auth::user()->user_type =='cash') {
      return redirect()->intended('/cache-management');
     }
     if(Auth::user()->user_type =='marketing') {
      return redirect()->intended('/sondage-management/survey');
     }
     if(Auth::user()->user_type =='client') {
      return redirect()->intended('/abonnes-management');
     }

     if(Auth::user()->user_type =='gaza') {
      return redirect()->intended('/cashout-gaza');
     }
     
     
    $constraints = [
      'from' => date("Y/n/j", strtotime("- 30 day")),
      'to' => date("Y/n/j ")
      
    ];

    $abonnes = DB::table('abonnes')->count();
    $abonnesmois = $this->abonnesparmois();

    $caches = DB::table('cache_tables')

      ->select('cache_tables.*')
      ->orderBy('created_at', 'DESC')
      ->limit(1)
      ->paginate(20);

    $soldecashout = $this->soldecashout();
    $solde = $this->solde();
    $virementEUR = $this->soldevirementEUR();
    $virementUSD = $this->soldevirementUSD();


    //dd($solde);

    return view('dashboard', [
      'caches' => $caches,
      'solde' => $solde,
      'abonnes' => $abonnes,
      'abonnesmois' => $abonnesmois,
      'soldecashout' => $soldecashout,
      'virementEUR' => $virementEUR,
      'virementUSD' => $virementUSD,
      'searchingVals' => $constraints

    ]);
  }




  private function abonnesparmois()
  {

    $abonnesmois = DB::table('abonnes')
      ->whereMonth('confirmed_at', '=', date('m'))->whereYear('confirmed_at', '=', date('Y'))->count();

    return $abonnesmois;
  }


  private function soldecashout()
  {

    $cashout = DB::table('coordonnes_commandes')

      ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')
      ->where('coordonnes_commandes.tracker_status', '=', 'attente')
      ->Where('paiements.payment_status', 'like', '%omplet%')

      ->sum('paiements.somme_mru');



    //dd($cashout);
    //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

    return $cashout;
  }



 private function soldevirementEUR()
  {

    $virementEUR = DB::table('paiements')
      ->select('payment_amount')
      ->where('payment_type', '=', 'Virement')
      ->where('payment_status', '=', 'En attente')
      ->where('payment_currency', '=', 'EUR')
      ->sum('payment_amount');
    return $virementEUR;
  }
  private function soldevirementUSD()
  {

    $virementUSD = DB::table('paiements')
      ->select('payment_amount')
      ->where('payment_type', '=', 'Virement')
      ->where('payment_status', '=', 'En attente')
      ->where('payment_currency', '=', 'USD')
      ->sum('payment_amount');
    return $virementUSD;
  }

  private function solde()
  {

   if( Auth::user()->user_type =='operateur') 
   {    
    $solde = Agence::where('id_client',  Auth::user()->id_client )->where('indice', '=', '1')-> latest()->value('solde_mru');
   }
   else
   {
    $solde = Cache_table::where('id_client', '!=', '99')-> latest()->value('solde');
   }
 
    

    return $solde;
  }

  private function fraisGhaza($somme)
  {

    //$Ghaza = Division::where('somme_min','<=',$somme) -> where('somme_max','>=' ,$somme)-> value('taux');


    return 0;
  }
 
}
