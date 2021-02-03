<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonne extends Model
{
     /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function commandes()
    {
        return $this->hasMany(Paiement::class ,'payer_email', 'email');
    }
}
