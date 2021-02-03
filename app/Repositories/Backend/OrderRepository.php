<?php

namespace App\Repositories\Backend;

use App\Models\Order;

class OrderRepository
{
    /**
     * 
     * @param int $idOrder
     * @return array
     */
    public function getById($idOrder)
    {
        return  Order::where('id_commande', '=', $idOrder)->get();
    }
}