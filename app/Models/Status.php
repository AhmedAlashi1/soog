<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'orders_status';

    public function orders()
    {
        return $this->hasMany(Order::class, 'order_id' , 'id');
    }

}
