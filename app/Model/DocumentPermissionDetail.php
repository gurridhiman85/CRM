<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DocumentPermissionDetail extends Model
{
    protected $table = 'document_permissions_detail';

    public function user(){
        return $this->hasOne(User::class,'u_dataid','u_dataid');
    }
}
