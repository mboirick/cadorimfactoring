<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billdepositwithdrawal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'billdepositwithdrawal';

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
    protected $fillable = ['id_client', 'id_user_debtor','amount', 'reason', 'type_operation'];
}
