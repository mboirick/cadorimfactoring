<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

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
    protected $fillable=['coupon_code','amount','amount_type','expiry_date','status'];
}
