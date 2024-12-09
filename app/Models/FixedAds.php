<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class FixedAds extends Model
{
    use HasFactory;

    protected $table = "fixed_ads";
    public $timestamps = true;

    protected $fillable = [
        'cat_id','end_at','start_at','clothes_id','packages_id','status','home','repeat_duration'

    ];

    public function clothes()
    {
        return $this->belongsTo(Clothes::class , 'clothes_id' , 'id');
    }
    public function cat()
    {
        return $this->belongsTo(Categories::class , 'cat_id' , 'id');
    }
    public function packages()
    {
        return $this->belongsTo(Packages::class , 'packages_id' , 'id');
    }


}
