<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpEmail extends Model
{
    protected $table = 'UL_RepCmp_Email';
    public $timestamps = false;
}
