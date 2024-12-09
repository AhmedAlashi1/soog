<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pieces extends Model
{


    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'orders_pices';

    public function clothe()
    {
        return $this->belongsTo('\App\Models\Clothes');
    }

    public function order()
    {
        return $this->belongsTo('\App\Models\Order');
    }

}
