<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'abonnes';

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
    protected $fillable = ['genre', 'reset_at', 'password', 'confirmation_token', 'prenom',
        'email', 'adress', 'ville', 'code_postal', 'pays_residence',
        'date_naissance', 'type_doc', 'numero_doc', 'date_emission',
        'date_expiration', 'document', 'confirmed_at', 'reset_token',
        'phone', 'username', 'kyc', 'unique_id', 'id_parrain' ];
}
