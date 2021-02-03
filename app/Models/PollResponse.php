<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reponse_sondages';

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
    protected $fillable = ['id_sondage', 'id_client', 'id_question', 'text_question', 'response', 'repondu'];
}
