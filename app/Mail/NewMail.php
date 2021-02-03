<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(!isset($this->details['tracker']))
            $this->details['tracker'] = [];

        if(!isset($this->details['idUser']))
            $this->details['idUser'] = '';

        if(!isset($this->details['orders']))
            $this->details['orders'] = '';

        if(!isset($this->details['reason']))
            $this->details['reason'] = '';

        return $this->subject($this->details['subject'])
                    ->view($this->details['template'],  [
                                                            'firstName' => $this->details['firstName'],
                                                            'tracker'   => $this->details['tracker'],
                                                            'idUser'    => $this->details['idUser'],
                                                            'orders'    => $this->details['orders'],
                                                            'to'        => $this->details['to'],
                                                            'reason'        => $this->details['reason'],
                                                        ])
                    ->to($this->details['to'])
                    ->from($this->details['from'], 'Cadorim');
    }
}