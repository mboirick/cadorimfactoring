<?php

namespace App\Http\Controllers\backend\Agency;

use App\Http\Controllers\Backend\BaseController;
use App\Notifications\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TransactionController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-Agency', 
        ['only' => ['credit', 'addAmount', 'debit', 'withdrawAmount', 'deposit', 
        'put', 'withdrawal', 'amputation', 'story', 'operationStory', 'detail']]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function credit($id)
    {
        $clients = $this->userRepository->getExcludeById($id, 'operateur');
        $debtor = $this->userRepository->getByIndexAndIdClient('1', $id);

        return view('backend/agency/transaction/credit',
                        ['clients' => $clients, 'debiteur' => $debtor, 'id' =>$id]);
    }

    public function addAmount(Request $request, $id)
    {
        $config = array(
            'amount' => $request['montant'],
            'idDebtor' => $request['debiteur'],
            'idBenefit' => $request['benef'],
            'motif' => $request['motif'],
            'action' => 'add',
        );

        $this->updateOperation($config);

        return redirect()->intended('/agencies/transaction/credit/confirmation/' . $request['debiteur'] . '/' . $request['benef']);
    }

    /**
     * @param array $config
     * @return bool
     */
    private function updateOperation(array $config)
    {
        $debitBalance = $this->agencyRepository->getByIdClientAndIndex($config['idDebtor'],'1');
        $creditBalance = $this->agencyRepository->getByIdClientAndIndex($config['idBenefit'],'1');
        if(!empty($debitBalance) && !empty($creditBalance)){
            $this->agencyRepository->updateIndex('1', $config['idDebtor'], 0);
            $this->agencyRepository->updateIndex('1', $config['idBenefit'], 0);

            $params = [
                'idClient' => $config['idDebtor'],
                'id_client_debiteur' => $creditBalance->id_client,
                'solde_avant_mru' => $debitBalance->solde_mru,
                'solde_mru' => $config['idDebtor'] == 'add' ? $debitBalance->solde_mru + $config['amount'] : $debitBalance->solde_mru - $config['amount'],
                'montant_mru' => $config['amount'],
                'motif' => $config['motif'],
                'type_opperation' => $config['idDebtor'] == 'add' ?'credit' : 'debit'
            ];

            $this->agencyRepository->create($params);

            $params = [
                'idClient' => $config['idBenefit'],
                'id_client_debiteur' => $debitBalance->id_client,
                'solde_avant_mru' => $creditBalance->solde_mru,
                'solde_mru' => $config['idDebtor'] == 'add' ? $creditBalance->solde_mru -  $config['amount'] : $creditBalance->solde_mru + $config['amount'],
                'montant_mru' => $config['amount'],
                'motif' => $config['motif'],
                'type_opperation' => $config['idDebtor'] == 'add' ? 'debit' : 'credit'
            ];

            $this->agencyRepository->create($params);
        }
    }
    public function confirmationCredit($idClientDebtor, $idClientBenefit)
    {
        $debitBalance = $this->agencyRepository->getByIndexAndIdClient('1', $idClientDebtor);
        $creditBalance = $this->agencyRepository->getByIndexAndIdClient('1', $idClientBenefit);

        return view('backend/agency/transaction/confirmationcredit',
            ['clientDebtor' => $debitBalance, 'clientBenefit' => $creditBalance, 'idClient'=>$idClientDebtor]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function debit($id)
    {
        $clients = $this->agencyRepository->getExcludeById($id, 'operateur');

        $debiteur = $this->agencyRepository->getByIndexAndIdClient('1', $id);

        return view('backend/agency/transaction/debit', ['id'=>$id, 'clients' => $clients, 'debiteur' => $debiteur]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function withdrawAmount(Request $request)
    {
        $config = array(
            'amount' => $request['montant'],
            'idDebtor' => $request['debiteur'],
            'idBenefit' => $request['benef'],
            'motif' => $request['motif'],
            'action' => 'put',
        );
        $this->updateOperation($config);

        return redirect()->intended('/agencies/transaction/credit/confirmation/' . $config['idBenefit'] . '/' . $config['idDebtor']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function deposit()
    {
        $clients = $this->userRepository->getByIndexAndUserType(1,'operateur');

        return view('backend/agency/transaction/deposit', ['clients' => $clients]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function put(Request $request)
    {
        $balance  = $this->agencyRepository->getBalanceByIndex('1', $request['id_client']);

        $error = 1;
        $idBill = 0;
        $amount = $request['montant_mru'];
        if ($balance) {
            $this->agencyRepository->updateIndex('1', $request['id_client'], '0');

            $params = [
                'idClient' =>  $request['id_client'],
                'id_client_debiteur' =>  Auth::user()->email,
                'solde_avant_mru' => $balance->solde_mru,
                'solde_mru' => $balance->solde_mru +  $amount,

                'montant_mru' => $amount,
                'motif' =>  $request['message'],
                'type_opperation' => 'Dépot'
            ];

            $this->agencyRepository->create($params);

            //eneleve du cash disponilble globale

            $availableBalance = $this->cashRepository->getAvailableBalance();

            $client = $this->agencyRepository->getByCriterion('id_client',$request['id_client']);
            $agencyName = isset($client[0])?$client[0]->firstname:'';

            $params = [
                'id_client' =>  $request['id_client'],
                'expediteur' => $request['expediteur'],
                'nom_benef' => 'Agent:'.$agencyName,
                'phone_benef' => 'Depot Cash agence',
                'montant_euro' => 0,
                'montant' => $request['montant_mru'],
                'operation' =>  'retrait',
                'solde_avant' => $availableBalance,
                'solde_apres' =>  $availableBalance -$amount,
                'solde' => $availableBalance -$amount

            ];

            $this->cashRepository->create($params);

            $params = [
                'idClient' =>  $request['id_client'],
                'idUserDebtor' =>  Auth::user()->id,
                'amount' => $request['montant_mru'],
                'reason' => $request['message'],
                'typeOperation' => 'deposit',
            ];

            $idBill = $this->billdepositwithdrawalRepository->create($params);

            $error = 0;
        }

        return redirect()->intended('/agencies/transaction/confirmation/'.$error.'/'.$idBill);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function withdrawal()
    {
        $clients = $this->userRepository->getByIndexAndUserType(1,'operateur');

        return view('backend/agency/transaction/withdrawal', ['clients' => $clients]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function amputation(Request $request)
    {
        $balance  = $this->agencyRepository->getBalanceByIndex('1', $request['id_client']);

        $state = 1;
        $idBill = 0;
        if ($balance) {

            $this->agencyRepository->updateIndex('1', $request['id_client'], '0');

            $params = [
                'idClient' =>  $request['id_client'],
                'id_client_debiteur' =>  Auth::user()->email,
                'solde_avant_mru' => $balance->solde_mru,
                'solde_mru' => $balance->solde_mru -  $request['montant_mru'],
                'montant_mru' => $request['montant_mru'],
                'motif' =>  $request['message'],
                'type_opperation' => 'retrait'

            ];

            $this->agencyRepository->create($params);

            $params = [
                'idClient' =>  $request['id_client'],
                'idUserDebtor' =>  Auth::user()->id,
                'amount' => $request['montant_mru'],
                'reason' => $request['message'],
                'typeOperation' => 'withdrawal',
            ];

            $idBill = $this->billdepositwithdrawalRepository->create($params);
            $state = 0;
        }

        return redirect()->intended('/agencies/transaction/confirmation/'.$state.'/'.$idBill);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function story($id)
    {
        $payments = $this->agencyRepository->getByIdClient($id);
        $agencies = $this->userRepository->getByUserType('operateur');

        return view('backend/agency/transaction/story', [
            'paiements' => $payments,
            'agences'=> $agencies,
        ]);
    }

    public function operationStory($id)
    {
        $email = $this->userRepository->getEmailByIdClient($id);
        $day = $this->transactionstatisticsRepository->getDateByEmail($email);

        if ($email) {
            if (empty($day)) {
                $day = '2008-01-01';
            }

            $operations =  $this->cashRepository->getOperationsByEmailAndDAy($email, $day);
            $update =  $this->cashRepository->getOperationsUpdateByEmailAndDAy($email, $day);

            foreach ($operations as $operation) {
                $input = [
                    'id_agence' => $id,
                    'email_agence' => $email,
                    'jours' => $operation->day,
                    'nbr_operation' =>  $operation->nbr,
                    'total' =>  $operation->somme,
                    'total_gaza' =>  $operation->somme_gaza,
                    'total_euro' =>  $operation->somme_euro
                ];

                $this->transactionstatisticsRepository->create($input);
            }

            foreach ($update as $operation) {
                $params = [
                    'id' => $id,
                    'email' => $email,
                    'day' => $operation->day,
                    'nbr' =>  $operation->nbr,
                    'somme' =>  $operation->somme,
                    'somme_gaza' =>  $operation->somme_gaza,
                    'somme_euro' =>  $operation->somme_euro

                ];

                $this->transactionstatisticsRepository->updateStatByEmailANdDay($email, $day, $params);
            }

            $operations =  $this->transactionstatisticsRepository->getStatByEmail($email);

            return view('backend/agency/transaction/operationStory', [
                'operations' => $operations,
            ]);
        }
    }
    
    public function detail($idClient, $day)
    {
        $email = $this->userRepository->getEmailByIdClient($idClient);

        if ($email) {
            $caches =  $this->cashRepository->getDetailOperationsByEmailAndDAy($email, $day);
            $agent = $this->transactionstatisticsRepository->getStatByEmailAndDAy($email, $day);

            return view('backend/agency/transaction/detail', [
                'caches' => $caches,
                'agent' => $agent,
                'idClient' => $idClient,
            ]);
        }
    }
}

