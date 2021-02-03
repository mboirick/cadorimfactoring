<?php


namespace App\Repositories\Backend;


use App\Models\Billdepositwithdrawal;
use App\Models\Transactionstatistics;
use Illuminate\Support\Facades\Auth;

class BilldepositwithdrawalRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Billdepositwithdrawal::where('id', '=', $id)
            ->orderBy('id', 'DESC')
            ->get();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return  Billdepositwithdrawal::create([
            "id_client" => $params['idClient'],
            "id_user_debtor" => $params['idUserDebtor'],
            'amount' => $params['amount'],
            'reason' => $params['reason'],
            'type_operation' => $params['typeOperation'],
        ])->id;
    }
}