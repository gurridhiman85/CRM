<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelChart extends Model
{
    protected $table = 'UM_Model_Chart';
    public $timestamps = false;
}
