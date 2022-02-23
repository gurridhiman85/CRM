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

    

    public function authenticate(){
        return $this->hasOne(UserAuthenticate::class,'User_ID','User_ID');
    }

   
}
