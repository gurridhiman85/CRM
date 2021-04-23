<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ReportTemplate extends Model
{
    protected $table = 'UR_Report_Templates';
    public $timestamps = false;

    public function rpcompleted()
    {
        return $this->hasOne(RepCmpCompleted::class,'camp_id','t_id')->where('t_type','A');
    }

    public function rpmeta()
    {
        return $this->hasOne(RepCmpMetaData::class,'CampaignID','t_id')->where('type','A');
    }

    public function rpshare()
    {
        return $this->hasOne(RepCmpShare::class,'camp_tmpl_id','t_id')->where('t_type','A');
    }

    public function rpschedule()
    {
        return $this->hasOne(RepCmpSchedule::class,'camp_tmpl_id','row_id')->where('t_type','A');
    }

    public function rpstatus(){
        //return $this->hasOneThrough(RepCmpStatus::class,RepCmpSchedule::class,'camp_tmpl_id','row_id','row_id','');
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchedule::class,
            'camp_tmpl_id', // Foreign key on the cars table...
            'row_id', // Foreign key on the owners table...
            'row_id', // Local key on the mechanics table...
            'sch_status_id' // Local key on the cars table...
        );
    }
}
