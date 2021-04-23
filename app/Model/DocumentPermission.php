<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DocumentPermission extends Model
{
    protected $table = 'document_permissions';

    public function permission_detail(){
        return $this->hasMany(DocumentPermissionDetail::class,'permission_id','id');
    }

    public function user(){
        return $this->hasOne(User::class,'u_dataid','u_dataid');
    }
}
