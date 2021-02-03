<?php

namespace App\Console\Commands;

use App\Repositories\Backend\LogSendmailRepository;
use App\Repositories\Backend\SubscribersRepository;
use App\Repositories\Backend\TaskSendMailSponsoringRepository;
use Illuminate\Console\Command;
use Mail;
use App\Mail\NewMail;


class SendEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sponsoring:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer des courriels pour parrainage';

    /**
     * @var  TaskSendMailSponsoringRepository
     */
    private $taskSendMailSponsoringRepository;

    /**
     * @var  SubscribersRepository
     */
    private $subscribersRepository;

    /**
     * @var  LogSendmailRepository
     */
    private $logSendmailRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->taskSendMailSponsoringRepository = new TaskSendMailSponsoringRepository();
        $this->subscribersRepository = new SubscribersRepository();
        $this->logSendmailRepository = new LogSendmailRepository();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $configurations = $this->taskSendMailSponsoringRepository->getByState('0');

        foreach ($configurations as $configuration) {
            if(!empty($configuration->sendall)){ 
                $this->sendByCriterion(null, null, null);
            }else{
                $this->sendByCriterion($configuration->numbre, $configuration->datestart, $configuration->dateend);
            }

            $this->taskSendMailSponsoringRepository->updateStateById($configuration->id, 1);
        }
        
    }

    /**
     * @param $numbre
     * @param $dateStart
     * @param $dateend
     */
    private function sendByCriterion($numbre, $dateStart, $dateend)
    {
        $subscribers = $this->subscribersRepository->searchByCriterion($dateStart, $dateend, $numbre);

        foreach ($subscribers as $subscriber) {
            $response = $this->send($subscriber->email, $subscriber->prenom);

            $this->logSendmailRepository->insert(
                ['email' => $subscriber->email,'subject'=>'subject',
                    'datesend' => date("Y-m-d H:i:s"),  'state' => $response ]
            );
        }
    }

    /**
     * @param $to
     * @return false
     */
    private function send($to, $firstName)
    {
        //$to = 'abouhamadi@yahoo.fr';
        $form = config('mail.from');
        $details = [
            'to' => $to,
            //'to' => 'abouhamadi@yahoo.fr',
            //'to' => 'mboirick@yahoo.fr',
            'from' => $form['address'],
            'subject' => 'Parrainage',
            'title' => $form['name'],
            "body"  => 'text à faire',
            "template"  => 'email.sponsoring',
            "firstName"  => $firstName,
            //"firstName"  => 'Abou'
        ];

        \Mail::to($to)->send(new \App\Mail\NewMail($details));
        if (Mail::failures())
            return 0;

        return 1;
    }
}
