<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailConfiguration extends Model
{
    protected $table = 'emails_configuration';
    public $timestamps = false;
}
