<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Coordonnes_commande extends Model
{
  /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function commande()
    {
        return $this->hasOne(Paiement::class ,'id_commande', 'id_commande');
    }
    
}
