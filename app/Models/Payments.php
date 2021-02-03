<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paiements';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['txnid', 'payment_amount', 'phone_benef', 'payment_status', 'itemid', 'createdtime'];

    public $timestamps = false;
}
