<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Backend\SubscribersRepository;
use Mail;
use App\Mail\NewMail;

class Subscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Envoyer un rappel pour les abonnes qui n'ont pas encore envoyer leurs piéces d'identités";


    /**
     * @var  SubscribersRepository
     */
    private $subscribersRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->subscribersRepository = new SubscribersRepository();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $params = ['username' => '', 'email' => '', 'phone' => ''];

        $subscribers =  $this->subscribersRepository->getByStatusAndKycGroupeBy('Complet', '0', ['username' => '', 'email' => '', 'phone' => '']);
        
        foreach ($subscribers as $subscriber) {
            $tracker = $this->subscribersRepository->getById($subscriber->id);
            if(isset($tracker[0]) && !empty($tracker[0]))
                $this->send($tracker);
        }
    }

    private function send($tracker)
    {
        //$to = 'abouhamadi@yahoo.fr';
        $to = $tracker[0]->email;
        $form = config('mail.from');
        $params = [
                    'to' => $tracker[0]->email,
                    //'to' => 'abouhamadi@yahoo.fr',
                    'from' => $form['address'],
                    'subject'   => "CADORIM: documents d'identité",
                    'title'     => 'CADORIM',
                    "body"  => '',
                    'firstName' => $tracker[0]->username,
                    'tracker'   => $tracker[0],
                    'idUser'    => $tracker[0]->id,
                    'template'  => 'email.proofReminder',
                ];

        \Mail::to($to)->send(new \App\Mail\NewMail($params));
        if (Mail::failures())
            return 0;

        return 1;
    }
}
