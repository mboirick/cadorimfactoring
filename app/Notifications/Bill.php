<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Bill extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $pdf;

    /**
     * @var int
     */
    private $idBill;

    /**
     * @var
     */
    private $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    /**
     * Bill constructor.
     * @param $pdf
     * @param $idBill
     * @param $user
     * @param $action
     */
    public function __construct($pdf, $idBill, $user, $action)
    {
        $this->pdf      = $pdf;
        $this->idBill   = $idBill;
        $this->user     = $user;
        $this->action     = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Facture - ' . $this->action)
            ->attachData($this->pdf, $this->idBill . '.pdf', [
                'mime' => 'application/pdf',
            ])
            ->salutation('Abou');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
