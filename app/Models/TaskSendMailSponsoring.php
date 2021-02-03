<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSendMailSponsoring extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_sendmailparrainage';

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
    protected $fillable = ['sendall', 'datestart', 'dateend', 'numbre', 'dateexcute', 'state'];
}
