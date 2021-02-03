<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        if(Auth::user()->user_type == 'api'){
            Auth::logout();
            return redirect()->intended('/login');
        }

        $redirect = $this->configuration();
        if(!empty($redirect))
            return redirect()->intended($redirect);

        $numberSubscribers  = $this->agencyRepository->getCount();
        $monthlySubscribers = $this->subscribersRepository->getCountByMonthAndYear(date('m'), date('Y'));
        $balanceCashOut     = $this->coordinatedOrdersRepository->getBalanceCashByStatus('attente', 'omplet');
        $balance            = Auth::user()->user_type =='operateur'?$this->agencyRepository->getBalanceCurrentUserByIdClientAndIndex(Auth::user()->id_client, '1'):$this->cashRepository->getBalanceLatest();
        $transferEUR        = $this->paymentsRepository->getBalanceByChange('Virement','En attente', 'EUR' );
        $transferUSD        = $this->paymentsRepository->getBalanceByChange('Virement', 'En attente', 'USD');

        return view('backend/dashboard', [
            'solde' => $balance,
            'abonnes' => $numberSubscribers,
            'abonnesmois' => $monthlySubscribers,
            'soldecashout' => $balanceCashOut,
            'virementEUR' => $transferEUR,
            'virementUSD' => $transferUSD,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function configuration()
    {
        $redirect = '';
        switch (Auth::user()->user_type) {
            case 'operateur':
                $redirect = '/cashout-management';
                break;
            case 'cash':
                $redirect = '/cache-management';
                break;
            case 'marketing':
                $redirect = '/sondage-management/survey';
                break;
            case 'client':
                $redirect = '/abonnes-management';
                break;
            case 'gaza':
                $redirect = '/cash/out/operator';
                break;
        }
        
        return $redirect;
    }
}
