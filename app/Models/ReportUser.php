<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUser extends Model
{
    protected $table = 'report_user';
        protected $fillable = ['customer_id','user_id','message'];
    protected $dates = ['created_at','updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo('\App\Models\AppUser');
    }

    public function user()
    {
        return $this->belongsTo('\App\Models\AppUser');
    }
}
