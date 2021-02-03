<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envoie_sondage extends Model
{
    protected $table = 'envoie_sondages';
    
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}
