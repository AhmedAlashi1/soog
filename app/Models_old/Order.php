<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{


    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'orders';
    protected $appends = ['total_wallet'];

    public function getTotalWalletAttribute($value)
    {
        return $this->attributes['total_cost'] - $this->attributes['credit'];
    }
    public function orderStatus()
    {
        return $this->hasMany('\App\Models\Status','order_id','id');
    }

    public function pieces()
    {
        return $this->hasMany('\App\Models\Pieces','order_id','id');
    }
    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser');
    }
    public function cat()
    {
        return $this->belongsTo('\App\Models\Categories');
    }
    public function driver()
    {
        return $this->belongsTo('\App\Models\Drivers');
    }
    public function payment()
    {
        return $this->belongsTo('\App\Models\Payment');
    }
    public function delivery()
    {
        return $this->belongsTo('\App\Models\Delivery');
    }
    public function time()
    {
        return $this->belongsTo('\App\Models\Times');
    }
    public function deliveryTypeTitle()
    {
        return $this->belongsTo(DeliveryTypes::class,'delivery_type','id');
    }
    public function address()
    {
        return $this->belongsTo('\App\Models\Charge');
    }
    public function promo()
    {
        return $this->belongsTo('\App\Models\Coupons','promo_code','code');
    }

    public function products()
    {
        return $this->belongsToMany('\App\Models\Clothes', 'orders_pices', 'order_id', 'clothe_id', 'id', 'id');
    }
}
