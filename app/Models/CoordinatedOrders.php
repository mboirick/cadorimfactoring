<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordinatedOrders extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coordonnes_commandes';

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
    protected $fillable = ['id_commande', 'mail_exp', 'nom_exp', 'phone_exp', 
							'adress_exp', 'nom_benef', 'phone_benef', 'adress_benef', 
							'montant', 'remise', 'promo_code', 'date_commande', 
							'paiement_satus', 'tracker_status', 'frais_gaza', 
							'agence_gaza', 'point_retrait', 'gaza_confirm'];
}
