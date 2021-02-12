<?php


namespace App\Repositories\Backend;


use App\Models\CustomerBalance;

class CustomerBalanceRepository
{
    public function getFirstByIdClient($idClient)
    {
        return CustomerBalance::where('id_client', '=', $idClient)
                                ->latest()
                                ->first();
    }

    public function getByIdClient($idClient)
    {
        return CustomerBalance::where('solde_client.id_client', '=', $idClient)
            ->orderBy('solde_client.created_at', 'DESC')
            ->paginate(20);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return CustomerBalance::create([
            'id_client' => $params['idClient'],
            'id_client_debiteur' => $params['idClientDebtor'],
            'solde_avant_euros' => $params['balanceBeforeEuros'],
            'solde_avant_mru' => $params['balanceBeforeMru'],
            'solde_euros' => $params['balanceEuros'],
            'solde_mru' => $params['balanceMru'],
            'montant_euros' =>  $params['amountEuros'],
            'taux' => $params['rate'],
            'montant_mru' => $params['amountMru'],
            'motif' => $params['motif'],
            'type_opperation' => $params['typeOperation']
        ]);

    }

    public function createClient(array $params)
    {
        return CustomerBalance::create($params);
    }

    /**
     * @param $index
     * @param $idClient
     * @return mixed
     */
    public function getByIndexAndIdClient($index, $idClient)
    {
        return CustomerBalance::where('indice', '=', $index)
            ->where('id_client', '=', $idClient)
            ->first();
    }

    /**
     * @param $indexOld
     * @param $idClient
     * @param $indexNew
     * @return mixed
     */
    public function updateIndexByIdClient($indexOld, $idClient, $indexNew)
    {
        return CustomerBalance::where('indice', '=', $indexOld)
            ->where('id_client', '=', $idClient)->update([
            'indice' => $indexNew
        ]);
    }

    public function updateParamsIndexByIdClient($indexOld, $idClient, $params)
    {
        return CustomerBalance::where('indice', '=', $indexOld)
            ->where('id_client', '=', $idClient)->update($params);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBalance(array $params)
    {
        return CustomerBalance::where('id',   $params['id'])->update([
            'montant_mru' => $params['amountMru'],
            'solde_avant_mru' => $params['balanceAfterMru'],
            'solde_mru' => $params['balanceMru']
        ]);
    }
}