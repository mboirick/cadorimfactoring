<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerBalance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solde_client';

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
    protected $fillable = ['id_client', 'id_client_debiteur', 'solde_avant_euros',
        'solde_avant_mru', 'solde_euros', 'solde_mru', 'montant_euros',
        'taux', 'montant_mru', 'indice', 'motif', 'type_opperation'];
}
