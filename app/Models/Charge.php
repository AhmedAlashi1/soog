<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{


    protected $guarded = ['id'];
    protected $connection = 'mysql';
    protected $table = 'app_users_charge';
    protected $fillable = ['user_id' , 'lat','lng','address','title','street','block','city','governate','floor','flat','building','avenue','city_id','region_id','type','notes','mobile'];

    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser','user_id','id');
    }

    public function cityData()
    {
        return $this->belongsTo(Governorates::class,'city_id','id');
    }
    public function regionData()
    {
        return $this->belongsTo(Cities::class,'region_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class , 'address_id' , 'id');
    }



}
