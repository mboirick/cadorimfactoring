<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agencyaddress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adresse_agences';

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
    protected $fillable = ['ville', 'quartier', 'id_agence'];
}
