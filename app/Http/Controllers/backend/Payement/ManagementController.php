<?php

namespace App\Http\Controllers\Backend\Payement;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;

class ManagementController extends BaseController
{
    public function clients(Request $request)
    {
        $nbr_compte = $this->userRepository->getNumberOfAccounts('1', 'business');
        $solde_eur  = $this->userRepository->getSumByColumns('business', '1', 'solde_euros');
        $solde_mru  = $this->userRepository->getSumByColumns('business', '1', 'solde_mru');
        
        $clients        = $this->userRepository->getClientsByIndiceUserType('1', 'business');
        $solde_dispo    = $this->cashRepository->getAvailableBalance();

        $infos =  ['nbr_compte' => $nbr_compte, 'solde_eur' => floor($solde_eur), 'solde_mru' => floor($solde_mru), 'solde_dispo' =>  floor($solde_dispo)];

        return view('backend.payement.management.clients', [
            'clients' => $clients,
            'infos' => $infos,
        ]);
    }

    public function search(Request $request)
    {
        return view('backend.payement.management.clients', [
            'clients' => $this->userRepository->getClientsByCriterion($request),
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

        return $this->client($request);
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
        $solde_debiteur = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['benef']);
        
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

        return redirect()->intended('/paiement-management/clients');
    }

    public function waiting()
    {
        /*$paiements = DB::table('cadorimpays')
            ->leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname')
            ->where('cadorimpays.statut', '=', '0')
            ->orderBy('cadorimpays.created_at', 'DESC')
            ->paginate(20);

        return view('paiement-mgmt/paiementcourant', [
            'paiements' => $paiements

        ]);*/
    }
}
