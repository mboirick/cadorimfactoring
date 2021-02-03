<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reponse_sondage extends Model
{
    //
    protected $table = 'reponse_sondages';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}
