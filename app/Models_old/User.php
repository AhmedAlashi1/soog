<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use GeniusTS\Roles\Traits\HasRoleAndPermission;
//use GeniusTS\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Authenticatable
{
    use Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_name','is_active','permissions','type','google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
            'remember_token',
    ];

    // public function getCreatedAtAttribute($date)
    // {
    //     $geoIp = \GeoIP::getLocation();
    //     $dtz = new \DateTimeZone($geoIp['timezone']);
    //     $time_in_sofia = new \DateTime('now', $dtz);
    //     $offset = $dtz->getOffset( $time_in_sofia ) / 3600;
    //     return $date.' (' . "GMT" . ($offset < 0 ? $offset : "+".$offset) . ')';
    // }

    public function messages()
    {
        return $this->hasMany('\App\Models\Messages');
    }

    public function roles(){
        return $this->belongsToMany(Role::class , 'role_user');
    }

    public function permissions($permissions)
    {
            foreach($this->roles as $role){
                if(in_array($permissions ,  $role->permissions)){
                    return true;
                }
                else{
                    return false;
                }
            }
    }
}
