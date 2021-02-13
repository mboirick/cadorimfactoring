<?php


namespace App\Repositories\Backend;


use App\Models\Invoices;

class InvoicesRepository
{
    public function  getByIdPay($idPay)
    {
        return Invoices::where('id_paiement', '=', $idPay)->get();
    }

    public function  create(array $params)
    {
        return Invoices::create($params);
    }
}