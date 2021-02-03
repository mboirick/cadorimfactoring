<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CREATED_AT = 'date_commande';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commandes';

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
    protected $fillable = ['id_commande', 'mail_commande', 'nom_produit', 'prix_produit', 'quantite', 'date_commande', 'date_validation'];

}
