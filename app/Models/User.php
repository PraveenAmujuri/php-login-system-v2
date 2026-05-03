<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'userId',
        'password',
        'last_login',
        'status',
        'remember_token'
    ];
}