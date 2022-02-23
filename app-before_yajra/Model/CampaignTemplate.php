<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class CampaignTemplate extends Model
{
    protected $table = 'UC_Campaign_Templates';
    public $timestamps = false;

    public function rpcompleted()
    {
        return $this->hasOne(RepCmpCompleted::class,'camp_id','t_id')->where('t_type','C');
    }

    public function rpmeta()
    {
        return $this->hasOne(RepCmpMetaData::class,'CampaignID','t_id')->where('type','C');
    }

    public function rpshare()
    {
        return $this->hasOne(RepCmpShare::class,'camp_tmpl_id','t_id')->where('t_type','C');
    }

    public function rpemail()
    {
        return $this->hasOne(RepCmpEmail::class,'camp_tmpl_id','t_id')->where('t_type','C');
    }

    public function rpschedule()
    {
        return $this->hasOne(RepCmpSchedule::class,'camp_tmpl_id','row_id')->where('t_type','C');
    }

    /*public function rpstatus(){
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchedule::class,
            'camp_tmpl_id', // Foreign key on the cars table...
            'row_id', // Foreign key on the owners table...
            'row_id', // Local key on the mechanics table...
            'sch_status_id' // Local key on the cars table...
        )->where('UL_RepCmp_Status.t_type','C');
    }*/
}
