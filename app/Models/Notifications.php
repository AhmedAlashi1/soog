<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{


    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'notifications';
    protected $casts = [
        'multi_product_id' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser','user_id','id');
    }
    public function sender()
    {
        return $this->belongsTo('\App\Models\AppUser','sender_id','id');
    }


}
