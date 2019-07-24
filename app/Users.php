<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function roles(){
        return $this->hasOne('App\Roles','id','rol');
    }

    public function referidos(){
        return $this->hasMany('App\Users','referido','id');
    }

    public function comprados(){
        return $this->hasMany('App\EventosVenta','usuario','id');
    }

    public function myReferidos(){
        return $this->hasOne('App\Users','id','referido');
    }
}
