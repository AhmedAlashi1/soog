<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharityImage extends Model
{
    protected $table='stock_images';
    protected $fillable = ['title','charity_id','image'];
    protected $dates = ['created_at','updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charity()
    {
        return $this->belongsTo('\App\Models\Clothes');
    }
}
