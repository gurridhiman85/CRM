<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpStatus extends Model
{
    protected $table = 'UL_RepCmp_Status';
    public $timestamps = false;
    protected $primaryKey = 'row_id';

    public function schedule_map(){
        return $this->hasOne(RepCmpSchStatusMapping::class,'sch_status_id','row_id');
    }
}
