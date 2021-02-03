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
        $abonnes= DB::table('abonnes')->count();
        $abonnesmois =$this->abonnesparmois();
        
        $caches = DB::table('cache_tables')
        
        ->select('cache_tables.*')
        ->orderBy('created_at','DESC')
        ->paginate(20);

        $soldecashout=$this->soldecashout();
        $solde=$this->solde();
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        

       //dd($solde);

        return view('cache-mgmt/index', ['caches' => $caches,
         'solde' => $solde, 
         'abonnes' => $abonnes, 
         'abonnesmois' => $abonnesmois,
          'soldecashout' => $soldecashout,
          'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
          
          ]);
    }

    public function cashin()
    {  
        $abonnes= DB::table('abonnes')->count();
        $abonnesmois =$this->abonnesparmois();
        $divisions = Division::paginate(5);
        $solde=$this->solde();
        $soldecashout=$this->soldecashout();
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        

        return view('cache-mgmt/cash-in', ['divisions' => $divisions,  'solde' => $solde,'abonnes' => $abonnes, 'abonnesmois' => $abonnesmois, 'soldecashout'  => $soldecashout]);
    }

    
    public function cashout()
    {

        $abonnes= DB::table('abonnes')->count();     
        $abonnesmois =$this->abonnesparmois();
        $cashout = DB::table('coordonnes_commandes')
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    
        ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
        ->select('coordonnes_commandes.*','paiements.*','commandes.*')
        ->where (function ($query) {
          $query->where('paiements.payment_type', 'like', '%cart%')
                ->Where('paiements.payment_status','like','%omplet%');
          }) 
         ->where('coordonnes_commandes.tracker_status','=','attente')
        ->whereDate('commandes.date_commande', '>', Carbon::now()->subDays(7))
        ->orderBy('paiements.payment_date','DESC')

        ->paginate(20);
        //dd($cashout);

        $solde=$this->solde();
        $soldecashout=$this->soldecashout();
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD();
     
        return view('cache-mgmt/cash-out', ['cashout' => $cashout, 
        'solde' => $solde,
        'abonnes' => $abonnes, 
        'abonnesmois' => $abonnesmois,
        'soldecashout'  => $soldecashout,
        'virementEUR'=> $virementEUR , 
        'virementUSD'=> $virementUSD
         ]);
    }


    public function editcashout($id)
    {
        $division = Division::find($id);
        // Redirect to division list if updating division wasn't existed
        if ($division == null || empty($division)) {
            return redirect()->intended('/system-management/division');
        }

        return view('system-mgmt/division/edit', ['division' => $division]);
    }



    public function virement(){

        $abonnes= DB::table('abonnes')->count();     
       
        $cashout = DB::table('coordonnes_commandes') 
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    
        ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
        ->select('coordonnes_commandes.*','paiements.*','commandes.*')
        ->where('paiements.payment_type', '=', 'Virement')
        ->where('paiements.payment_status', 'like', '%attent%')
        ->orderBy('commandes.date_commande','DESC')
        ->paginate(20);
        $solde=$this->solde();
        $soldecashout=$this->soldecashout();
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        $abonnesmois =$this->abonnesparmois();
     
        return view('cache-mgmt/virement', ['cashout' => $cashout,
         'solde' => $solde,'abonnes' => $abonnes, 
         'abonnesmois' => $abonnesmois,
         'soldecashout'  => $soldecashout,
          'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
          
          ]);
    }
    
    public function abonnes()
    {

        $abonnesmois =$this->abonnesparmois();
        $abonnes = DB::table('abonnes')

        //->leftJoin('department', 'employees.department_id', '=', 'department.id')
        
        //->leftJoin('division', 'employees.division_id', '=', 'division.id')
        //->select('employees.*', 'department.name as department_name', 'department.id as department_id', 'division.name as division_name', 'division.id as division_id')
        ->select('abonnes.*')
        ->orderBy('confirmed_at','DESC')
        ->paginate(20);
        $nombre= DB::table('abonnes')->count();
        $solde=$this->solde();
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD();
        $soldecashout=$this->soldecashout();
        return view('cache-mgmt/abonnes', ['abonnes' => $abonnes,
         'abonnesmois' => $abonnesmois, 
         'solde' => $solde,'nombre' => $nombre, 
         'soldecashout'  => $soldecashout,
         'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
         
         
         
         ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('cache-mgmt/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function retrait()
    {    
 
        
        return view('cache-mgmt/retrait');
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
        $code_confirmation=str_shuffle(hexdec(uniqid()));

        $keys = ['expediteur', 'nom_benef', 'phone_benef', 'montant' ,'solde','operation'];
       
        $input = $this->createQueryInput($keys, $request);
        // Not implement yet
        $input['code_confirmation'] =  $code_confirmation;


       if($request['operation']=='depot'){

        $input['solde'] =  $solde + $request['montant'] ;
      
        
       }
       else{
      
      
            
            $input['solde'] =  $solde - $request['montant'] ;
      




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
        
        $datacommande =  Paiement:: select('payment_amount','payment_currency')->  where('id_commande', $id)-> get();
        $Taux_echange  = Taux_echange:: select('taux_euros','taux_dollar') ->get();
        
        $cigle = $datacommande[0]->payment_currency;
        
        if($cigle=='EUR'){
         $sommeMRU= ($datacommande[0]->payment_amount)*($Taux_echange[0]->taux_euros) ;
        }
        else
        {
        $sommeMRU= ($datacommande[0]->payment_amount)*($Taux_echange[0]->taux_dollar) ;
        }
        $input['payment_status']="Complet";
        $input['somme_mru']=$sommeMRU;
        Paiement::where('id_commande', $id)
            ->update($input);

        //envoie de mail de reception virement
        //$this->mailconfirmation($id, 'virement'); 

        return redirect()->intended('/cache-management/virement');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$editecash = Coordonnes_commande::where('id_commande', '=', $id)->get();
        
        $editecash = DB::table('coordonnes_commandes')
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    
        ->select('coordonnes_commandes.*','paiements.*')
        ->where('coordonnes_commandes.id_commande', '=', $id)->get();
        $somme_mru=(float)$editecash[0]->somme_mru;
        $Ghaza= $this->fraisGhaza($somme_mru);
    

      // dd( $Ghaza);
        return view('cache-mgmt/edit', ['editecash' => $editecash,'Ghaza' => $Ghaza]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
      

      if($request['operation']=='retrait')
      {

//echo "ici retait ";
      $etat = Coordonnes_commande::select('tracker_status') -> where('id_commande',  $id)-> value('tracker_status');
     
        if($etat=="attente")
        {
          $input['frais_gaza'] = $request['frais_gaza'];
          $charge = $request['somme_mru'] +$request['frais_gaza'];
               
          $input['tracker_status'] = 'retire';
  
  
          $idmax = Cache_table::max('id');
          $solde = Cache_table::select('solde') -> where('id',  $idmax)-> value('solde');
  
          $solde=$solde -$charge;
          
          $inputsolde['solde'] = $solde;
        
         Coordonnes_commande::where('id_commande', $id)
              ->update($input); 
          Cache_table::where('id', $idmax)
              ->update($inputsolde);
          
              $this->mailconfirmation($id, 'retrait'); 

        }
        $etat = Coordonnes_commande::select('tracker_status') -> where('id_commande',  $id)-> value('tracker_status');
            
       
      }
      elseif($request['operation']=='transfert')
      {
      //echo "ici transfert";
        
      }
      elseif($request['operation']=='email')
      {
      ///echo "ici email";
      $this->mailconfirmation($id, 'injoignable'); 

      }
        
       

        return redirect()->intended('/cache-management/cashout');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Paiement::where('id_commande', $id)->delete();
         Coordonnes_commande::where('id_commande', $id)->delete();
         Commande::where('id_commande', $id)->delete();
         return redirect()->intended('/cache-management/virement');
    }

    /**
     * Search state from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */

    public function search(Request $request) {
        $abonnes= DB::table('abonnes')->count();

        $constraints = [
            'from' => $request['from'],
            'to' => $request['to'],
            'type' => $request['type']
        ];
        
        $soldecashout=$this->soldecashout();
        $solde=$this->solde();
        $cashs = $this->getCashs($constraints);
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        $abonnesmois =$this->abonnesparmois();

        return view('cache-mgmt/index', ['caches' => $cashs, 
        'searchingVals' => $constraints,
         'solde' => $solde,'abonnes' => $abonnes , 
         'soldecashout' => $soldecashout,
         'abonnesmois' => $abonnesmois,
         'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
         
         
         ]);
    }

    
    
    
    private function getCashs($constraints) {
        $cashs = Cache_table::where('created_at', '>=', $constraints['from'])
                        ->where('created_at', '<=', $constraints['to'])
                        ->where('operation', 'like', '%'.$constraints['type'].'%')
                        ->orderBy('created_at','DESC')
                        ->paginate(20);
        return $cashs;
    }


    public function searchCashOut(Request $request) {
        
        $constraints = [
            'expediteur' => $request['expediteur'],
            'beneficiaire' => $request['beneficiaire'],
            'statut' => $request['statut']
        ];
        $abonnes= DB::table('abonnes')->count();
        $solde=$this->solde();
        $soldecashout=$this->soldecashout();
        $cashout = $this->getCashsOut($constraints);
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        $abonnesmois =$this->abonnesparmois();
        //dd($cashs);
        return view('cache-mgmt/cash-out', ['cashout' => $cashout, 
        'searchingVals' => $constraints,
        'solde' => $solde,
        'abonnes' => $abonnes ,
        'soldecashout' => $soldecashout,
        'abonnesmois' => $abonnesmois,
         'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
        
        ]);
        
    }
   

    private function getCashsOut($constraints) {

        $cashout = DB::table('coordonnes_commandes')
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    
        ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
        ->select('coordonnes_commandes.*','paiements.*','commandes.*')
        //->where('coordonnes_commandes.nom_exp', 'like', '%'.$constraints['expediteur'].'%')
        ->where('coordonnes_commandes.phone_exp', 'like', '%'.$constraints['expediteur'].'%')
        //->where('coordonnes_commandes.nom_benef', 'like', '%'.$constraints['beneficiaire'].'%')
        ->where('coordonnes_commandes.phone_benef', 'like', '%'.$constraints['beneficiaire'].'%')
        ->where('coordonnes_commandes.tracker_status', 'like', '%'.$constraints['statut'].'%')
        ->where (function ($query) {
          $query->where('paiements.payment_type', 'like', '%cart%')
                ->Where('paiements.payment_status','like','%omplet%');
          })
        ->whereDate('commandes.date_commande', '>', Carbon::now()->subDays(7))
        ->orderBy('commandes.date_commande','DESC')
        ->paginate(20);
        //dd($cashout);
        //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

        return $cashout;
    }


    public function searchabonnes(Request $request) {
        
        $constraints = [
            'username' => $request['nom'],
            'prenom' => $request['prenom'],
            'phone' => $request['telephone']
        ];
        //dd( $request);
        $nombre= DB::table('abonnes')->count();
        $solde=$this->solde();
        $soldecashout=$this->soldecashout();
        $abonnes = $this->getabonnes($constraints);
        $virementEUR=$this->soldevirementEUR(); 
        $virementUSD=$this->soldevirementUSD(); 
        $abonnesmois =$this->abonnesparmois();
        //dd($cashs);
        return view('cache-mgmt/abonnes', ['abonnes' => $abonnes,
         'abonnesmois' => $abonnesmois,
         'solde' => $solde,
         'nombre' => $nombre, 
         'soldecashout'  => $soldecashout,
         'virementEUR'=> $virementEUR , 
          'virementUSD'=> $virementUSD
         
         ]);
        
    }
   

    private function getabonnes($constraints) {

        $abonnes = DB::table('abonnes')
        ->where('username', 'like', '%'.$constraints['username'].'%')
        ->where('prenom', 'like', '%'.$constraints['prenom'].'%')
        ->where('phone', 'like', '%'.$constraints['phone'].'%')
        
        ->orderBy('confirmed_at','DESC')
        ->paginate(20);
        //dd($cashout);
        //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

        return $abonnes;
    }




    private function doSearchingQuery($constraints)   {
        $query = DB::table('cache_tables')
    
        ->select('cache_tables.*');
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where($fields[$index], 'like', '%'.$constraint.'%');
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
    public function load($name) {
         $path = storage_path().'/app/avatars/'.$name;
        if (file_exists($path)) {
            return Response::download($path);
        }
    }

    private function validateInput($request) {
        $this->validate($request, [

            'expediteur' => 'required',
            'nom_benef' => 'required',
            
            'montant' => 'required|numeric|min:0|not_in:0',          

        ]);
    }

    private function createQueryInput($keys, $request) {
        $queryInput = [];
        for($i = 0; $i < sizeof($keys); $i++) {
            $key = $keys[$i];
            $queryInput[$key] = $request[$key];
        }

        return $queryInput;
    }

    private function solde(){

        $idmax = Cache_table::max('id');
        $solde = Cache_table::select('solde', 'montant','operation') -> where('id',  $idmax)-> get();
        //$solde=strrev(wordwrap(strrev($solde), 3, ' ', true));
        
        return $solde;
    }

    private function fraisGhaza($somme){

        //$Ghaza = Division::where('somme_min','<=',$somme) -> where('somme_max','>=' ,$somme)-> value('taux');

        
        return 0;
    }



    private function soldecashout() {

        $cashout = DB::table('coordonnes_commandes')
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    
        ->leftJoin('commandes', 'paiements.id_commande', '=', 'commandes.id_commande')
        ->select('coordonnes_commandes.*','paiements.*','commandes.*')
        ->where('coordonnes_commandes.tracker_status','=','attente')
        ->where (function ($query) {
          $query->where('paiements.payment_type', 'like', '%cart%')
                ->Where('paiements.payment_status','like','%omplet%');
          })
         
        ->sum('paiements.somme_mru'); 
        
        
        
        //dd($cashout);
        //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

        return $cashout;
    }

    private function soldevirementEUR() {
        
        $virementEUR = DB::table('paiements')
        ->select('payment_amount')
        ->where('payment_type', '=', 'Virement')
        ->where('payment_status', '=', 'En attente')
        ->where('payment_currency', '=', 'EUR')
        ->sum('payment_amount'); 
        return $virementEUR;
    }
    private function soldevirementUSD() {
        
        $virementUSD = DB::table('paiements')
        ->select('payment_amount')
        ->where('payment_type', '=', 'Virement')
        ->where('payment_status', '=', 'En attente')
        ->where('payment_currency', '=', 'USD')
        ->sum('payment_amount'); 
        return $virementUSD;
    }
            
    
    private function abonnesparmois() {

        $abonnesmois = DB::table('abonnes')
       ->whereMonth('confirmed_at', '=', date('m'))->whereYear('confirmed_at', '=', date('Y'))->count(); 
        
        
        
        //dd($cashout);
        //return view('cache-mgmt/cash-out', ['cashout' => $cashout]);

        return $abonnesmois;
    }



    
private function mailconfirmation($idcommande , $type){


    
        
        $tracker = DB::table('coordonnes_commandes')
        
        ->leftJoin('paiements', 'coordonnes_commandes.id_commande', '=', 'paiements.id_commande')    

        ->select('coordonnes_commandes.*','paiements.*')
        ->where('coordonnes_commandes.id_commande','=',$idcommande)->get(); 
        


       $reqpanier = DB::table('commandes')    
      ->where('id_commande','=',$idcommande)->get();

      $panier="";
     
     foreach($reqpanier as $line) { 

  $panier .= ' <tr>
      <td><div align="left">'.$line->nom_produit.'</div></td>
      <td><div align="left">'.$line->quantite.'</div></td>
      <td><div align="left">'.$line->prix_produit.'</div></td>
    </tr>';
                
     }
  




$to = $tracker[0]->mail_exp; // Email de l'expediteur.
//$to = 'mboirick@yahoo.fr';


if($type=='retrait'){

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
    
    <p align="left"> Bonjour '.$tracker[0]->nom_exp.',  </p>
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
          <td ><div align="left">'.$tracker[0]->nom_exp.'<br>
          </div></td>
          
          <td ><div align="left">'.$tracker[0]->nom_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td ><div align="left">Tel : '.$tracker[0]->phone_exp.'<br>
          </div></td>
          
          <td ><div align="left">Tel : '.$tracker[0]->phone_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td > <div align="left">Email : '.$tracker[0]->mail_exp.'</div></td>
          
          <td ><div align="left">Adresse : '.$tracker[0]->adress_benef.'<br>
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
  '.$panier .'
  
    <tr> 
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px"><div align="left">Frais d\'envoi : 0 ' .$tracker[0]->payment_currency.'</div></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ><div align="left"><strong>Total : '.$tracker[0]->payment_amount.' '.$tracker[0]->payment_currency.'</strong></div></td>
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

}
elseif ($type=='virement')
{

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
    
    <p align="left"> Bonjour Mrs, Mme :'.$tracker[0]->nom_exp.'   </p>
    <p align="left">Nous vous confirmons la récéption de votre virement pour la commande ci dessous, Votre numéro de suivi  est : <strong>'.$idcommande.'</strong>.
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
          <td ><div align="left">'.$tracker[0]->nom_exp.'<br>
          </div></td>
          
          <td ><div align="left">'.$tracker[0]->nom_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td ><div align="left">Tel : '.$tracker[0]->phone_exp.'<br>
          </div></td>
          
          <td ><div align="left">Tel : '.$tracker[0]->phone_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td > <div align="left">Email : '.$tracker[0]->mail_exp.'</div></td>
          
          <td ><div align="left">Adresse : '.$tracker[0]->adress_benef.'<br>
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
  
  '.$panier.'
  
    <tr>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px"><div align="left">Frais d\'envoi : 0 ' .$tracker[0]->payment_currency.'</div></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ><div align="left"><strong>Total : '.$tracker[0]->payment_amount.' ' .$tracker[0]->payment_currency.'</strong></div></td>
  </tr>
</table>

  <div>
    <div align="left"><strong> Paiement status : '.$tracker[0]->payment_status.' </strong></div>
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

}
elseif ($type=='injoignable')
{

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
    
    <p align="left"> Bonjour Mme(Mrs) :'.$tracker[0]->nom_exp.'   </p>
    <p align="left">
    
    L\'éuiqpe cadorim a essayé de joindre par téléphone, Mme(Mrs) '.$tracker[0]->nom_benef.' au numéro de téléphone :<strong>'.$tracker[0]->phone_benef.'</strong>. sans succès<br>
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
          <td ><div align="left">'.$tracker[0]->nom_exp.'<br>
          </div></td>
          
          <td ><div align="left">'.$tracker[0]->nom_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td ><div align="left">Tel : '.$tracker[0]->phone_exp.'<br>
          </div></td>
          
          <td ><div align="left">Tel : '.$tracker[0]->phone_benef.'<br>
          </div></td>
        </tr>
        <tr>
          <td > <div align="left">Email : '.$tracker[0]->mail_exp.'</div></td>
          
          <td ><div align="left">Adresse : '.$tracker[0]->adress_benef.'<br>
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
  
  '.$panier.'
  
    <tr>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px"><div align="left">Frais d\'envoi : 0 ' .$tracker[0]->payment_currency.'</div></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ><div align="left"><strong>Total : '.$tracker[0]->payment_amount.' ' .$tracker[0]->payment_currency.'</strong></div></td>
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

}

$headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
$headers .= "Reply-To: noreply@cadorim.com\n";

$headers .= 'Bcc:  cadorimc@gmail.com, bmneine@gmail.com, cadorim.stat@gmail.com' . "\r\n";
//$headers .= 'Bcc:  cadorim.stat@gmail.com' . "\r\n";
$headers .= "Content-Type: text/html; charset=\"utf8\"";



mail($to,$email_subject,$email_body,$headers);

        
    }
}
