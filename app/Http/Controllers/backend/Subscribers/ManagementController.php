<?php

namespace App\Http\Controllers\Backend\Subscribers;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;
use App\Exports\SubscribersExport;
use Excel;
use Session;
use File;


class ManagementController extends BaseController
{
	protected function setPermission()
    {
        $this->middleware('permission:abonnes', 
		['only' => ['index', 'edit', 'update', 'rejetSendForm', 'details', 
					'sendReminder', 'deleteFile', 'downloadFile', 'visualizeFile']]);
	}

	/**
	 * 
	 */
    public function index(Request $request)
 	{
 		$params = [
	        'username'	=> isset($request['nom'])?$request['nom']:'',
	        'email'		=> isset($request['email'])?$request['email']:'',
	        'phone'		=> isset($request['telephone'])?$request['telephone']:'',
	        'kyc' 		=> isset($request['statut_kyc'])?$request['statut_kyc']:''
	    ];

	    $search = isset($request['search'])?$request['search']:'';

 		switch ($search) {
		 	case "recherche":
		    	$subscribers = $this->subscribersRepository->getByCriterion($params);
		    break;
			case "excel":
		    	return Excel::download(new SubscribersExport($params), 'Abonnes.xlsx');
		    break;
		    default:
    			$subscribers = $this->subscribersRepository->getAllOrderBy();
		}


	    return view('backend/subscribers/index', [
			'abonnes' => $subscribers,
			'params' => $params,
			'stat'	=> $this->subscribersRepository->getStatus(),
			'actif'	=> $this->subscribersRepository->getByStatusAndKyc('Complet', '0'),
	    ]);
  	}

  	public function edit($id)
  	{
  		$subscriber = $this->subscribersRepository->getById($id);
	    $documents 	= $this->documentsRepository->getByIdUser($id);


    	return view('backend/subscribers/edit', ['user' => $subscriber, 'documents' => $documents]);
  	}

  	
  	public function update(Request $request)
	{

	    $id = $request['idUser'];

	    $files = $request->file('document');

	    if ($request->hasFile('document')) {
      		foreach ($files as $file) {
		        $this->documentsRepository->create(['id_user' => $id, 'path' => $file->store('avatars')]);
	    	}
	    }


	    $params = [
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


	    $this->subscribersRepository->update($id, $params);

	    $response = false;

	    switch ($request['kyc']) {
		 	case 1:
		    	$params = [
			            'to' => $request['email'],
			            //'to' => 'abouhamadi@yahoo.fr',
			            'subject'	=> 'CADORIM : approbation des documents KYC',
			            'title' 	=> 'CADORIM',
			            'firstName'	=> $request['prenom'] . ' ' . $request['username'],
			            'template'  => 'email.approval',
		        	];

		        	$response = $this->send($params);
		    break;
			case 2:
		        	return redirect()->intended('/subscribers/send/form/' . $id);
		    break;
		}


		Session::flash('message', ' Modification effectuée avec success!');

	    return redirect()->intended('/subscribers/home');
  	}



  	public function rejetSendForm(Request $request, $id)
  	{
  		$message ="Erreur lors de l'envoi du mail";
    	if($request['type'] == 'rejet'){
    		$tracker = $this->subscribersRepository->getById($id);
    		if(isset($tracker[0]) && !empty($tracker[0])){
	        	$params = [
					'to' => $tracker[0]->email,
					//'to' => 'abouhamadi@yahoo.fr',
					'subject'	=> "CADORIM: Documents KYC rejetès",
					'title' 	=> 'CADORIM',
					'firstName'	=> $tracker[0]->username,
					'tracker'	=> $tracker[0],
					'idUser'	=> $tracker[0]->id,
					'reason' 	=> $request['reason'],
					'template'  => 'email.reject',
				];

        		$response = $this->send($params);
        		$message = $this->send($params)?'Le message a été envoyé avec success!':$message;
    		}

    		Session::flash('message', $message);

    		return redirect()->intended('/subscribers/home');
    	}

	    return view('backend/subscribers/rejetSendForm', ['id' => $id]);
  	}


  	public function details(Request $request)
  	{	
      	$params = [
	        'username'	=> isset($request['nom'])?$request['nom']:'',
	        'email'		=> isset($request['email'])?$request['email']:'',
	        'phone'		=> isset($request['telephone'])?$request['telephone']:'',
	    ];

	    return view('backend/subscribers/details', [

	      'abonnes' =>  $this->subscribersRepository->getByStatusAndKycGroupeBy('Complet', '0', $params),
	      'params' => $params,
	    ]);
  	}

  	public function sendReminder($id)
  	{	
  		$tracker = $this->subscribersRepository->getById($id);

		$params = [
					'to' => $tracker[0]->email,
					//'to' => 'abouhamadi@yahoo.fr',
					'subject'	=> "CADORIM: documents d'identité",
					'title' 	=> 'CADORIM',
					'firstName'	=> $tracker[0]->username,
					'tracker'	=> $tracker[0],
					'idUser'	=> $tracker[0]->id,
					'template'  => 'email.proofReminder',
				];

		$message = $this->send($params)?'Le message a été envoyé avec success!':"Erreur lors de l'envoi du mail";

		Session::flash('message', $message);
	    
	    return redirect()->intended('/subscribers/details');
  	}


  	public function deleteFile($id)
	{
		$document = $this->documentsRepository->getById($id);

	    $id_user = $document[0]->id_user;
	    $path_doc = $document[0]->path;
	    $path = storage_path("app/" . $path_doc);


	    if (File::exists($path)) {

	    	File::delete($path);
	    }

	    $this->documentsRepository->deleteById($id);

	   return redirect()->intended('/subscribers/edit/' . $id_user);
  	}


  	public function downloadFile($id)
	{
	    $document = $this->documentsRepository->getPathById($id);

	    $path = storage_path("app/" . $document);

	    return response()->download($path);
	}


	public function visualizeFile($id)
	{
		$document = $this->documentsRepository->getPathById($id);

		$path = storage_path("app/" . $document);
		$ext = File::extension($document);

		switch (File::extension($document)) {
		    case 'pdf':
		        $content_types = 'application/pdf';
		        break;
		    case 'jpeg':
		    case 'jpg':
		        $content_types = 'image/jpeg';
		        break;
		    case 'png':
		        $content_types = 'image/png';
		        break;
	        default:
       			$content_types = '';
		}
		

		return response()->file($path, [
		  'Content-Type' => $content_types
		]);
	}

}
