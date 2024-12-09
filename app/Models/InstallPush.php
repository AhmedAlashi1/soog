<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallPush extends Model
{

    protected $table = 'notification_install_push';
    protected $guarded = ['id'];

    public function country()
    {
        return $this->belongsTo('App\Models\Countries','country_code','country_code');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\InstallPushLog','id', 'message_id');
    }

}
