<?php

namespace App\Repositories\Backend;

use Illuminate\Http\Request;
use App\Models\Cadorimpays;

class CadorimpaysRepository
{
    public function getByIdPay($idPay)
    {
        return Cadorimpays::where('id_paiement', '=', $idPay)->first();
    }

    public function UpdateByIdPay($idPay, array $params)
    {
        return Cadorimpays::where('id_paiement', '=', $idPay)->update($params);
    }

    public function getIdByStatus($status)
    {
        return Cadorimpays::leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
                        ->select('cadorimpays.*', 'users.firstname')
                        ->where('cadorimpays.statut', '=', '0')
                        ->orderBy('cadorimpays.created_at', 'DESC')
                        ->paginate(20);
    }

    public function getIdByStatusIdPay($status, $idPay)
    {
        return Cadorimpays::leftJoin('users', 'cadorimpays.id_client', '=', 'users.id_client')
            ->select('cadorimpays.*', 'users.firstname', 'users.email')
            ->where('id_paiement', '=', $idPay )
            ->where('statut', '=', '0')
            ->orderBy('created_at', 'DESC')
            ->paginate(20);
    }
}
