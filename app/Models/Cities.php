<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $fillable = ['governorat_id' , 'title_en' , 'title_ar' , 'status' , 'delivery_cost' , 'far_zone' , 'order_limit' , 'status' , 'deleted_at'];

    public function app_users()
    {
        return $this->hasMany(App_users::class , 'city_id' , 'id');
    }

    public function Governorates()
    {
        return $this->belongsTo(Governorates::class , 'governorat_id' , 'id');
    }

}
