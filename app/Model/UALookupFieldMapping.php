<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UALookupFieldMapping extends Model
{
    protected $table = 'UA_Lookup_Field_Mapping';
    public $timestamps = false;

    protected $primaryKey = 'RowID';
}
