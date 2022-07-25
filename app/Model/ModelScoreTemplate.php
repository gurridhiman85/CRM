<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ModelScoreTemplate extends Model
{
    protected $table = 'UM_ModelScore_Templates';
    public $timestamps = false;

    public function rpcompleted()
    {
        return $this->hasOne(RepCmpCompleted::class,'camp_id','t_id')->where('t_type','M');
    }

    public function modelscoremetadata()
    {
        return $this->hasOne(ModelScoredMetaData::class,'ModelScoreID','t_id');
    }

    public function rpshare()
    {
        return $this->hasOne(RepCmpShare::class,'camp_tmpl_id','t_id')->where('t_type','M');
    }

    public function rpemail()
    {
        return $this->hasOne(RepCmpEmail::class,'camp_tmpl_id','t_id')->where('t_type','M');
    }

    public function rpschedule()
    {
        return $this->hasOne(RepCmpSchedule::class,'camp_tmpl_id','row_id')->where('t_type','M');
    }

    public function momodel()
    {
        return $this->hasOne(ModelBuildMetadata::class,'ModelBuildID','ModelBuildID');
    }

}
