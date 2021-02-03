<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfert_table extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfert_tables';

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];
}