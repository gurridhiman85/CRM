<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepCmpSFTP extends Model
{
    protected $table = 'UL_RepCmp_SFTP';
    public $timestamps = false;
}
