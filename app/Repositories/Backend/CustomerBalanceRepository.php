<?php


namespace App\Repositories\Backend;


use App\Models\CustomerBalance;

class CustomerBalanceRepository
{

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