<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ModelScoreMetadata extends Model
{
    protected $table = 'UM_ModelScore_Metadata';
    public $timestamps = false;
    public $primaryKey = 'ModelScoreID';
}
