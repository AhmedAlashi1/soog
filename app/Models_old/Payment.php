<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = "payment";

    protected $fillable = ['title_en' , 'title_ar' , 'slug' , 'status'];

    public function orders()
    {
        return $this->hasMany(Order::class , 'payment_id' , 'id');
    }

}
