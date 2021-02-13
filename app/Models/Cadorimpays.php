<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cadorimpays extends Model
{
        /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cadorimpays';

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
    protected $fillable = ['id_paiement', 'id_client', 'entreprise', 'adresse', 
                            'montant_euros', 'taux_echange', 'montant_mru', 
                            'iban', 'remarque', 'reponses', 'statut', 'type_demande', 'date_limit'];

}
