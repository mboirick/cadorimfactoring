<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    protected $guarded = [];
}
