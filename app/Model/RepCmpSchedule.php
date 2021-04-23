<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpSchedule extends Model
{
    protected $table = 'UL_RepCmp_Schedules';
    public $timestamps = false;

    public function rpschedule()
    {
        return $this->hasOne(RepCmpStatus::class,'row_id','sch_status_id')->where('t_type','A');
    }
}
