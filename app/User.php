<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;
    
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAgencyOPerateur()
    {
        return  in_array($this->user_type, ['gaza', 'mauritanie', 'ouldyenja', 'selibaby', 'tachout']);
    }

    public function isAdminOPerateur()
    {
        return  in_array($this->user_type, ['operateur']) && $this->email == 'saleck@cadorim.com';
    }
    
}
