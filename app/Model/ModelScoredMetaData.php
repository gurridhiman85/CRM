<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelScoredMetaData extends Model
{
    protected $table = 'UM_ModelScore_Metadata';
    public $timestamps = false;

    public function modelscoredata()
    {
        return $this->hasOne(ModelScoredData::class,'ModelScoreID','ModelScoreID');
    }
}
