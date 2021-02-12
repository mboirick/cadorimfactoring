<?php

namespace App\Http\Controllers\Backend\Atlpay;

use Illuminate\Http\Request;
use App\Http\Controllers\Backend\BaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AtlpayExport;
use Auth;
use PDF;

class ManagementController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:atlpay', 
        ['only' => ['index', 'exportExcel', 'details']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $constraints = [
            'from'  => isset($request['from'])?$request['from']:date("Y/n/j", strtotime("- 30 day")),
            'to'    => isset($request['to'])?$request['to']:date("Y/n/j ", strtotime("1 day"))
        ];

        if ($request['search'] == 'excel')  
            return Excel::download(new AtlpayExport($constraints['from'], $constraints['to']), 'atlpay.xlsx');

        $atlpay = $this->getStats($constraints);

        return view('backend.atlpay.management.index', ['atlpay' => $atlpay, 'searchingVals' => $constraints]);
    }

    public function exportExcel(Request $request)
    {
        $params = [
            'from'  => isset($request['from'])?$request['from']:'',
            'to'    => isset($request['to'])?$request['to']:''
        ];

        return Excel::download(new AtlpayExport($params['from'], $params['to']), 'atlpay.xlsx');
    }

    private function getStats($constraints)
    {
        $from = $constraints['from'];
        $to = $constraints['to'];

        $cashs =  $this->atlpayRepository->getByDate($from, $to);
        $nombretransaction =  $this->atlpayRepository->getSumByDate($from, $to, 'nbr_transaction');
        $soldeatlpay = $this->atlpayRepository->getSumByDate($from, $to, 'somme_brut');
        $frais_atl = $this->atlpayRepository->getSumByDate($from, $to, 'frais_atl');

        $solde_net = floor($soldeatlpay - $frais_atl);
        $soldeatlpay = floor($soldeatlpay);
        return [$cashs, $nombretransaction, $soldeatlpay,  $frais_atl,  $solde_net];
    }

    public  function details($date)
    {
        $params = [
            'from' => date("Y/m/d", strtotime($date)),
            'to' => date("Y/m/d", strtotime("+1 day", strtotime($date)))

        ];
        $atlpay = $this->getCashs($params);

        return view('backend.atlpay.management.details', ['atlpay' => $atlpay, 'searchingVals' => $params]);
    }

    private function getCashs($constraints)
    {
        $from = $constraints['from'] . " 02:00:00";
        $to = $constraints['to'] . " 02:00:00";
        
        $cashs              = $this->paymentsRepository->getByDate($from, $to);
        $nombretransaction  = $this->paymentsRepository->getCountByDate($from, $to);
        $soldeatlpay        = $this->paymentsRepository->getSumByDate($from, $to);
        $frais_atl          = floor($soldeatlpay * 0.015 + $nombretransaction * 0.4);
        
        $solde_net      = floor($soldeatlpay - $frais_atl);
        $soldeatlpay    = floor($soldeatlpay);

        $id = $this->atlpayRepository->getIdByDate($constraints['from']);
        
        if (empty($id)) {
            $params = [
                'date' => $constraints['from'],
                'nbr_transaction' => $nombretransaction,
                'somme_brut' => $soldeatlpay,
                'frais_atl' => $frais_atl,
                'somme_net' =>  $solde_net
            ];

            $this->atlpayRepository->create($params);
        }

        return [$cashs, $nombretransaction, $soldeatlpay,  $frais_atl,  $solde_net];
    }
}
