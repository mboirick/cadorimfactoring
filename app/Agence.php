<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agences';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}