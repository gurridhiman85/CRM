<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpMetaData extends Model
{
    protected $table = 'UL_RepCmp_MetaData';
    public $timestamps = false;

    protected $primaryKey = 'RowID';
}
