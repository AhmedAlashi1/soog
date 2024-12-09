<?php

namespace App\Models\Roles;

class PermissionUser extends \App\Models\BaseModel
{

    protected $table = 'permission_user';

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function permissions()
    {
        return $this->belongsToMany('Bican\Roles\Models\Permission');
    }

}
