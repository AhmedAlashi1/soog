<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{


    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'notification_log';
    protected $casts = [
        'multi_product_id' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser');
    }
    public function driver()
    {
        return $this->belongsTo('\App\Models\Drivers');
    }

    public function categories()
    {
        return $this->belongsTo('\App\Models\Categories' , 'cat_id'  , 'id');
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\Clothes' , 'product_id' , 'id');
    }

}
