<?php

namespace App\Http\Controllers\Backend\Agency;

use App\Http\Controllers\Backend\BaseController;
use Illuminate\Http\Request;
use Auth;
use Validator;



class ManagementController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-Agency', 
        ['only' => ['index', 'create', 'edit', 'update']]);
    }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $params = [
            'firstname' => empty($request['company'])?'':$request['company'],
            'email' => empty($request['email'])?'':$request['email'],
            'phone' => empty($request['phone'])?'':$request['phone'],
            'user_type' => 'operateur',
            'index' => '1',
        ];

        $numberOfAccounts = $this->userRepository->getNumberOfAccounts();
        $clients 		  = $this->userRepository->getByCriteria($params);
        $balance          = $this->agencyRepository->getBalance();
        $availableBalance = $this->cashRepository->getBalance();

        $request->session()->put('solde',
            [
                'nbr_compte' => $numberOfAccounts,
                'solde_mru' => round($balance),
                'solde_dispo' =>  round($availableBalance)]);

        return view('backend/agency/management/index', [
            'clients' => $clients,
            'balance' => round($balance),
            'availableBalance' => round($availableBalance),
            'company' => $params['firstname'],
            'email' => $params['email'],
            'phone' => $params['phone'],
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        $cities = $this->cityRepository->getAll();

        return view('backend/agency/management/create', ['villes'  => $cities]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:users|max:255',
            'ville' => 'required',
        ]);

        $params['user_type'] = 'operateur';
        $params['motif'] = 'Creation compte';
        $params['type_opperation'] = 'Creation';
        $params['agence'] = $request->agence;
        $params['nom'] = $request->nom;
        $params['prenom'] = $request->prenom;
        $params['email'] = $request->email;
        $params['telephone'] = $request->telephone;
        $params['ville'] = $request->ville;
        $params['quartier'] = $request->quartier;
        $params['idClient'] = uniqid();
        $params['solde_avant_mru']  = 0;
        $params['solde_mru']        = 0;
        $params['montant_mru']      = 0;

        $isCreateUser = $this->agencyRepository->create($params);
        $isCreateAgency = $this->agencyRepository->create($params);
        $isCreateAgencyAddress = $this->agencyaddressRepository->create($params);

        $isOk = $isCreateUser && $isCreateAgency && $isCreateAgencyAddress;

        return view('backend/agency/management/store', ['isOk'  => $isOk, 'params' =>$params]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $client = $this->userRepository->getById($id);

        $cities = $this->cityRepository->getAll();

        return view('backend/agency/management/edit', ['clientedit' => $client, 'villes' => $cities]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if ($request->password) {
            $this->validate($request, ['password' => 'required|min:6|confirmed']);
            $this->userRepository->updatePassword($id, $request['password']);
        }

        $error = false;
        try {
            $this->userRepository->updateById($id, $request);
            $this->agencyaddressRepository->updateByIdClient($id, $request);
        }catch(Exception $e) {
            $error = true;
        }

        return view('backend/agency/management/update',
            ['error' => $error,
                'agency' => $request['agence'],
                'idClient' => $id,
                ]);
    }
}
