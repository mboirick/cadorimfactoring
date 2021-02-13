<?php

namespace App\Http\Controllers\Backend;

use App\Notifications\Bill;
use App\Repositories\Backend\CoordinatedOrdersRepository;
use App\Repositories\Backend\DocumentsRepository;
use App\Repositories\Backend\AgencyaddressRepository;
use App\Repositories\Backend\AgencyRepository;
use App\Repositories\Backend\BilldepositwithdrawalRepository;
use App\Repositories\Backend\CashRepository;
use App\Repositories\Backend\CityRepository;
use App\Repositories\Backend\CustomerBalanceRepository;
use App\Repositories\Backend\CustomerRepository;
use App\Repositories\Backend\AtlpayRepository ;
use App\Repositories\Backend\OrderRepository;
use App\Repositories\Backend\PaymentsRepository;
use App\Repositories\Backend\InvoicesRepository;
use App\Repositories\Backend\CadorimpaysRepository;
use App\Repositories\Backend\SubscribersRepository;
use App\Repositories\Backend\TaskSendMailSponsoringRepository;
use App\Repositories\Backend\TransactionstatisticsRepository;
use App\Repositories\Backend\SendPollsRepository;
use App\Repositories\Backend\PollsRepository;
use App\Repositories\Backend\CouponRepository;
use App\Repositories\Backend\PollResponseRepository;
use App\Repositories\Backend\UserRepository;
use App\Repositories\Backend\RoleRepository;
use App\Repositories\Backend\PermissionRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use App\Mail\NewMail;
use Mail;
use Auth;


