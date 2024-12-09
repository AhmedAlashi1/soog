<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follow';
    protected $fillable = ['follow','followers'];
    protected $dates = ['created_at','updated_at'];


    public function follows()
    {
        return $this->belongsTo(AppUser::class,'follow','id');

    }

    public function follower()
    {
        return $this->belongsTo(AppUser::class,'followers','id');


    }
}
