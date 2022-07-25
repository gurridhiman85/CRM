<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelScoredData extends Model
{
    protected $table = 'UM_ModelScore_Data';
    public $timestamps = false;
}
