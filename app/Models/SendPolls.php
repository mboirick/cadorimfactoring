<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SendPolls extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'envoie_sondages';

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
    protected $fillable = ['email', 'id_sondages', 'repondu'];

}
