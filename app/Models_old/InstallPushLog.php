<?php

Namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class InstallPushLog extends Moloquent
{

    protected $collection = 'install_push_log';
    protected $guarded = ['id'];
    protected $connection = 'mongodb';
    public $timestamps = false;

    public function getCreatedAtAttribute($date)
    {
        return date('Y-m-d H:i:s', $date);
    }

    public function message()
    {
        return $this->belongsTo('App\Models\InstallPush');
    }

}
