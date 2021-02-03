<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sondage extends Model
{
    protected $table = 'sondages';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}
