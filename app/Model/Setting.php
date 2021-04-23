<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'idockers_settings';

    const USERS = 20;
    const PROFILE_MASTER = 21;
    const DEPARTMENT = 22;
    const DESIGNATION = 23;
}
