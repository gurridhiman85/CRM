<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpSchStatusMapping extends Model
{
    protected $table = 'UL_RepCmp_Sch_status_mapping';
    public $timestamps = false;
    protected $primaryKey = 'row_id';
}
