<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpCompleted extends Model
{
    protected $table = 'UL_RepCmp_Completed';
    public $timestamps = false;
}
