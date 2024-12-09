<?php

namespace App\Models;

class Country extends BaseModel
{
    protected $table = 'country';

    public $timestamps = false;

    protected $fillable = ['title_en' , 'title_ar' , 'status' , 'coin_price' , 'slug' ,
        'coin_name' ,'coin_name_en', 'image','phone_code' ,'regus' ,'first_kg' ,'after_first_kg' ,'weight'];
}
