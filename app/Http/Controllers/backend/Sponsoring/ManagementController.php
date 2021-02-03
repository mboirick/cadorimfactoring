<?php

namespace App\Http\Controllers\Backend\Sponsoring;

use App\Http\Controllers\Backend\BaseController;
use Illuminate\Http\Request;

class ManagementController extends BaseController
{

    protected function setPermission()
    {
        $this->middleware('permission:parrainage', 
		['only' => ['index', 'edit', 'update', 'rejetSendForm', 'details', 
					'sendReminder', 'deleteFile', 'downloadFile', 'visualizeFile']]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        $subscribers = $this->subscribersRepository->getAllSponsoring();

        return view('backend/sponsoring/index', [
            'subscribers' => $subscribers
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $subscribers = array();
        $params      = array();
        if($request['search']=='recherche'){
            $nombre  = empty($request['nombre'])?0:$request['nombre'];
            $params  = array(
                'nom' => isset($request['nom'])?$request['nom']:'',
                'email' => isset($request['email'])?$request['email']:'',
                'nombre' => isset($request['nombre'])?$request['nombre']:'',
            );

            $subscribers = $this->subscribersRepository->getSearchSponsoringByCriterion($params['nom'], $params['email'], $nombre);
        }

        return view('backend/sponsoring/index', [
            'subscribers' => $subscribers, 'params' => $params
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function emailForm($id)
    {
        return view('backend/sponsoring/emailForm', [
            'user' => $this->subscribersRepository->getById($id),
            'send'=> 0
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function emailSend(Request $request, $id)
    {
        $send = 0;
        if($request['send']=='send'){
            try {
                $send = 1;
                $params = [
                    'to' => $request->email,
                    //'to' => 'abouhamadi@yahoo.fr',
                    'subject' => 'Parrainage',
                    'title' => 'titre',
                    'firstName' => $request->firstName,
                    'template'  => 'email.sponsoring',
                ];

                $response = $this->send($params);

            }catch (Exception $e) {
                $response = false;
            }
        }


        return view('backend/sponsoring/emailForm', [
            'response' => $response,
            'send' => $send,
            'user' => $this->subscribersRepository->getById($id),
        ]);
    }

    public function planSend(Request $request)
    {
        $confirmation = $request->confirmation;
        if($request->add == 'add'){
            $params = [
                'all' => $request->all,
                'startdate' => $request->startdate,
                'enddate' => $request->enddate,
                'numbre' => $request->numbre,
            ];
            $this->taskSendMailSponsoringRepository->insert($params);

            return redirect()->intended('/sponsoring/plan/send?confirmation=ok');
        }

        return view('backend/sponsoring/planSend', [
            'confirmation' => $confirmation,
        ]);
    }
}
