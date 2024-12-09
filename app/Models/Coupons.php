<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $table = "coupons";

    protected $fillable = ['code' , 'discount' , 'count_number' , 
    'end_at' , 'type' , 'percent' ,
    'use_number' , 'code_limit' , 'code_max'
    , 'status'];


    public static $rules = [
        'code' => 'required|min:3',
        'count_number' => 'required|numeric|min:3',
        'code_limit' => 'required|numeric',
        'code_max' => 'required|numeric',
        'end_at' => 'required',
        'discount' => 'required|numeric',
        // 'type' => 'required',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class , 'promo_code' , 'id');
    }

}
