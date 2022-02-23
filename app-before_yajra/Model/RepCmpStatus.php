<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpStatus extends Model
{
    protected $table = 'UL_RepCmp_Status';
    public $timestamps = false;
}
