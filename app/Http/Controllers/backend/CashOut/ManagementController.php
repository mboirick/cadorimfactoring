<?php

namespace App\Http\Controllers\backend\CashOut;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;
use Auth;
use Excel;
use App\Exports\CashOutExport;
use Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementController extends BaseController
{
	protected function setPermission()
    {
        $this->middleware('permission:admin-Cash-out|cash-out-operator', 
		['only' => ['index', 'search', 'edit', 'detail', 'operator',
					'request', 'operation', 'viewDocument', 'operationConfirmation']]);
		
		$this->middleware('permission:cash-out-operator', 
		['only' => ['operator', 'operationConfirmation']]);
	}
	
    /**
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
  	{
	    $userType 	= Auth::user()->user_type;
	    $soldedispo = $userType == 'operateur'?$this->agencyRepository->getBalanceCurrentUserByIdClientAndIndex(Auth::user()->id_client, '1') : $this->cashRepository->getBalanceLatest();

	  
	  	$cashout = $this->coordinatedOrdersRepository->getOrderByPaymentStatus('omplet', 'retire');

	    return view('backend/cashout/management/index', [
	      'cashout' => $cashout,
	      'soldedispo' => $soldedispo,
	      'searchingVals' => ['expediteur' => '', 'beneficiaire' => '', 'from' => '', 'to' => '']
	    ]);
  	}

  	/**
  	 * 
  	 * @param Request $request
  	 * @return unknown|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
  	 */
  	public function search(Request $request)
  	{
		$userType 	= Auth::user()->user_type;
	    $soldedispo = ($userType == 'operateur')?$this->agencyRepository->getBalanceCurrentUserByIdClientAndIndex(Auth::user()->id_client, '1') : $this->cashRepository->getBalanceLatest();
	    
	    $search = isset($request['search'])?$request['search']:null;
	    
	    $constraints = [
		        'expediteur' => $request['expediteur'],
		        'beneficiaire' => $request['beneficiaire'],
		        'from' => $request['from'],
		        'to' => $request['to'],
		        'statut' => $request['statut']
	      	];

	    if($search == 'excel')
    		return Excel::download(new CashOutExport($constraints), 'cashout.xlsx');

    	$cashout = $this->coordinatedOrdersRepository->getOrderByCriterion($constraints);
	  
	  
	    return view('backend/cashout/management/index', [
	      'cashout' => $cashout,
	      'soldedispo' => $soldedispo,
	      'searchingVals' => $constraints
	    ]);
  	}

  	/**
  	 * 
  	 * @param int $id
  	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
  	 */
  	public function edit($id)
  	{
	    $troisderniermois = [
	      'from' => date("Y/n/j", strtotime("- 90 day")),
	      'to' => date("Y/n/j ", strtotime(" 1 day"))
	    ];

    	$editecash = $this->coordinatedOrdersRepository->getById($id);

    	$email = $editecash[0]->email;

    	$total = $this->paymentsRepository->getSumByEmailAndStatus($email, 'omplet');
    	$transaction = $this->paymentsRepository->getNbrByEmailAndStatus($email, 'omplet');
		$troismois = $this->paymentsRepository->getByUpdateDate($email, 'omplet', $troisderniermois['from'], $troisderniermois['to']);

		$nombre_benef = $this->coordinatedOrdersRepository->getNbreBenefitByEmail($email);
		
    	$idUser 	= $editecash[0]->id;
    	$documents 	= $this->documentsRepository->getByIdUser($idUser);

	    return view(
	      'backend/cashout/management/edit',
	      [
	        'editecash' => $editecash,
	        'documents' => $documents,
	        'total' => $total,
	        'troismois' => $troismois,
	        'displayProofIncome' => $troismois >= 4000 ,
	        'transaction' => $transaction,
	        'nombre_benef' => $nombre_benef
	      ]
	    );
  	}

  	/**
  	 * 
  	 * @param Request $request
  	 * @param int $id
  	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
  	 */
  	public function detail(Request $request, $id)
  	{
		$cashout = $this->coordinatedOrdersRepository->getByEmailAndPaymentStatus($request['email'], 'omplet');
		
      return view('cashout-mgmt/infos', [
        'cashout' => $cashout,

      ]);
   
  	}

    /**
     * 
     * @param string $type
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
  	public function request($type, $id)
  	{
  		$message = 'Il y a un erreur envoie courriel';
  		$tracker = $this->coordinatedOrdersRepository->getByIdOrder($id);
  			
		if(isset($tracker[0]) && !empty($tracker)){
			$idUser = $this->subscribersRepository->getByEmail($tracker[0]->mail_exp);

			switch ($type) {
			  case "income":
				    $params = [
			            'to' => $tracker[0]->mail_exp,
			            //'to' => 'abouhamadi@yahoo.fr',
			            'subject'	=> 'CADORIM: Justificatif de revenus',
			            'title' 	=> 'CADORIM',
			            'firstName'	=> $tracker[0]->nom_exp,
			            'tracker'	=> $tracker[0],
			            'idUser'	=> $idUser,
			            'template'  => 'email.income',
		        	];

		        	$response = $this->send($params);
	        		$message = $response?'Le message a été bien envoyé':$message;

			    break;
			  case "proof":
				    $params = [
			            'to' => $tracker[0]->mail_exp,
			            //'to' => 'abouhamadi@yahoo.fr',
			            'subject'	=> "CADORIM: documents d'identité",
			            'title' 	=> 'CADORIM',
			            'firstName'	=> $tracker[0]->nom_exp,
			            'tracker'	=> $tracker[0],
			            'idUser'	=> $idUser,
			            'template'  => 'email.proof',
		        	];

		        	$response = $this->send($params);
	        		$message = $response?'Le message a été bien envoyé':$message;
			    break;
			    case "reminder":
			    	$orders = $this->orderRepository->getById($id);
				    $params = [
			            'to' => $tracker[0]->mail_exp,
			            //'to' => 'abouhamadi@yahoo.fr',
			            'subject'	=> "CADORIM: Bénéficiaire injoignable",
			            'title' 	=> 'CADORIM',
			            'firstName'	=> $tracker[0]->nom_exp,
			            'tracker'	=> $tracker[0],
			            'idUser'	=> $idUser,
			            'orders' 	=> $orders,
			            'template'  => 'email.reminder',
		        	];

		        	$response = $this->send($params);
	        		$message = $response?'Le message a été bien envoyé':$message;
			    break;
			    case "retire":
			    	$orders = $this->orderRepository->getById($id);
				    $params = [
			            'to' => $tracker[0]->mail_exp,
			            //'to' => 'abouhamadi@yahoo.fr',
			            'subject'	=> "CADORIM: Retrait",
			            'title' 	=> 'CADORIM',
			            'firstName'	=> $tracker[0]->nom_exp,
			            'tracker'	=> $tracker[0],
			            'idUser'	=> $idUser,
			            'orders' 	=> $orders,
			            'template'  => 'email.withdrawal',
		        	];

		        	$response = $this->send($params);
	        		$message = $response?'Le message a été bien envoyé':$message;
			    break;
			}
		}

		Session::flash('message', $message);
		//Session::flash('message', trans('lang.message.add.fil'));


		return redirect()->intended('/cash/out/edit/' . $id);
  	}


  	public function operation(Request $request, $id)
  	{
  		if(in_array($request['operation'], array('retire', 'transfert'))){

  			$status = $this->coordinatedOrdersRepository->getStatusById($id);

	  		$params = [
	    			'frais_gaza' 		=> $request['frais_gaza'],
	    			'tracker_status' 	=> $request['operation'],
	    			'agence_gaza' 		=> empty($request['agence_gaza'])?'CADORIM':$request['agence_gaza'],
	    			'point_retrait' 	=> $request['transfert_vers'],
	        	];


			switch (array($request['operation'], $status)) {
			    case array('retire', 'attente'):
			     	$this->soldeupdate($request, $id);
					$this->coordinatedOrdersRepository->updateById($id, $params);
					$this->request('retire', $id);
			        break;
			    case array('transfert', 'attente'):
			        $this->soldeupdate($request, $id);
					$this->coordinatedOrdersRepository->updateById($id, $params);
			        break;
			    case array('retire', 'transfert'):
			        $this->coordinatedOrdersRepository->updateById($id, ['tracker_status' =>'retire' ]);
			        $this->request('retire', $id);
			        break;
		        case array('retire', 'retire'):
			        return redirect()->intended('/cash/out/home');
			        break;
			}    
    		

			Session::flash('message', ' Modification effectuée avec success!');

  		}

  		
    	return redirect()->intended('cash/out/edit/'.$id);
	}


	/**
	 * 
	 * @param Request $request
	 * @param int $id
	 */
	private function soldeupdate($request, $id)
	{
		$id_client = ($request['transfert_vers'] == 'cadorim')?Auth::user()->id_client:$request['transfert_vers'];
		$row 			= $this->cashRepository->getByCodeConfirmation($id);
		$agence_solde 	= $this->agencyRepository->getBalanceLatestByIndex('1', $id_client);

      	if ($agence_solde) {

	        $charge = $request['somme_mru'] + $request['frais_gaza'];
	        
         	$update_solde = $agence_solde->solde_mru - $charge;
        	$this->agencyRepository->updateIndex('1', $id_client, '0');
      
            $params = [
            "idClient" => $id_client,
            "idClientDebiteur" => 'ID Commande :' . $id,
            'solde_avant_mru' => $agence_solde->solde_mru,
            'solde_mru' => $update_solde,
            'montant_mru' => $charge,
            "motif" => 'Transfert d\'argent ',
            "type_opperation" => 'retrait'
        	];
		
			$this->agencyRepository->create($params);

         	$solde 	= $this->cashRepository->getAvailableBalanceByIdMax();
         	$charge	= $request['somme_mru'] + $request['frais_gaza'];

      
         	$params = [
	            'id_client'     =>	99,
	            'expediteur'    => Auth::user()->email,
	            'nom_benef'     => 'Transfert: (' . $request['email_exp'] . ' )',
	            'phone_benef'   => 'ID COMMANDE = ' . $id,
	            'montant_euro'  =>  $request['somme_eur'],
	            'montant'       =>  $request['somme_mru'],
	            'operation'     =>  'retrait',
	            'solde_avant'   =>  $agence_solde->solde_mru,
	            'solde_apres'   =>   $update_solde,
	            'solde'         =>  $update_solde

        	];

        	$this->cashRepository->create($params);
	          
	        
	    } else {

	        if (empty($row)) {

	         	$solde = $this->cashRepository->getAvailableBalance();
	         	$charge = $request['somme_mru'] + $request['frais_gaza'];

	        	$params = [
		            'id_client'     =>	999,
		            'expediteur'    => Auth::user()->email,
		            'nom_benef'     => 'Transfert: (' . $request['email_exp'] . ' )',
		            'phone_benef'   => 'ID COMMANDE = ' . $id,
		            'montant_euro'  =>  $request['somme_eur'],
		            'montant'       =>  $request['somme_mru'],
		            'operation'     =>  'retrait',
		            'solde_avant'   =>  $solde,
		            'solde_apres'   =>   $solde -  $charge,
		            'solde'         =>  $solde -  $charge

	        	];
	        	$this->cashRepository->create($params);
	          
	        }
    	}
	}
	
	/**
	 * 
	 * @param int $id
	 * @return unknown
	 */
	public function viewDocument($id)
	{
	    $document = $this->documentsRepository->getPathById($id);
	    
	    $path = storage_path("app/" . $document);
	    
	    try {
	        return  response()->file('Invoices/' . $path);
	    } catch (\Exception $e) {
	        return view('backend/cashflow/transaction/sowPdf');
	    }
	}

	public function operator(Request $request)
	{
		$date = date("Y-m-d");
		if ($request['date']) {
			$date = $request['date'];
			Session::flash('message', ' Les resultat de la recherche date  ' . $request['date'] . '  نتائج البحث لتاريخ ');
		}

		switch (auth()->user()->hasRole('admin')) {
			case true:
				$idUser = isset($request['idUser'])?$request['idUser']:'';
				break;
			default:
				if(auth()->user()->hasRole('admin-agence'))
					$idUser = isset($request['idUser'])?$request['idUser']:'';
				else
					$idUser = Auth::user()->isAgencyOPerateur()? Auth::user()->id:'';
				break;
		}

		$cashout = array();
		$gaza = array();
	
		$user = $this->userRepository->getUserById($idUser);
		//$user = isset($user[0])?$user[0]:array();
		$clients = $this->userRepository->getByUserTypes(['gaza', 'mauritanie', 'ouldyenja', 'selibaby', 'tachout']);

		if(!empty($user)){
			$cashout =  $this->coordinatedOrdersRepository->getByDateOperator($date, $user->id_client);
			$gaza 	 =  $this->coordinatedOrdersRepository->getByDateOperatorType($date, $user->id_client, $user->user_type);
		}
		//var_dump($user->id_client, $user->user_type);die;
		return view('backend/cashout/management/operator', [
		'cashout' => $cashout,
		'gaza' => $gaza,
		'date' => $date,
		'idUser' => $idUser,
		'clients' => $clients
		]);
	}

	public function operationConfirmation($id, $operator)
	{
		$this->coordinatedOrdersRepository->updateById($id, ['gaza_confirm' => $operator,'updated_at' => Carbon::now() ]);
		
		return back() ->with('message', 'تم التحويل بنجاح');
	  }
}
