<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = "Item";


    protected $fillable = [
        'title_en' , 'title_ar' ,  'type'

    ];



    public static $rules = [
        'title_ar' => 'required|min:3',
        'title_en' => 'required|min:3',

    ];



}
