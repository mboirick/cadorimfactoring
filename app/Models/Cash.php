<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cache_tables';

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
    protected $fillable = ['solde', 'solde_apres', 'solde_avant', 'montant_euro', 'montant', 'id_client', 'expediteur', 'nom_benef', 'phone_benef', 'code_confirmation', 'operation', 'invoices'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['statut' => 'int'];
}
