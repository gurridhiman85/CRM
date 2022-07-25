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

    public function sftp()
    {
        return $this->belongsTo(RepCmpSFTP::class,'ftp_tmpl_id','row_id');
    }

    public function rpschstatusmap(){
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchStatusMapping::class,
            'sch_id',
            'row_id',
            'row_id',
            'sch_status_id'
        )->where('UL_RepCmp_Sch_status_mapping.t_type','A')->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id');
    }

    public function ccschstatusmap(){
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchStatusMapping::class,
            'sch_id',
            'row_id',
            'row_id',
            'sch_status_id'
        )->where('UL_RepCmp_Sch_status_mapping.t_type','C')->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id');
    }

    public function moschstatusmap(){
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchStatusMapping::class,
            'sch_id',
            'row_id',
            'row_id',
            'sch_status_id'
        )->where('UL_RepCmp_Sch_status_mapping.t_type','M')->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id');
    }

    public function prschstatusmap(){
        return $this->hasManyThrough(
            RepCmpStatus::class,
            RepCmpSchStatusMapping::class,
            'sch_id',
            'row_id',
            'row_id',
            'sch_status_id'
        )->where('UL_RepCmp_Sch_status_mapping.t_type','P')->orderByDesc('UL_RepCmp_Sch_status_mapping.row_id');
    }
}
