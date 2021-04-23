<?php

namespace App;

use App\Model\Attachment;
use App\Model\Permissions;
use App\Model\Profile;
use App\Model\UserAuthenticate;
use App\Model\UserDetail;
use App\Model\UserMeta;
use App\Model\UserRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    //use SoftDeletes;

    protected $table = 'User_Detail';

    protected $rememberTokenName = false;
    protected $primaryKey = 'User_ID';
    protected $dateFormat = 'Y-m-d H:i:s';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','is_active'
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function profile(){
        return $this->hasOne(Profile::class,'profile_id','profile_id');
    }

    public function permissions(){
        return $this->hasMany(Permissions::class,'profile_id','profile_id');
    }

    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

    public static function userInitial(string $name)
    {
        $words = explode(' ', $name);
        return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    }

    public function getNameEmailAttribute(){
        return "{$this->first_name} {$this->last_name}  ({$this->details->company_name})";
    }

    public function details(){
        return $this->hasOne(UserDetail::class,'u_dataid','u_dataid');
    }

    public function authenticate(){
        return $this->hasOne(UserAuthenticate::class,'User_ID','User_ID');
    }

    public function umeta(){
        return $this->hasMany(UserMeta::class,'u_dataid','u_dataid');
    }

    public function utheme(){
        return $this->hasOne(UserMeta::class,'u_dataid','u_dataid')->where('meta_key','=','ACTIVE_THEME');
    }

    public function attachment(){
        return $this->hasOne(Attachment::class,'u_dataid','u_dataid')->where('type','=','user');
    }

    public function getProfileImageAttribute(){
        $dummy_imag = '/ds_attachments/users/dummy_man.png';

        return isset($this->attachment) ? '/ds_attachments/users/'.$this->FullName.'/'.$this->attachment->attachment_url : $dummy_imag;
    }

    public function getProfileImageThumbAttribute(){
        $dummy_imag = '/ds_attachments/users/dummy_man.png';
        return isset($this->attachment) ? '/ds_attachments/users/'.$this->FullName.'/thumb/'.$this->attachment->attachment_url : $dummy_imag;
    }

    public function getIsSuperAdminAttribute(){
        return ($this->profile->profile_id == 'PR1568809591') ? true : false;
    }
}
