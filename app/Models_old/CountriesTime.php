<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountriesTime extends Model
{

    protected $table = 'countries_times';
    protected $fillable = ['country_code', 'day_from', 'day_to'];

}
