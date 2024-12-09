<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorates extends Model
{
    use HasFactory;

    protected $table = "governorates";

    protected $fillable = ['title_en' , 'title_ar' , 'country_id' , 'status'];

    public function app_users()
    {
        return $this->hasMany(App_users::class , 'region_id' , 'id');
    }

    public function cities()
    {
        return $this->hasMany(Cities::class , 'governorat_id' , 'id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id' , 'id');
    }

}
