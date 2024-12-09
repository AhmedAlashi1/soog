<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

//use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Clothes extends Model
{
    use HasFactory;

    protected $table = "clothes";

    protected $fillable = ['title_ar' , 'title_en' , 'note_en' ,
    'note_ar' , 'image' , 'price' ,'quntaty' , 'cat_id' , 'user_id',
        'status' , 'type' , 'price_after','international','weight','keywords' , 'order_limit',
        'end_date' , 'lat' , 'lng','confirm','sort_order','number_rooms' , 'swimming_pool' , 'Jim',
        'year' , 'cere' , 'number_cylinders','working_condition','brand_id','salary' , 'educational_level_id' , 'specialization_id',
        'biography' , 'animal_type_id' , 'fashion_type_id','location','subjects_id','country_id' , 'governorates_id' , 'views',
        'chat' , 'whatsApp' , 'email','sms','call','sale'
    ];


    protected $spatialFields = [
        'from_location'
    ];
    protected $appends = ['block_user'];

    public static $rules = [
        'title_ar' => 'required|min:3',
        'title_en' => 'required|min:3',
        'note_ar' => 'required|min:3',
        'note_en' => 'required|min:3',
        'price' => 'required|numeric',
        'image' => 'required',
    ];
    public function getBlockUserAttribute()
    {
        $user = null;
        try{
            $user = JWTAuth::parseToken()->authenticate()->id;
        }catch (JWTException $e) {

        }

        if ($user == null){
            return 0;
        }else{
            $order=BlockUser::where('user_id',$this->user->id)->where('customer_id',$user)->count();
            return $order;
        }

    }
    public function categories()
    {
        return $this->belongsTo('\App\Models\Categories' , 'cat_id'  , 'id');
    }

    public function user()
    {
        return $this->belongsTo(AppUser::class , 'user_id'  , 'id');
    }

    public function charityImages()
    {
        return $this->hasMany('\App\Models\CharityImage','charity_id','id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class , 'product_id ' , 'id');
    }

    public function favorites()
    {
        return $this->hasMany('\App\Models\Fav', 'charity_id', 'id');
    }
    public function pieces()
    {
        return $this->hasMany(Pieces::class, 'clothe_id', 'id');
    }
    public function fixedAds()
    {
        return $this->hasMany('\App\Models\FixedAds','clothes_id','id');
    }
    public function country()
    {
        return $this->belongsTo('\App\Models\Country','country_id','id');
    }
    public function governorates()
    {
        return $this->belongsTo('\App\Models\Governorates','governorates_id','id');
    }

}
