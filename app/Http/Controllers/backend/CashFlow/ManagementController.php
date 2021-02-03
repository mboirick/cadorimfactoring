<?php

namespace App\Http\Controllers\backend\CashFlow;

use App\Exports\CashExport;
use App\Http\Controllers\Backend\BaseController;
use Session;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ManagementController extends BaseController
{
    protected function setPermission()
    {
        $this->middleware('permission:admin-Cash-Flow', 
        ['only' => ['index', 'dailyReport']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $constraints = [
            'dateStart' => date("Y/n/j", strtotime("- 30 day")),
            'dateEnd' => date("Y/n/j ", strtotime(" 1 day")),
            'type' => '',
            'idClient' => '',
            'idClient' => ''
        ];

        $idMax   = $this->cashRepository->getMaxId();
        $clients = $this->userRepository->getUsersBusiness();
        $caches  = $this->cashRepository->getCashByCreateDateUpdateDate($constraints['dateStart'], $constraints['dateEnd']);

        if($request->search == 'submitSearch' || $request->search == 'excel' ){
            $constraints = [
                'dateStart' => $request['dateStart'],
                'dateEnd' => $request['dateEnd'],
                'type' => $request['type'],
                'idClient' => $request['idClient']
            ];

            $caches  = $this->cashRepository->getCashByCriterion($constraints);
        }

        if($request->search == 'excel' ){
            return Excel::download(new CashExport($constraints), 'gestion_cash.xlsx');
        }

        return view('backend/cashflow/management/index', [
            'caches' => $caches,
            'clients' => $clients,
            'idmax' => $idMax,
            'searchingVals' => $constraints
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function dailyReport(Request $request)
    {
        $date = date('Y-m-d');

        if($request->search == 'submitSearch'){
            $date = $request->dateSearch;
        }

        $cashOut = $this->cashRepository->getCashOutByDate($date);
        $cashOutTotal = $this->cashRepository->getCashOutTotalByDate($date);

        $cashIn = $this->cashRepository->getByOperationAndDate($date, 'depot');

        $balancesClients =  $this->cashRepository->getBalanceBusinessByIndex('1', $date);

        $balanceCadorim = $this->cashRepository->getByLastBalanceByDate($date);
        $balanceAgencies = $this->getBalanceAgencies($date);

        return view('backend/cashflow/management/dailyReport', [
            'cachesout' => $cashOut,
            'cashOutTotal' => isset($cashOutTotal[0])?$cashOutTotal[0]:array(),
            'cashs_in' => $cashIn,
            'balancesClients' => $balancesClients,
            'balanceCadorim' => $balanceCadorim,
            'solde_cadorim' => $balanceAgencies,
            'date' => $date,
        ]);
    }

    /**
     * @param $date
     * @return array
     */
    private function getBalanceAgencies ($date)
    {
        $agencies = $this->agencyRepository->getByDate($date);

        $i = 0;
        $result = array();
        foreach ($agencies as $agency) {

            $balanceEnd = $this->agencyRepository->getByIdClientAndDate($date, $agency->id_client, 'desc');

            $balanceStart = $this->agencyRepository->getByIdClientAndDate($date, $agency->id_client, 'asc');

            $result['compte' . $i] = $balanceEnd->email;
            $result['soldeActuel' . $i] = $balanceEnd->solde_mru;
            $result['soldeDebut' . $i] = $balanceStart->solde_avant_mru;
            $i++;
        }

        return $result;
    }
}
