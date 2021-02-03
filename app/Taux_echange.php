<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taux_echange extends Model
{
  /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taux_echange';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}
