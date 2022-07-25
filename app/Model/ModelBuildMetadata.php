<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ModelBuildMetadata extends Model
{
    protected $table = 'UM_ModelBuild_Metadata';
    public $timestamps = false;
}
