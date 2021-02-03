<?php

namespace App\Http\Controllers\Backend\CashFlow;

use App\Http\Controllers\Backend\BaseController;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class TransactionController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-Cash-Flow', 
        ['only' => ['sowPdf', 'deposit', 'put', 'withdrawal', 'balanceUpdate', 'addFiles', 'uploadProofDocument']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function deposit()
    {
        $clients = $this->userRepository->getClientBalanceByIndexAndUserType(1, 'business');

        return view('backend/cashflow/transaction/deposit', ['clients' => $clients]);
    }

    public function put(Request $request)
    {
        $this->validate($request, [
            'expediteur' => 'required',
            'nom_benef' => 'required',
            'montant' => 'required|numeric|min:0|not_in:0',
        ]);
        try {
            $this->customerDeposit($request);

            $balanceCustomer    = $this->customerRepository->getBalanceById($request['nom_benef']);
            $newBalanceCustomer = $balanceCustomer + $request['montant'];

            $this->customerRepository->updateBalanceById($request['nom_benef'], $request['montant'], $newBalanceCustomer);

            $client = $this->userRepository->getByCriterion('id_client',$request['nom_benef']);
            $societe = isset($client[0])?$client[0]->firstname:'';

            $balance = $this->cashRepository->getAvailableBalance();
            $input = [
                'id_client' => $request['nom_benef'],
                'expediteur' => $request['expediteur'],
                'nom_benef' => !empty($societe) ? $societe:$request['nom_benef'],
                'phone_benef' => $request['phone_benef'],
                'montant_euro' =>$request['montant_euro'],
                'montant' =>$request['montant'],
                'operation' =>$request['operation'],
                'code_confirmation' => str_shuffle(hexdec(uniqid())),
                'solde_avant' => $balance,
                'solde' => $balance + $request['montant'],
                'solde_apres' => $balance + $request['montant'],
            ];

            $this->cashRepository->create($input);

            $params = [
                'idClient' =>  $request['nom_benef'],
                'idUserDebtor' =>  Auth::user()->id,
                'amount' => $request['montant'],
                'reason' => $request['phone_benef'],
                'typeOperation' => 'deposit',
            ];

            $idBill = $this->billdepositwithdrawalRepository->create($params);

            $error = 0;
        } catch (ErrorException $e) {
            $error = 1;
            $idBill = 0;
        }

        return redirect()->intended('/cash/flow/transaction/confirmation/'.$error.'/'.$idBill);
    }

    /**
     * @param $request
     */
    private function customerDeposit($request)
    {
        $creditBalance = $this->customerBalanceRepository->getByIndexAndIdClient(1,$request['nom_benef']);

        $this->customerBalanceRepository->updateIndexByIdClient(1, $request['nom_benef'], 0);

        $params = [
            'idClient' => $request['nom_benef'],
            'idClientDebtor' => 'CADORIM',
            'balanceBeforeEuros' => $creditBalance->solde_euros,
            'balanceBeforeMru' => $creditBalance->solde_mru,
            'balanceEuros' =>  $creditBalance->solde_euros + $request['montant_euro'],
            'balanceMru' => $creditBalance->solde_mru +  $request['montant'],
            'amountEuros' =>  $request['montant_euro'],
            'rate' => '0',
            'amountMru' => $request['montant'],
            'motif' => $request['phone_benef'],
            'typeOperation' => 'Dépot'
        ];

        $this->customerBalanceRepository->create($params);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function withdrawal()
    {
        $clients = $this->userRepository->getByUserType('business');

        return view('backend/cashflow/transaction/withdrawal', ['clients' => $clients]);
    }


    public function amputation(Request $request)
    {
        $error = 1;
        $idBill = 0;

        try {
            $balance = $this->customerBalanceRepository->getByIndexAndIdClient('1', $request['id_client']);

            if ($balance) {
                $params =[
                    'idClient' => $request['id_client'],
                    'idClientDebtor' => $request['expediteur'],
                    'balanceBeforeEuros' => $balance->solde_euros,
                    'balanceBeforeMru' => $balance->solde_mru,
                    'balanceEuros' => 0,
                    'balanceMru' => $balance->solde_mru -  $request['montant_mru'],
                    'amountEuros' =>  0,
                    'rate' => 0,
                    'amountMru' => $request['montant_mru'],
                    'motif' => $request['message'],
                    'typeOperation' => 'Retrait'
                ];
                $this->balanceUpdate($request);

                $this->customerBalanceRepository->updateIndexByIdClient('1', $request['id_client'], '0');

                $this->customerBalanceRepository->create($params);

                $params = [
                    'idClient' =>  $request['id_client'],
                    'idUserDebtor' =>  Auth::user()->id,
                    'amount' => $request['montant_mru'],
                    'reason' => $request['message'],
                    'typeOperation' => 'withdrawal',
                ];

                $idBill = $this->billdepositwithdrawalRepository->create($params);

                $error = 0;
            }
        } catch (ErrorException $e) {
            $error = 1;
            $idBill = 0;
        }

        return redirect()->intended('/cash/flow/transaction/confirmation/'.$error.'/'.$idBill);
    }

    /**
     * @param $request
     */
    private function balanceUpdate($request)
    {
        $balance  = $this->cashRepository->getAvailableBalance();
        $client = $this->userRepository->getValueByIdClient($request['id_client'], 'firstname');
        $charge = $request['montant_mru'];

        $input['id_client'] = $request['id_client'];
        $input['nom_benef'] =$request['operation'].'__'.$client;
        $input['expediteur'] = Auth::user()->email;
        $input['montant_euro'] = 0;
        $input['montant'] = $request['montant_mru'];
        $input['solde_avant'] = $balance;
        $input['phone_benef'] = $request['message'];
        $input['operation'] = $request['operation'];
        $input['solde'] =  $balance - $charge;
        $input['solde_apres'] = $balance -  $charge;

        $this->cashRepository->create($input);
    }

    /**
     * @param $idCash
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function addFiles($idCash)
    {
        return view('backend/cashflow/transaction/addFiles', ['idCash'=> $idCash]);
    }

    /**
     * @param Request $request
     * @param $idCash
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadProofDocument(Request $request, $idCash)
    {
        if ($request->hasfile('filenames')) {
            foreach ($request->file('filenames') as $file) {
                $originalName = strtolower(trim($file->getClientOriginalName()));
                $fileName = time().rand(100,999).$originalName;
                $file->move(public_path() . '/Invoices/', $fileName);
                $data[] = $fileName;
            }

            $this->cashRepository->updateInvoicesById($idCash, implode(',', $data));


            Session::flash('message', trans('lang.message.add.fil'));


            return redirect()->intended('/cash/flow/home');
        }
    }

    /**
     * @param $path
     * @return mixed
     */
    public function sowPdf($path)
    {
        try {
            return  response()->file('Invoices/' . $path);
        } catch (\Exception $e) {
            return view('backend/cashflow/transaction/sowPdf');
        }

    }
}
