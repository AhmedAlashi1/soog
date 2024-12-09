<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Times extends Model
{
    use HasFactory;

    protected $table = "times";

    protected $fillable = ['title_en' , 'title_ar' , 'status'];

    public function orders()
    {
        return $this->hasMany(Order::class , 'time_id' , 'id');
    }

}
