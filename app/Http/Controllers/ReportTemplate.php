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
        return $this->hasOne(RepCmpShare::class,'camp_tmpl_id','t_id')->where('Shared_With_User_id',Auth::user()->User_ID)->where('t_type','A');
    }
}
