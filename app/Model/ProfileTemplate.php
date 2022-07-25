<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ProfileTemplate extends Model
{
    protected $table = 'UP_Profile_Templates';
    public $timestamps = false;

    public function rpcompleted()
    {
        return $this->hasOne(RepCmpCompleted::class,'camp_id','t_id')->where('t_type','P');
    }

    public function rpmeta()
    {
        return $this->hasOne(RepCmpMetaData::class,'CampaignID','t_id')->where('type','P');
    }

    public function rpshare()
    {
        return $this->hasOne(RepCmpShare::class,'camp_tmpl_id','t_id')->where('t_type','P');
    }

    public function rpemail()
    {
        return $this->hasOne(RepCmpEmail::class,'camp_tmpl_id','t_id')->where('t_type','P');
    }

    public function rpschedule()
    {
        return $this->hasOne(RepCmpSchedule::class,'camp_tmpl_id','row_id')->where('t_type','P');
    }

    public function RepCmpSchStatusMappingRunning(){
        return $this->hasManyThrough(RepCmpSchStatusMapping::class, RepCmpSchedule::class, 'camp_tmpl_id','sch_id','row_id','row_id')
            ->where('UL_RepCmp_Sch_status_mapping.t_type','P')
            ->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id')
            ->leftjoin('UL_RepCmp_Status','UL_RepCmp_Sch_status_mapping.sch_status_id','=','UL_RepCmp_Status.row_id')->select('UL_RepCmp_Status.*');
    }

    public function RepCmpSchStatusMappingScheduled(){
        return $this->hasManyThrough(RepCmpSchStatusMapping::class, RepCmpSchedule::class, 'camp_tmpl_id','sch_id','row_id','row_id')
            ->where('UL_RepCmp_Sch_status_mapping.t_type','P')
            ->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id')
            ->leftjoin('UL_RepCmp_Status','UL_RepCmp_Sch_status_mapping.sch_status_id','=','UL_RepCmp_Status.row_id')->select('UL_RepCmp_Status.*');
    }

    public function RepCmpSchStatusMappingCompleted(){
        return $this->hasManyThrough(RepCmpSchStatusMapping::class, RepCmpSchedule::class, 'camp_tmpl_id','sch_id','row_id','row_id')
            ->where('UL_RepCmp_Sch_status_mapping.t_type','P')
            ->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id')
            ->leftjoin('UL_RepCmp_Status','UL_RepCmp_Sch_status_mapping.sch_status_id','=','UL_RepCmp_Status.row_id')->select('UL_RepCmp_Status.*');
    }

   /* public function rpstatus(){
        //return $this->hasOneThrough(RepCmpStatus::class,RepCmpSchedule::class,'camp_tmpl_id','row_id','row_id','');
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchedule::class,
            'camp_tmpl_id', // Foreign key on the cars table...
            'row_id', // Foreign key on the owners table...
            'row_id', // Local key on the mechanics table...
            'sch_status_id' // Local key on the cars table...
        );
    }*/
}
