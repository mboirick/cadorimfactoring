<?php

namespace App\Repositories\Backend;

use App\Models\PollResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PollResponseRepository
{
    public function create($params)
    {
        return PollResponse::create($params);;
    }

    public function getByIdPoll($idPoll)
    {
        return PollResponse::leftJoin('abonnes', 'reponse_sondages.id_client', '=', 'abonnes.email')
                   ->where('id_sondage',$idPoll)
                   ->select('*')
                   ->orderBy('reponse_sondages.id')
                   ->paginate('24');
    }

    public function getByEmailName($email, $name)
    {
        return PollResponse::select('id')
                    ->where('id_client', $email)
                    ->where('id_sondage', $name)
                    ->first(); 
    }
}
