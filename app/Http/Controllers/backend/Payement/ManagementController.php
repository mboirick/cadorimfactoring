<?php

namespace App\Http\Controllers\Backend\Payement;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;

class ManagementController extends BaseController
{
    public function clients(Request $request)
    {
        $nbr_compte = $this->userRepository->getNumberOfAccounts('1', 'business');
        $solde_eur  = $this->userRepository->getSumByColumns('business', '1', 'solde_euros');
        $solde_mru  = $this->userRepository->getSumByColumns('business', '1', 'solde_mru');
        
       // $clients        = $this->userRepository->getClientsByIndiceUserType('1', 'business');
        $solde_dispo    = $this->cashRepository->getAvailableBalance();

        $params = [
                'societe' => isset($request['societe'])?$request['societe']:'', 
                'email' => isset($request['email'])?$request['email']:'', 
                'telephone' => isset($request['telephone'])?$request['telephone']:''
                ];
        switch ($request['search']) {
            case 'recherche':
                //$params = ['societe' =>$request['societe'], 'email' => $request['email'], 'telephone' =>$request['societe']];
                $clients = $this->userRepository->getClientsByCriterion($params);
                break;
            case 'excel':
                return Excel::download(new PaymentExport($params), 'payments.xlsx');
                break;
            default:
                $clients = $this->userRepository->getClientsByIndiceUserType('1', 'business');
        }

        $infos =  ['nbr_compte' => $nbr_compte, 'solde_eur' => floor($solde_eur), 'solde_mru' => floor($solde_mru), 'solde_dispo' =>  floor($solde_dispo)];

        return view('backend.payement.management.clients', [
            'clients' => $clients,
            'infos' => $infos,
            'params' => $params
        ]);
    }

    public function credit($id)
    {
        $clients = $this->userRepository->getClientsByUserType($id, 'business');
        $debiteur = $this->userRepository->getByIdClient($id);

        return view('backend.payement.management.credit',  ['clients' => $clients, 'debiteur' => $debiteur]);
    }

    public function debite($id)
    {
        $clients = $this->userRepository->getClientsByUserType($id, 'business');
        $debiteur = $this->userRepository->getByIdClient($id);

        return view('backend.payement.management.debite', ['clients' => $clients, 'debiteur' => $debiteur]);
    }

    public function story($id)
    {
        $paiements  = $this->customerBalanceRepository->getByIdClient($id);
        $comptes    = $this->userRepository->getAllByUserType('business');

        return view('backend.payement.management.story', [
            'paiements' => $paiements,
            'comptes' => $comptes,
        ]);
    }

    public function edit($id)
    {
        $clientedit = $this->userRepository->getUserByIdClient($id);
        $solde      = $this->customerBalanceRepository->getFirstByIdClient($id);

        return view('backend.payement.management.edit', ['clientedit' => $clientedit, 'solde' => $solde]);
    }

    public function update($id, Request $request)
    {
        $inputcash['nom_benef'] = $request['societe'];
        
        $this->userRepository->updateById($id, [
            'agence' => $request['societe'],
            'nom' => $request['nom'],
            'prenom' => $request['prenom'],
            'telephone' => $request['telephone']
        ]);
        
        $this->customerBalanceRepository->updateParamsIndexByIdClient('1', $id, [
            'solde_euros' => $request['solde_eur'],
            'solde_mru' => $request['solde_mru']
        ]);

        return $this->clients($request);
    }

    public function store(Request $request)
    {
        $id_client = uniqid();
        
        $this->userRepository->createUser([
            'id_client' => $id_client,
            'user_type' => 'business',
            'firstname' => $request['societe'],
            'name' => $request['nom'],
            'lastname' => $request['prenom'],
            'email' => $request['email'],
            'phone' => $request['telephone'],
            'password' => Hash::make($id_client),
            'email_verified_at' => date("Y-m-d H:i:s")
        ]);

        $this->customerBalanceRepository->createClient([
            'id_client' => $id_client,
            'id_client_debiteur' => 'creation',
            'solde_avant_euros' => 0,
            'solde_avant_mru' => 0,
            'solde_euros' => $request['solde_eur'],
            'solde_mru' => $request['solde_mru'],
            'montant_euros' => $request['solde_eur'],
            'taux' => 0,
            'montant_mru' => $request['solde_mru']

        ]);

        return redirect()->intended('/payement/clients');
    }

