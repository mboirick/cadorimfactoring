<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParrainageController extends Controller
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
     $abonnes = DB::table('abonnes as A')   
        ->leftJoin('abonnes as B', 'A.id', '=', 'B.id_parrain')
		    ->select(array(
						DB::Raw('count(B.id_parrain) AS number'),
						DB::Raw('A.id'),
						DB::Raw('A.prenom'),
						DB::Raw('A.email'),
						DB::Raw('A.username'),
						DB::Raw('A.pays_residence'),
						DB::Raw('A.id_parrain'),
						)
					)
        ->groupBy('A.id')
        ->groupBy('A.prenom')
        ->groupBy('A.email')
        ->groupBy('A.username')
        ->groupBy('A.pays_residence')
        ->groupBy('A.id_parrain')
        ->orderBy('number', 'DESC')
        ->paginate(20);

    return view('parrainage-mgmt/index', [
      'abonnes' => $abonnes
    ]);
  }

  public function search(Request $request)
  {
  	$abonnes = array();
  	$params = array();
  	if($request['search']=='recherche'){

		$nombre = empty($request['nombre'])?0:$request['nombre'];
  		$params  = array(
  							'nom' => isset($request['nom'])?$request['nom']:'', 
  							'email' => isset($request['email'])?$request['email']:'', 
  							'nombre' => isset($request['nombre'])?$request['nombre']:'', 
  						);
  		
  		
  		$abonnes = DB::table('abonnes as A')   
	      ->leftJoin('abonnes as B', 'A.id', '=', 'B.id_parrain')
			  ->select(array(
							DB::Raw('count(B.id_parrain) AS number'),
							DB::Raw('A.id'),
							DB::Raw('A.prenom'),
							DB::Raw('A.email'),
							DB::Raw('A.username'),
							DB::Raw('A.pays_residence'),
							DB::Raw('A.id_parrain'),
							)
						)
		 	->where('A.username', 'like', '%' . $request['nom'] . '%')
	      	->where('A.email', 'like', '%' . $request['email'] . '%')
	        ->groupBy('A.id')
	        ->groupBy('A.prenom')
	        ->groupBy('A.email')
	        ->groupBy('A.username')
	        ->groupBy('A.pays_residence')
	        ->groupBy('A.id_parrain')
	        ->havingRaw('count(B.id_parrain) >= ?',  [intval ($nombre)])
	        ->orderBy('number', 'DESC')
	        ->paginate(20);
	}
  	

    return view('parrainage-mgmt/index', [
      'abonnes' => $abonnes, 'params' => $params
    ]);
  }
*/
  /*public function formulairecourriel($id)
  {
  	$user = array();
  	if(!empty($id)){
  		$user = DB::table('abonnes')

      ->where('id', '=', $id)->get();
  	}
  	

    return view('parrainage-mgmt/formulairecourriel', [
      'user' => $user,
    ]);
  }

  public function formulaireplanifierenvoiecourriel(Request $request)
  {
    $confirmation = $request->confirmation;
    if($request->add == 'add'){
      
      DB::table('task_sendmailparrainage')->insert(
        ['sendall' => $request->all, 'datestart' => $request->startdate, 'dateend' => $request->enddate,'numbre' => $request->numbre]
      );

      return redirect()->intended('/parrainage-management/formulaire-planifier-envoie-courriel?confirmation=ok');
    }

    return view('parrainage-mgmt/formulaireplanifierenvoiecourriel', [
      'confirmation' => $confirmation,
    ]);
  }*/
}
