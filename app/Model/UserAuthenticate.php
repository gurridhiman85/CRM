<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAuthenticate extends Model
{
    protected $timestamp = false;
    protected $table = 'User_Authenticate';
}
