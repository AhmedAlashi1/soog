<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $table = "delivery";

    protected $fillable = ['title_en' , 'title_ar' , 'cost'  , 'order_limit' , 'status'];

    public function orders()
    {
        return $this->hasMany(Order::class , 'delivery_id' , 'id');
    }

}
