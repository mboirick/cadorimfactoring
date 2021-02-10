<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Atlpay extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stat_atlpay';

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
    protected $fillable = ['date', 'nbr_transaction', 'somme_brut', 'frais_atl', 'somme_net'];

}
