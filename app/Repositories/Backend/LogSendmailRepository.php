<?php


namespace App\Repositories\Backend;


use App\Models\LogSendmail;

class LogSendmailRepository
{
    public function insert(array $params)
    {
        return LogSendmail::insert(
            ['email' => $params['email'], 'subject' => $params['subject'],
                'datesend' => $params['datesend'],'state' => $params['state']]
        );
    }
}