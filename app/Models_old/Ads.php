<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $table = "ads";

    protected $fillable = ['title' , 'url' , 'layout' , 'lauout_title' ,
    'image' , 'days' , 'cost' ,
    'status' , 'cat_id' , 'product_id' , 'multi_product_id'];

    protected $casts = [
        'multi_product_id' => 'array'
    ];

    public function categories()
    {
        return $this->belongsTo(Categories::class , 'cat_id'  , 'id');
    }

    public function Products()
    {
        return $this->belongsTo(Clothes::class , 'product_id'  , 'id');
    }
}
