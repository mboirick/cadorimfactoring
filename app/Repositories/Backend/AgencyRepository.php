<?php

namespace App\Repositories\Backend;

use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AgencyRepository
{
    /**
     * @param array $params
     * @return Agency|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $params)
    {
        if(!isset($params['idClientDebiteur']))
            $params['idClientDebiteur'] =  Auth::user()->email;

        return Agency::create([
            "id_client" => $params['idClient'],
            "id_client_debiteur" => $params['idClientDebiteur'],
            'solde_avant_mru' => $params['solde_avant_mru'],
            'solde_mru' => $params['solde_mru'],
            'montant_mru' => $params['montant_mru'],
            "motif" => $params['motif'],
            "type_opperation" => $params['type_opperation']

        ]);
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return DB::table('users')
            ->leftJoin('agences', 'agences.id_client', '=', 'users.id_client')
            ->where('agences.indice', '=', '1')
            ->where('users.user_type', '=', 'operateur')
            ->sum('agences.solde_mru');
    }

    /**
     * @param $index
     * @param $idClient
     * @return Agency|\Illuminate\Database\Eloquent\Model|null
     */
    public function getBalanceByIndex($index, $idClient)
    {
        return Agency::where('id_client', '=', $idClient)
            ->where('indice', '=', $index)->first();
    }

    public function getBalanceLatestByIndex($index, $idClient)
    {
        return Agency::where('id_client', '=', $idClient)
            ->where('indice', '=', $index)->latest()->first();
    }

    /**
     * @param $idClient
     * @param $index
     * @return Agency|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByIdClientAndIndex($idClient, $index)
    {
        return Agency::where('id_client', '=', $idClient)
            ->where('indice', '=', $index)
            ->first();
    }

    /**
     * @param $indexSearch
     * @param $idClient
     * @param $indexValue
     * @return bool
     */
    public function updateIndex($indexSearch, $idClient, $indexValue)
    {   return Agency::where('id_client', '=', $idClient)
                ->where('indice', '=', $indexSearch)
                ->update(['indice' => $indexValue]);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function updateBalance($params)
    {
        return Agency::where('id', $params['id'])->update([
            'montant_mru' => $params['amountRmu'],
            'solde_avant_mru' => $params['balanceAfterMru'],
            'solde_mru' => $params['balanceMru']
        ]);
    }

    /**
     * @param $idClient
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getByIdClient($idClient)
    {
        return  Agency::leftJoin('users', 'agences.id_client', '=', 'users.id_client')
            ->select('agences.*', 'users.firstname')
            ->where('agences.id_client', '=', $idClient)
            ->orderBy('agences.created_at', 'DESC')
            ->paginate(20);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getByDate($date)
    {
        return Agency::whereDate('created_at', 'like', '%' . $date . '%')
            ->select(array(
                DB::Raw('DISTINCT(id_client)'),
            ))->get();
    }

    /**
    * @param $date
    * @param $idClient
    * @param $order
    * @return mixed
    */
    public function getByIdClientAndDate($date, $idClient, $order)
    {
        return DB::table('agences as A')
            ->leftJoin('users as U', 'A.id_client', '=', 'U.id_client')
            ->where('A.id_client', '=', $idClient)
            ->whereDate('A.created_at', 'like', '%' . $date . '%')
            ->select('A.solde_avant_mru', 'A.solde_mru', 'U.email')
            ->orderBy('A.id', $order)
            ->first();
    }

    /**
    * @return mixed
    */
    public function getCount()
    {
        return Agency::count();
    }

    public function getBalanceCurrentUserByIdClientAndIndex($idClient, $index)
    {
        return Agency::where('id_client',  $idClient )
                ->where('indice', '=', $index)
                -> latest()->value('solde_mru');
    }
}