class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @var  DocumentsRepository
     */
    protected $documentsRepository;

    /**
     * @var  UserRepository
     */
    protected $userRepository;

    /**
     * @var  CashRepository
     */
    protected $cashRepository;

    /**
     * @var  CustomerBalanceRepository
     */
    protected $customerBalanceRepository;

    /**
     * @var  CustomerRepository
     */
    protected $customerRepository;

    /**
     * The Agency repository instance.
     *
     * @var AgencyRepository
     */
    protected $agencyRepository;

    /**
     * @var  BilldepositwithdrawalRepository
     */
    protected $billdepositwithdrawalRepository;

    /**
     * @var  CityRepository
     */
    protected $cityRepository;

    /**
     * @var TransactionstatisticsRepository
     */
    protected $transactionstatisticsRepository;

    /**
     * @var  AgencyaddressRepository
     */
    protected $agencyaddressRepository;

    /**
     * @var  OrderRepository
     */
    protected $orderRepository;

    /**
     * @var  PaymentsRepository
     */
    protected $paymentsRepository;

    /**
     * @var  SubscribersRepository
     */
    protected $subscribersRepository;

    /**
     * @var  TaskSendMailSponsoringRepository
     */
    protected $taskSendMailSponsoringRepository;

    /**
     * @var  CoordinatedOrdersRepository
     */
    protected $coordinatedOrdersRepository;

    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
    * @var SendPollsRepository
    */
    protected $sendPollsRepository;

    /**
    * @var PollsRepository
    */
    protected $PollsRepository;

    /**
    * @var PollResponseRepository
    */
    protected $pollResponseRepository;

    /**
    * @var CouponRepository
    */
    protected $couponRepository;

    /**
    * @var AtlpayRepository
    */
    protected $atlpayRepository;

    /**
    * @var CadorimpaysRepository
    */
    protected $cadorimpaysRepository;

    /**
    * @var InvoicesRepository
    */
    protected $invoicesRepository;

    /**
     * BaseController constructor.
     * @param UserRepository $userRepositor
     * @param CashRepository $cashRepositor
     * @param CustomerBalanceRepository $customerBalanceRepository
     * @param CustomerRepository $customerRepository
     * @param AgencyRepository $agencyRepositor
     * @param BilldepositwithdrawalRepository $billdepositwithdrawalRepository
     * @param CityRepository $cityRepository
     * @param TransactionstatisticsRepository $transactionstatisticsRepository
     * @param AgencyaddressRepository $agencyaddressRepository
     * @param OrderRepository $orderRepository
     * @param PaymentsRepository $paymentsRepository
     * @param SubscribersRepository $subscribersRepository
     * @param TaskSendMailSponsoringRepository $taskSendMailSponsoringRepository
     * @param SendPollsRepository $sendPollsRepository
     * @param pollsRepository $pollsRepository
     * @param PollResponseRepository $pollResponseRepository
     * @param CouponRepository $couponRepository
     * @param AtlpayRepository $atlpayRepository
     * @param CadorimpaysRepository $cadorimpaysRepository
     * @param InvoicesRepository $invoicesRepository
     */
    public function __construct(UserRepository $userRepository, CashRepository $cashRepository,
                                CustomerBalanceRepository $customerBalanceRepository, 
                                CustomerRepository $customerRepository,
                                AgencyRepository $agencyRepository, BilldepositwithdrawalRepository $billdepositwithdrawalRepository,
                                CityRepository $cityRepository, TransactionstatisticsRepository $transactionstatisticsRepository,
                                AgencyaddressRepository $agencyaddressRepository, OrderRepository $orderRepository,
                                PaymentsRepository $paymentsRepository, SubscribersRepository $subscribersRepository,
                                TaskSendMailSponsoringRepository $taskSendMailSponsoringRepository, 
                                DocumentsRepository $documentsRepository, CoordinatedOrdersRepository $coordinatedOrdersRepository,
                                RoleRepository $roleRepository, PermissionRepository $permissionRepository,
                                SendPollsRepository $sendPollsRepository, PollsRepository $pollsRepository,
                                PollResponseRepository $pollResponseRepository, CouponRepository $couponRepository,
                                AtlpayRepository $atlpayRepository, CadorimpaysRepository $cadorimpaysRepository,
                                InvoicesRepository $invoicesRepository)
    {
        $this->documentsRepository              = $documentsRepository;
        $this->coordinatedOrdersRepository      = $coordinatedOrdersRepository;
        $this->userRepository                    = $userRepository;
        $this->cashRepository                    = $cashRepository;
        $this->customerBalanceRepository        = $customerBalanceRepository;
        $this->customerRepository               = $customerRepository;
        $this->agencyRepository                  = $agencyRepository;
        $this->cityRepository                   = $cityRepository;
        $this->billdepositwithdrawalRepository  = $billdepositwithdrawalRepository;
        $this->transactionstatisticsRepository  = $transactionstatisticsRepository;
        $this->agencyaddressRepository          = $agencyaddressRepository;
        $this->orderRepository                  = $orderRepository;
        $this->paymentsRepository               = $paymentsRepository;
        $this->subscribersRepository            = $subscribersRepository;
        $this->taskSendMailSponsoringRepository = $taskSendMailSponsoringRepository;
        $this->roleRepository                   = $roleRepository;
        $this->permissionRepository             = $permissionRepository;
        $this->sendPollsRepository              = $sendPollsRepository;
        $this->pollsRepository                  = $pollsRepository;
        $this->pollResponseRepository           = $pollResponseRepository;
        $this->couponRepository                 = $couponRepository;
        $this->atlpayRepository                 = $atlpayRepository;
        $this->cadorimpaysRepository            = $cadorimpaysRepository;
        $this->invoicesRepository               = $invoicesRepository;

        $this->middleware('auth');
        $this->setPermission();
    }

    protected function setPermission()
    {

    }

    /**
     * @param $idBill
     * @param $idClient
     */
    protected function notify($idBill, $idClient, $action)
    {
        $pdf = $this->getBillById($idBill);
        $output = $pdf->output();
        $user = $this->userRepository->getById($idClient);
        //$user->email = 'abouhamadi@yahoo.fr';
        //$user->email = 'mboirick@yahoo.fr';

        if(isset($user))
            $user->notify(new Bill($output, $idBill, $user, $action));
    }

    public function confirmation($state, $idBill)
    {
        $bill = $this->billdepositwithdrawalRepository->getById($idBill);
        if(isset($bill[0])){
            $idClient = $bill[0]->id_client;
            $amount = $bill[0]->amount;

            $client = $this->userRepository->getByCriterion('id_client',$idClient);
            $agencyName = isset($client[0])? $client[0]->firstname:'';

            $this->notify($idBill, $idClient, trans('lang.' . $bill[0]->type_operation));

            return view('backend/agency/transaction/confirmationbill', [
                'error' => $state,
                'agencyName' => $agencyName,
                'amount' => $amount,
                'dateToday' => date("Y-m-d"),
                'idBill' => $idBill,
                'typeOperation' => $bill[0]->type_operation,
            ]);
        }

        return view('backend/agency/transaction/confirmationbill', ['error' => 1]);
    }

    /**
     * @param $idBill
     * @return false
     */
    protected function getBillById($idBill)
    {
        $bill = $this->billdepositwithdrawalRepository->getById($idBill);
        if(isset($bill[0])){
            $idClient = $bill[0]->id_client;
            $amount   = $bill[0]->amount;
            
            $typeOperation = $bill[0]->type_operation;

            $client = $this->userRepository->getByCriterion('id_client',$idClient);
            $agencyName = isset($client[0])? $client[0]->firstname:'';

            $html=view('backend/agency/transaction/bill',
                [
                    'agencyName' => $agencyName,
                    'amount' => $amount,
                    'typeOperation' => $typeOperation,
                    'idBill' => $idBill,
                    'dateToday' => date("d-m-Y"),
                ]);

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);

            return $pdf;
        }

        return false;
    }

    protected function send($params)
    {
        $form = config('mail.from');
        $params['from'] = $form['address'];
        // $params['subject'] = 'Parrainage';
        $params['title'] = $form['name'];


        \Mail::to($params['to'])->send(new \App\Mail\NewMail($params));
        if (Mail::failures())
            return false;

        return true;
    }

    public function downloadBill($idBill)
    {
        $pdf = $this->getBillById($idBill);
        if($pdf){
            return $pdf->download($idBill.'.pdf');
        }

        return redirect()->intended('/agencies/home');
    }
}