    public function addamount(Request $request)
    {
        $montant_euro   = $request['montant_euro'];
        $montant_mru    = $request['montant'];

        $solde_debiteur     = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['debiteur']);
        $solde_crediteur    = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['benef']);

        $this->customerBalanceRepository->updateIndexByIdClient('1', $request['debiteur'], 0);

        $this->customerBalanceRepository->updateIndexByIdClient('1', $request['benef'], 0);

        $this->customerBalanceRepository->createClient([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_euros' => $solde_debiteur->solde_euros,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_euros' => $solde_debiteur->solde_euros + $montant_euro,
            'solde_mru' => $solde_debiteur->solde_mru +  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);

        $this->customerBalanceRepository->createClient([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_euros' => $solde_crediteur->solde_euros,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_euros' => $solde_crediteur->solde_euros - $montant_euro,
            'solde_mru' => $solde_crediteur->solde_mru -  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        return redirect()->intended('/payement/clients');
    }

    public function withdrawalamount(Request $request)
    {
        $montant_euro = $request['montant_euro'];
        $montant_mru = $request['montant'];

        $solde_debiteur = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['debiteur']);
        $solde_crediteur = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['benef']);
        
        $this->customerBalanceRepository->updateIndexByIdClient('1', $request['debiteur'], 0);
        $this->customerBalanceRepository->updateIndexByIdClient('1', $request['benef'], 0);

        $this->customerBalanceRepository->createClient([
            'id_client' => $request['debiteur'],
            'id_client_debiteur' => $solde_crediteur->id_client,
            'solde_avant_euros' => $solde_debiteur->solde_euros,
            'solde_avant_mru' => $solde_debiteur->solde_mru,
            'solde_euros' => $solde_debiteur->solde_euros - $montant_euro,
            'solde_mru' => $solde_debiteur->solde_mru -  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'debit'
        ]);

        $this->customerBalanceRepository->createClient([
            'id_client' => $request['benef'],
            'id_client_debiteur' => $solde_debiteur->id_client,
            'solde_avant_euros' => $solde_crediteur->solde_euros,
            'solde_avant_mru' => $solde_crediteur->solde_mru,
            'solde_euros' => $solde_crediteur->solde_euros + $montant_euro,
            'solde_mru' => $solde_crediteur->solde_mru +  $montant_mru,
            'montant_euros' =>  $montant_euro,
            'taux' => $request['taux'],
            'montant_mru' => $montant_mru,
            'motif' => $request['motif'],
            'type_opperation' => 'credit'
        ]);

        return redirect()->intended('/payement/clients');
    }

    public function waiting(Request $request)
    {
        $params = [
            'emetteur' => isset($request['emetteur'])?$request['emetteur']:'', 
            'beneficiaire' => isset($request['beneficiaire'])?$request['beneficiaire']:'', 
            'operation' => isset($request['operation'])?$request['operation']:''
            ];

        $paiements = $this->cadorimpaysRepository->getIdByStatus('0');

        return view('backend.payement.management.waiting', [
            'paiements' => $paiements,
            'params' => $params,
        ]);
    }

    public function detail($id_paiement)
    {
        $paiements = $this->cadorimpaysRepository->getIdByStatus('0', $id_paiement);
        $id_client =  $paiements[0]->id_client;
        $documents = $this->invoicesRepository->getByIdPay($id_paiement);
        $soldes = $this->customerBalanceRepository->getByIndexAndIdClient('1', $id_client);

        return view('backend.payement.management.detail', [
            'paiements' => $paiements[0],
            'documents' => $documents,
            'soldes' => $soldes

        ]);
    }

    public function transaction(Request $request)
    {
        $id_paiement    = $request['paiement'];
        $reponse        = $request['remarque'];
        $id_client      = $request['id_client'];

        $infos = $this->cadorimpaysRepository->getByIdPay($id_paiement);

        if ($request['operation'] == 'approuver'  && $infos->statut == 0) {

            $data_avant = $this->customerBalanceRepository->getByIndexAndIdClient('1', $id_client);
            $files = $request->file('document');
            if ($request->hasFile('document')) {
                foreach ($files as $file) {
                    $path = $file->store('factures');
                    $input['type'] = "recu";
                    $input['id_client'] = $id_client;
                    $input['id_paiement'] = $id_paiement;
                    $input['path'] = $path;
                    $input['numero_facture'] = $request['reference'];

                    $this->invoicesRepository->create($input);
                }
            }

            if ($request['type_demande'] == 'credit') {
                $input = [
                    'id_client' =>  $id_client,
                    'id_client_debiteur' => $request['type_demande'],
                    'solde_avant_euros' => $data_avant->solde_euros,
                    'solde_avant_mru' => $data_avant->solde_mru,
                    'solde_euros' => $data_avant->solde_euros + $request['montant_euros'],
                    'solde_mru' => $data_avant->solde_mru +  $request['montant_mru'],
                    'montant_euros' => $request['montant_euros'],
                    'taux' => $request['taux_echange'],
                    'montant_mru' => $request['montant_mru'],
                    'indice' => 1,
                    'motif' =>  $reponse,
                    'type_opperation' => $request['type_demande']

                ];
            } else {
                $input = [
                    'id_client' =>  $id_client,
                    'id_client_debiteur' => $infos->entreprise,
                    'solde_avant_euros' => $data_avant->solde_euros,
                    'solde_avant_mru' => $data_avant->solde_mru,
                    'solde_euros' => $data_avant->solde_euros - $request['montant_euros'],
                    'solde_mru' => $data_avant->solde_mru -  $request['montant_mru'],
                    'montant_euros' => $request['montant_euros'],
                    'taux' => $request['taux_echange'],
                    'montant_mru' => $request['montant_mru'],
                    'indice' => 1,
                    'motif' =>  $reponse,
                    'type_opperation' => $request['type_demande']

                ];
            }

            $this->customerBalanceRepository->updateByIdClient(['indice' => 0]);
            $this->customerBalanceRepository->createClient($input);
            $this->cadorimpaysRepository->UpdateByIdPay(['statut' => 1, 'reponses' =>  $reponse]);  
        }

        if ($request['operation'] == 'rejeter'  && $infos->statut == 0)
            $this->cadorimpaysRepository->UpdateByIdPay(['statut' => 2, 'reponses' =>  $reponse]);

        return redirect()->intended('payement/waiting');
    }
}
