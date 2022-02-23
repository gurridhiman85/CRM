<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UAFieldMapping extends Model
{
    protected $table = 'UA_Field_Mapping';
    public $timestamps = false;

    protected $primaryKey = 'RowID';
}
