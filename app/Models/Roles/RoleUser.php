<?php

namespace App\Models\Roles;

class RoleUser extends \App\Models\BaseModel
{

    protected $table = 'role_user';
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function roles()
    {
        return $this->belongsToMany('Bican\Roles\Models\Role');
    }

}
