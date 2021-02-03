<?php


namespace App\Repositories\Backend;


use App\Models\Customer;

class CustomerRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function getBalanceById($id)
    {
        return Customer::where('id',  $id)->value('solde');
    }

    /**
     * @param $id
     * @param $amount
     * @param $balance
     * @return mixed
     */
    public function updateBalanceById($id, $amount, $balance)
    {
        return Customer::where('id', '=', $id)->update(['cash_in' => $amount, 'solde' => $balance]);
    }


}