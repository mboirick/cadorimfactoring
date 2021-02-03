<?php

namespace App\Repositories\Backend;

use App\Models\SendPolls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SendPollsRepository
{
    public function getAll()
    {
        return SendPolls::select('id_sondage')
                        ->groupBy('id_sondage')
                        ->paginate(20);
    }

    public function getCountAll()
    {
        return SendPolls::count();
    }

    public function getCountByAnswered()
    {
        return SendPolls::where('repondu','1')->count();
    }

    public function updateByEmail($email, array $params)
    {
        return SendPolls::where('email', $email)->update($params);
    }
}
