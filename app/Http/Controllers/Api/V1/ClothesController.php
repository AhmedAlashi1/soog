<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PusherApp;
use App\Models\Categories;
use App\Models\Charge;
use App\Models\CharityImage;
use App\Models\Cities;
use App\Models\Clothes;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Delivery;

use App\Models\Fav;
use App\Models\FixedAds;
use App\Models\Follow;
use App\Models\Governorates;
use App\Models\Item;
use App\Models\Order;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\ClothesRepository;
use App\Repositories\SliderRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\ContactRepository;
use App\Repositories\FavRepository;
use App\Repositories\AdsRepository;
use App\Repositories\AppUsersChargeRepository;
use App\Repositories\DeliveryRepository;
use App\Repositories\PaymentRepository;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Illuminate\Support\Facades\DB;

class ClothesController extends ApiController
{

    use Functions;
    private $repo;
    private $cat;
    private $contact;
    private $fav;
    private $ads;
    private $address;
    private $dev;
    private $pay;
    private $slider;
    public function __construct(Request $request, ClothesRepository $repo,CategoriesRepository $cat,ContactRepository $contact, FavRepository $fav,AdsRepository $ads,AppUsersChargeRepository $address,DeliveryRepository $dev,PaymentRepository $pay,SliderRepository $slider)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->cat = $cat;
        $this->contact = $contact;
        $this->fav = $fav;
        $this->ads = $ads;
        $this->address = $address;
        $this->dev = $dev;
        $this->pay = $pay;
        $this->slider = $slider;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request ,$cat_id)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }


        $country=$request->header('country');
        if ($country){
            $country=$request->header('country');

        }else{
            $country=3;
        }

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;



            $now=Carbon::now()->format('Y-m-d H:i:s');
            $fixed_ad=FixedAds::query();
            $fixed_ad->with('clothes.favorites','clothes.user','clothes.country','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('packages' ,function ($q){
                    $q->where('type',1);
                })
                ->whereHas('clothes' ,function ($qq) use ($country){
                    $qq->where('country_id',$country);
                })
                ->where('status','1')
                ->whereIn('home', ['2','3'])
                ->orderBy('created_at','desc');



            $where_obj = Clothes::query();
            $where_obj->where('country_id',$country);
            $where_obj->where('status','1');
            $where_obj->where('confirm','1');

            $categories=Categories::where(['status'=>'1','parent_id' => $cat_id])->orderBy('sort_order','asc')->get();


        if($request->input('cat_id')) {


            $where_obj->where('cat_id', $request->input('cat_id'));

            $fixed_ad->where('cat_id', $request->input('cat_id'));

        }
        else{
            $parent_id=Categories::where(['status'=>'1','parent_id' => $cat_id])->pluck('id')->toArray();
            $where_obj->WhereIn('cat_id',$parent_id);
            $fixed_ad->WhereIn('cat_id', $parent_id);

        }

        if($request->input('keyword')){
                $where_obj->where(function ($query)use ($request){
                    $query->where('title_ar','like','%'.$request->input('keyword').'%')
                        ->orwhere('title_en','like','%'.$request->input('keyword').'%')
                        ->orwhere('keywords','like','%'.$request->input('keyword').'%')
                    ;
                });
            }
        if ($request->input('lat') and $request->input('lng')){
            $lat = $request->input('lat') ? $request->lat : '';
            $lng = $request->input('lng') ? $request->lng : '';
//            $where_obj->whereRaw('6371 * acos( cos( radians(40.6591158) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-73.7841042,14) ) + sin( radians(40.6591158) ) * sin( radians( lat ) ) ) )  < 1');
            $where_obj->selectRaw("*,(st_distance_sphere( POINT(".$lng.",".$lat.") ,  point(lng, lat))/1000) as distance")
                ->havingRaw("distance < 100000")
                ->orderBy("distance");


        }else{
            $where_obj->orderBy('created_at','desc');
        }


        $fixed_ads=$fixed_ad->limit(9)->get(['*']);
        $where_obj->with('country');
        $paginate = $where_obj->whereHas('categories')->paginate($length);

        $d['data'] = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');

        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['note'] = $row->$note;
            $d['data'][$k]['end_date'] = $row->end_date;
            $d['data'][$k]['price'] = $row->price;
            $d['data'][$k]['cat_id'] = $row->cat_id;
            $d['data'][$k]['cat_name'] = $row->categories->$title;
            $d['data'][$k]['lat'] = $row->lat;
            $d['data'][$k]['lng'] = $row->lng;

            $d['data'][$k]['country'] = ($row->country) ? $row->country->$title : null;
            $d['data'][$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

            $d['data'][$k]['views'] = $row->views;
            $d['data'][$k]['user_id'] = $row->user->id;
            $d['data'][$k]['user_email'] = $row->user->email;
            $d['data'][$k]['user_mobile_number'] = $row->user->mobile_number;
            $d['data'][$k]['user_whats_number'] = $row->user->whats_number;

            $d['data'][$k]['sale'] = $row->sale == 1 ?true : false;
            $d['data'][$k]['chat_icon'] = $row->chat == 1 ?true : false;
            $d['data'][$k]['email_icon'] = $row->email == 1 ?true : false;
            $d['data'][$k]['sms_icon'] = $row->sms == 1 ?true : false;
            $d['data'][$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
            $d['data'][$k]['call_icon'] = $row->call == 1 ?true : false;

            if($user) {
                $d['data'][$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
            $d['data'][$k]['image']=url('/').'/assets/tmp/'.$row->image;
        }

        $dd = [];
        foreach($fixed_ads as $k=>$row){
            $dd[$k]['id'] = $row->clothes->id;
            $dd[$k]['title'] = $row->clothes->$title;
            $dd[$k]['note'] = $row->clothes->$note;
            $dd[$k]['end_date'] = $row->end_date;
            $dd[$k]['cat_id'] = $row->clothes->cat_id;
            $dd[$k]['price'] = $row->clothes->price;
            $dd[$k]['user_id'] = $row->clothes->user->id;
            $dd[$k]['user_email'] = $row->clothes->user->email;
            $dd[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
            $dd[$k]['user_whats_number'] = $row->clothes->user->whats_number;
            $dd[$k]['lat'] = $row->clothes->lat;
            $dd[$k]['lng'] = $row->clothes->lng;
            $dd[$k]['country'] = ($row->clothes->country) ? $row->clothes->country->$title : null;
            $dd[$k]['coin_name'] =($row->clothes->country) ? $row->clothes->country->coin_name : null;

            $dd[$k]['sale'] = $row->clothes->sale == 1 ?true : false;
            $dd[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
            $dd[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
            $dd[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
            $dd[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
            $dd[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;



            if($user) {
                $dd[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
            $dd[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
        }

        $categoriesData = [];
        foreach($categories as $k=>$row){
            $categoriesData[$k]['id'] = $row->id;
            $categoriesData[$k]['title'] = $row->$title;
            $categoriesData[$k]['location'] = $row->location == 1 ? true : false;
            $categoriesData[$k]['image'] = url('/').'/assets/tmp/'.$row->image;
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        $data=['count_total' => $paginate->total(),
            'nextPageUrl' => $paginate->nextPageUrl(),
            'pages'=>ceil($paginate->total()/$length),
            'categories'=>$categoriesData,
            'fixed_ads'=>$dd,
            'data'=>$d['data'],



        ];
        return $this->outApiJson(true,'success',$data);
    }

    public function all(Request $request){
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;

        $country=$request->header('country');
        if ($country){
            $country=$request->header('country');

        }else{
            $country=3;
        }

        if ($request->type == 1){
            $now=Carbon::now()->format('Y-m-d H:i:s');
            $fixed_ad=FixedAds::query();
            $fixed_ad->with('clothes.favorites','clothes.user','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('clothes' ,function ($q) use ($country){
                    $q->where('country_id',$country);
                })
                ->whereHas('packages' ,function ($q){
                    $q->where('type',1);
                });

            $fixed_ad->where('status','1');
            $fixed_ad ->orderBy('created_at','desc');
            if ($request->home == 1){
                $fixed_ad->whereIn('home', ['1','3']);
            }else{
                $fixed_ad->whereIn('home', ['2','3']);
                if($request->input('cat_id')) {
                    $getSub = $this->getSubCat($request->input('cat_id'),',');
                    if($getSub){
                        $getSub=rtrim($getSub,',');
                        $cats = explode(',',$getSub);
                    }
                    $cats[]=intval($request->input('cat_id'));

//                    $where_obj->WhereIn('cat_id', $cats);

                    $fixed_ad->WhereIn('cat_id', $cats);

                }
            }
            $title='title_'.$request->header('lang');

            if ($request->input('keyword')){
                $fixed_ad->whereHas('clothes' ,function ($q) use ($title,$request){
                    $q->where($title,'like','%'.$request->input('keyword').'%');

                });

            }

            $fixed_ads = $fixed_ad->paginate($length);

            $d['data'] = [];
//            $title='title_'.$request->header('lang');
            $note='note_'.$request->header('lang');
            $dd = [];
            foreach($fixed_ads->items() as $k=>$row){
                $dd[$k]['id'] = $row->clothes->id;
                $dd[$k]['title'] = $row->clothes->$title;
                $dd[$k]['note'] = $row->clothes->$note;
                $dd[$k]['end_date'] = $row->end_date;
                $dd[$k]['cat_id'] = $row->clothes->cat_id;
                $dd[$k]['price'] = $row->clothes->price;
                $dd[$k]['user_id'] = $row->clothes->user->id;
                $dd[$k]['user_email'] = $row->clothes->user->email;
                $dd[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $dd[$k]['user_whats_number'] = $row->clothes->user->whats_number;

                $dd[$k]['sale'] = $row->clothes->sale == 1 ?true : false;
                $dd[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
                $dd[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
                $dd[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
                $dd[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
                $dd[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;



                if($user) {
                    $dd[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $dd[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
            }
            $data=['count_total' => $fixed_ads->total(),
                'nextPageUrl' => $fixed_ads->nextPageUrl(),
                'pages'=>ceil($fixed_ads->total()/$length),
                'data'=>$dd,

            ];

        }elseif ($request->type == 2){
            $now=Carbon::now()->format('Y-m-d H:i:s');
            $fixed_ad=FixedAds::query();
            $fixed_ad->with('clothes.favorites','clothes.user','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('clothes' ,function ($q) use ($country){
                    $q->where('country_id',$country);
                })
                ->whereHas('packages' ,function ($q){
                    $q->where('type',2);
                });
            $fixed_ad->where('status','1');
            $fixed_ad ->orderBy('created_at','desc');
            $fixed_ad->where('home', '1');

            $title='title_'.$request->header('lang');

            if ($request->input('keyword')){
                $fixed_ad->whereHas('clothes' ,function ($q) use ($title,$request){
                    $q->where($title,'like','%'.$request->input('keyword').'%');

                });

            }
            $fixed_ads = $fixed_ad->paginate($length);


//            $title='title_'.$request->header('lang');
            $note='note_'.$request->header('lang');
            $dd = [];
            foreach($fixed_ads->items() as $k=>$row){
                $dd[$k]['id'] = $row->clothes->id;
                $dd[$k]['title'] = $row->clothes->$title;
                $dd[$k]['note'] = $row->clothes->$note;
                $dd[$k]['end_date'] = $row->end_date;
                $dd[$k]['cat_id'] = $row->clothes->cat_id;
                $dd[$k]['price'] = $row->clothes->price;
                $dd[$k]['user_id'] = $row->clothes->user->id;
                $dd[$k]['user_email'] = $row->clothes->user->email;
                $dd[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $dd[$k]['user_whats_number'] = $row->clothes->user->whats_number;

                $dd[$k]['sale'] = $row->clothes->sale == 1 ?true : false;
                $dd[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
                $dd[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
                $dd[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
                $dd[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
                $dd[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;



                if($user) {
                    $dd[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $dd[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
            }
            $data=['count_total' => $fixed_ads->total(),
                'nextPageUrl' => $fixed_ads->nextPageUrl(),
                'pages'=>ceil($fixed_ads->total()/$length),
                'data'=>$dd,

            ];
        }elseif ($request->type == 3){
            $most_watched= Clothes::query();
            $most_watched->where('type','1')
                ->where('country_id',$country)
                ->where('status','1')->where('confirm','1')
                ->with('user','country')->orderBy('views','desc');

            $title='title_'.$request->header('lang');
            if ($request->input('keyword')){
                $most_watched->where($title,'like','%'.$request->input('keyword').'%');


            }
            $most_watcheds = $most_watched->paginate($length);
            $ddd = [];
            $note='note_'.$request->header('lang');
            foreach($most_watcheds->items() as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['note'] = $row->$note;
                $ddd[$k]['end_date'] = $row->end_date;
                $ddd[$k]['price'] = $row->price;
                $ddd[$k]['cat_id'] = $row->cat_id;

                $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $ddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $ddd[$k]['views'] = $row->views;
                $ddd[$k]['user_id'] = $row->user->id;
                $ddd[$k]['user_email'] = $row->user->email;
                $ddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $ddd[$k]['user_whats_number'] = $row->user->whats_number;

                $ddd[$k]['sale'] = $row->sale == 1 ?true : false;
                $ddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                $ddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                $ddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                $ddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                $ddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                if($user) {
                    $ddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
            $data=['count_total' => $most_watcheds->total(),
                'nextPageUrl' => $most_watcheds->nextPageUrl(),
                'pages'=>ceil($most_watcheds->total()/$length),
                'data'=>$ddd,

            ];

        }else{
            $latest_ads= Clothes::query();
            $latest_ads->where('type','1')
                ->where('country_id',$country)
                ->where('status','1')->where('confirm','1')
                ->with('user','country')->orderBy('id','desc');

            $title='title_'.$request->header('lang');
            if ($request->input('keyword')){
                $latest_ads->where($title,'like','%'.$request->input('keyword').'%');

            }
            $latest_ad = $latest_ads->paginate($length);
            $ddd = [];
            $note='note_'.$request->header('lang');
            foreach($latest_ad->items() as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['note'] = $row->$note;
                $ddd[$k]['end_date'] = $row->end_date;
                $ddd[$k]['price'] = $row->price;
                $ddd[$k]['cat_id'] = $row->cat_id;

                $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $ddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $ddd[$k]['views'] = $row->views;
                $ddd[$k]['user_id'] = $row->user->id;
                $ddd[$k]['user_email'] = $row->user->email;
                $ddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $ddd[$k]['user_whats_number'] = $row->user->whats_number;

                $ddd[$k]['sale'] = $row->sale == 1 ?true : false;
                $ddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                $ddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                $ddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                $ddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                $ddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                if($user) {
                    $ddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
            $data=['count_total' => $latest_ad->total(),
                'nextPageUrl' => $latest_ad->nextPageUrl(),
                'pages'=>ceil($latest_ad->total()/$length),
                'data'=>$ddd,

            ];
        }
        return $this->outApiJson(true,'success',$data);
    }
    public function getRow(Request $request)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }
//        return $user;

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        try{
            $repose = Clothes::where('id',$request->input('id'))->with('categories.parent','country','user')->first();
            if(empty($repose)){
                return $this->outApiJson(false,'not_found');
            }

            $similar= Clothes::where('type','1')->where('status','1')->where('confirm','1')->where('cat_id',$repose->cat_id)->with('user')->orderBy('id','desc')->limit(10)->get(['*']);


            if(empty($repose->views)){
                $repose->views = 1;
            }else{
                $repose->views  += 1;
            }
            $repose->save();
//            return $repose;
            $data=[];
            if ($repose) {
                $title='title_'.$request->header('lang');
                $note='note_'.$request->header('lang');

                $data['id'] = $repose->id;
                $data['title'] = $repose->$title;
                $data['note'] = $repose->$note;
                $data['end_date'] = $repose->end_date;
                $data['created_at'] = $repose->created_at;
                $data['price'] = $repose->price;
                $data['cat_id'] = $repose->cat_id;
                $data['sup_cat_id'] = $repose->categories->parent->id;
                $data['views'] = $repose->views;
                $data['country_id'] = $repose->country_id;
                $data['governorates_id'] = $repose->governorates_id;
                $data['coin_name'] =($repose->country) ? $repose->country->coin_name : null;

                $data['number_rooms'] = $repose->number_rooms;
                $data['swimming_pool'] = $repose->swimming_pool;
                $data['Jim'] = $repose->Jim;
                $data['year'] = $repose->year;
                $data['cere'] = $repose->cere;
                $data['number_cylinders'] = $repose->number_cylinders;
                $data['working_condition'] = $repose->working_condition;
                $data['salary'] = $repose->salary;
                $data['biography'] = $repose->biography;
                $data['location'] = $repose->location;

                if ($repose->brand_id){
                    $items_brand=Item::find($repose->brand_id);
                    if ($items_brand){
                        $data['brand_id'] =  $items_brand->$title ;
                    }else{
                        $data['brand_id'] =  null ;
                    }
                }

                if ($repose->educational_level_id){
                    $items_educational_level_id=Item::find($repose->educational_level_id);
                    if ($items_educational_level_id){
                        $data['educational_level_id'] =  $items_educational_level_id->$title ;
                    }else{
                        $data['educational_level_id'] =  null ;
                    }
                }
                if ($repose->specialization_id){
                    $items_specialization_id=Item::find($repose->specialization_id);
                    if ($items_specialization_id){
                        $data['specialization_id'] =  $items_specialization_id->$title ;
                    }else{
                        $data['specialization_id'] =  null ;
                    }
                }

                if ($repose->animal_type_id){
                    $items_animal_type_id=Item::find($repose->animal_type_id);
                    if ($items_animal_type_id){
                        $data['animal_type_id'] =  $items_animal_type_id->$title ;
                    }else{
                        $data['animal_type_id'] =  null ;
                    }
                }
                if ($repose->fashion_type_id){
                    $items_fashion_type_id=Item::find($repose->fashion_type_id);
                    if ($items_fashion_type_id){
                        $data['fashion_type_id'] =  $items_fashion_type_id->$title ;
                    }else{
                        $data['fashion_type_id'] =  null ;
                    }
                }
                if ($repose->subjects_id){
                    $items_subjects_id=Item::find($repose->subjects_id);
                    if ($items_subjects_id){
                        $data['subjects_id'] =  $items_subjects_id->$title ;
                    }else{
                        $data['subjects_id'] =  null ;
                    }
                }



                $data['user_id'] = $repose->user->id;
                $data['user_name'] = $repose->first_name;
                $data['user_email'] = $repose->user->email;
                $data['user_mobile_number'] = $repose->user->mobile_number;
                $data['user_whats_number'] = $repose->user->whats_number;
                $data['user_image'] = url('/').'/assets/tmp/'.$repose->user->avatar;

                $data['chat_icon'] = $repose->chat == 1 ?true : false;
                $data['email_icon'] = $repose->email == 1 ?true : false;
                $data['sms_icon'] = $repose->sms == 1 ?true : false;
                $data['whatsApp_icon'] = $repose->whatsApp == 1 ?true : false;
                $data['call_icon'] = $repose->call == 1 ?true : false;
                $data['sale'] = $repose->sale == 1 ?true : false;

                if($user) {
                    $data['fav'] = ($repose->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                    $follw=Follow::where('follow',$user->id)->where('followers',$repose->user->id)->first();
                    $data['follow'] = ($follw)?true: false;

                }
                $data['image']=url('/').'/assets/tmp/'.$repose->image;

                $data['images']=[];
                $kk=0;
                foreach($repose->charityImages as $kk=>$item){
                    //$data['images'][$kk]['id']=$item->id;
                    $data['images'][$kk]['image']=url('/').'/assets/tmp/'.$item->image;
                    $kk++;
                }
                $data['images'][$kk]=['image'=>url('/').'/assets/tmp/'.$repose->image];


                $dddd = [];
                foreach($similar as $k=>$row){
                    $dddd[$k]['id'] = $row->id;
                    $dddd[$k]['title'] = $row->$title;
                    $dddd[$k]['note'] = $row->$note;
                    $dddd[$k]['end_date'] = $row->end_date;
                    $dddd[$k]['price'] = $row->price;
                    $dddd[$k]['cat_id'] = $row->cat_id;

                    $dddd[$k]['user_id'] =$row->user? $row->user->id : null;
                    $dddd[$k]['user_email'] = $row->user? $row->user->email : null;
                    $dddd[$k]['user_mobile_number'] = $row->user ?  $row->user->mobile_number :null;
                    $dddd[$k]['user_whats_number'] = $row->user ? $row->user->whats_number : null;

                    $dddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                    $dddd[$k]['sale'] = $row->sale == 1 ?true : false;
                    $dddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                    $dddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                    $dddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                    $dddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                    if($user) {
                        $dddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                    }
                    $dddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
                }

                $datas=[
                    'data'=> $data,
                    'similar'=> $dddd,

                ];
                return $this->outApiJson(true,'success',$datas);
            }

            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubCat($parent,$dilemeter){
        $cats =Categories::where(['parent_id'=>$parent])->get();

        $out='';
        foreach ($cats as $row){
            $out .=$row->id.$dilemeter;
            $out .=$this->getSubCat($row->id,',');
        }
        return $out;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        $parent_id = ($request->input('parent_id')) ? $request->input('parent_id') : 0;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('parent_id',$parent_id,'eq');
        $where_obj->pushWhere('status',1,'eq');
        //$where_obj->pushWhere('confirm',1,'eq');
        if($request->input('keyword')){
            $where_obj->pushWhere('title_'.$request->header('lang'),$request->input('keyword'),'contain');
        }
        $where_obj->pushOrder('sort_order','asc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->cat->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->cat->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
            $d['data'][$k]['products']=$row->products->count();
            $d['data'][$k]['subCategory']=$row->sub->count();
        }
        //$d['data'] = $paginate->items();
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function slider(Request $request)
    {
        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        $parent_id = ($request->input('parent_id')) ? $request->input('parent_id') : 0;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('status',1,'eq');
        if($request->input('keyword')){
            $where_obj->pushWhere('title_'.$request->header('lang'),$request->input('keyword'),'contain');
        }
        $where_obj->pushOrder('id','desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->slider->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->slider->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['image']=url('/').'/assets/tmp/'.$row->image;
        }
        //$d['data'] = $paginate->items();
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    public function delivery(Request $request)
    {
        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
//        $where_obj = new \App\Repositories\Criteria\WhereObject();
//        $where_obj->pushWhere('status',1,'eq');
//        $where_obj->pushOrder('id','desc');
//        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
//        $push::setWhereObject($where_obj);
//        $this->dev->pushCriteria(new AdvancedSearchCriteria());
//        $paginate = $this->dev->all();
        $paginate =Delivery::where('status','1')->orderby('id','desc')->get();

        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['price'] = $row->cost;
            $d['data'][$k]['order_limit'] = $row->order_limit;
        }
        //$d['data'] = $paginate->items();
        // $d['recordsTotal'] = $paginate->total();
        // $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['data'=>$d['data']]);
    }
    public function payment(Request $request)
    {
        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('status',1,'eq');
        $where_obj->pushWhere('id',4,'neq');
        $where_obj->pushOrder('id','desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->pay->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->pay->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
        }
        //$d['data'] = $paginate->items();
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactUs(Request $request)
    {

        if(
            empty($request->input('name'))||
            empty($request->input('email'))||
            empty($request->input('subject'))||
            empty($request->input('phone'))||
            empty($request->input('message'))
        ){
            return $this->outApiJson(false,'data_required');
        }

        $data=[
            'name'=>$request->input('name'),
            'mobile'=>$request->input('subject'),
            'email'=>$request->input('email'),
            'message'=>$request->input('message'),
            'phone'=>$request->input('phone'),
        ];

        try{
            $repose=Contact::create($data);
            if ($repose) {
//                PusherApp::pushNotifications([
//                    'message' => $request->input('message'),
//                    'type' => 'contact-us',
//                    'id' => null
//                ]);
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(Request $request)
    {
    $user = null;
    try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        try{
            $up_banner=$this->ads->findWhere(['layout'=>4,'status'=>1])->take(1);
            $down_banner=$this->ads->findWhere(['layout'=>2,'status'=>1])->first();
            $hot=$this->repo->getHomeAds(1,50);
            $flash=$this->repo->getHomeAds(2,50);
            $newest=$this->repo->getHomeAds(3,50);
            $d = [];
            $title='title_'.$request->header('lang');
            foreach($hot as $k=>$row){
                $d[$k]['id'] = $row->id;
                $d[$k]['title'] = $row->$title;
                $d[$k]['end_date'] = $row->end_date;
                $d[$k]['price_before'] = $row->price;
                $d[$k]['price_after'] = $row->price_after;
                $d[$k]['end_offer'] = $row->price_after;
                $d[$k]['quntaty'] = $row->quntaty;
                $d[$k]['order_limit'] = $row->order_limit;
                if($user) {
                $d[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
                $d[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
            $dd = [];
            foreach($flash as $k=>$row){
                $dd[$k]['id'] = $row->id;
                $dd[$k]['title'] = $row->$title;
                $dd[$k]['end_date'] = $row->end_date;
                $dd[$k]['price_before'] = $row->price;
                $dd[$k]['price_after'] = $row->price_after;
                $dd[$k]['end_offer'] = $row->end_offer;
                $dd[$k]['quntaty'] = $row->quntaty;
                $dd[$k]['order_limit'] = $row->order_limit;

                if($user) {
                $dd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
                $dd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
            $ddd = [];
            foreach($newest as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['end_date'] = $row->end_date;
                $ddd[$k]['price_before'] = $row->price;
                $ddd[$k]['price_after'] = $row->price_after;
                $ddd[$k]['end_offer'] = $row->price_after;
                $ddd[$k]['quntaty'] = $row->quntaty;
                $ddd[$k]['order_limit'] = $row->order_limit;

                if($user) {
                $ddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
            $ban=[];
            foreach($up_banner as $up){
                $ban[]=['url'=>$up->url,'image'=>url('/').'/assets/tmp/'.$up->image];
            }
            $data=[
                'up_banner'=>$ban,
                'down_banner'=>($down_banner)?['url'=>$down_banner->url,'image'=>url('/').'/assets/tmp/'.$down_banner->image]:'',
                'hot'=>$d,
                'flash'=>$dd,
                'newest'=>$ddd,
            ];
            return $this->outApiJson(true,'success',$data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAdd(Request $request)
    {

        return 'a';
        $user=auth('api')->user();
//        return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        //check user inactive
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }

        if(empty($request->input('title'))){
            return $this->outApiJson(false,'data_required');
        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'address'=>$request->input('address'),
            'title'=>$request->input('title'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'floor'=>$request->input('floor'),
            'flat'=>$request->input('flat'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'city_id'=>$request->input('city_id'),
            'region_id'=>$request->input('region_id'),
            'type'=>$request->input('type'),
            'notes'=>$request->input('notes'),
            'mobile'=>$request->input('mobile'),
            'user_id'=> $user->id,
        ];
        try{
//            return $data;
            $repose=Charge::create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFav(Request $request)
    {
        $user=auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }


        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;



        $paginate = Fav::where('user_id',$user->id)->with('charity','user')->orderBy('id', 'DESC')->paginate($length);
//        return $paginate;


        $d['data'] = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');
//        return $paginate;
        foreach($paginate->items() as $k=>$row){

            if ($row->charity){
                if ($row->charity->status==1) {
                    $d['data'][$k]['id'] = $row->charity->id;
                    $d['data'][$k]['title'] = $row->charity->$title;
                    $d['data'][$k]['note'] = $row->charity->$note;
                    $d['data'][$k]['end_date'] = $row->charity->end_date;
                    $d['data'][$k]['price'] = $row->charity->price;
                    $d['data'][$k]['cat_id'] = $row->charity->cat_id;


                    $d['data'][$k]['country'] = ($row->charity->country) ? $row->charity->country->$title : null;
                    $d['data'][$k]['coin_name'] =($row->charity->country) ? $row->charity->country->coin_name : null;

                    $d['data'][$k]['user_id'] = $row->charity->user->id;
                    $d['data'][$k]['user_email'] = $row->charity->user->email;
                    $d['data'][$k]['user_mobile_number'] = $row->charity->user->mobile_number;
                    $d['data'][$k]['user_whats_number'] = $row->charity->user->whats_number;

                    $d['data'][$k]['chat_icon'] = $row->charity->chat == 1 ?true : false;
                    $d['data'][$k]['email_icon'] = $row->charity->email == 1 ?true : false;
                    $d['data'][$k]['sms_icon'] = $row->charity->sms == 1 ?true : false;
                    $d['data'][$k]['whatsApp_icon'] = $row->charity->whatsApp == 1 ?true : false;
                    $d['data'][$k]['call_icon'] = $row->charity->call == 1 ?true : false;

                    if($user) {
                        $d['data'][$k]['fav'] = ($row->charity->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                    }
                    $d['data'][$k]['image']=url('/').'/assets/tmp/'.$row->charity->image;



                }
            }
        }

        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }
    public function addFav(Request $request)
    {
        $user = auth('api')->user();
//        return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }

        $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();
        if($add){
            return $this->outApiJson(false,'fav_exists');
        }
        $data=[
            'charity_id'=>$request->input('id'),
            'user_id'=> $user->id,
        ];

        try{

            $repose=Fav::create($data);

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function deleteFav(Request $request)
    {
        $user=auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        //check user inactive
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }

        if(
            empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }

        $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();

        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }

        try{
            $repose=Fav::where('charity_id', $request->input('id'))->where('user_id', $user->id)->delete();

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function getAdd(Request $request)
    {
        $user=auth('api')->user();
//        return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        ini_set('precision', 10);
        ini_set('serialize_precision', 10);
        //check user inactive
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
//        $where_obj = new \App\Repositories\Criteria\WhereObject();
//        $where_obj->pushOrder('id','desc');
//        $where_obj->pushOrWhere('user_id', $user->id,'eq');
//        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
//        $push::setWhereObject($where_obj);
//        $this->address->pushCriteria(new AdvancedSearchCriteria());
//        $paginate = $this->address->all();
        $paginate=Charge::where('user_id',$user->id)->orderBy('id', 'DESC')->get();



        $d['data'] = [];
        $title='title_'.$request->header('lang');
        $city_id=[];
        foreach($paginate as $k=>$row) {
            $city_id[] = $row->city_id;
        }
        if ($city_id > 0){
            $country_id= $request->header('country');
            $country=Country::where('id',$country_id)->where('status','1')->first();
            if (empty($country)){
                $country_id='3';
            }else{
                $country_id= $request->header('country');
            }
            $areas = Governorates::where(['status'=>1])->whereIn('id',$city_id)->where('country_id',$country_id)->get();
            $areas_id=[];
            foreach($areas as $k=>$row) {
                $areas_id[] = $row->id;
            }
//            return  $areas;

            $paginate=Charge::where('user_id',$user->id)->whereIn('city_id',$areas_id)->orderBy('id', 'DESC')->get()  ;
//            return $city_areas;

        }

//        return $paginate;
//        if ($paginate != []){
            foreach($paginate as $k=>$row){
                if( $user->address==$row->id){
                    $d['data'][$k]['default']=true;
                }
                $d['data'][$k]['id'] = $row->id;
                $d['data'][$k]['address'] = $row->address;
                $d['data'][$k]['lat'] = $row->lat;
                $d['data'][$k]['lng'] = $row->lng;
                $d['data'][$k]['title'] = $row->title;
                $d['data'][$k]['street'] = $row->street;
                $d['data'][$k]['block'] = $row->block;
                $d['data'][$k]['city'] = $row->city;
                $d['data'][$k]['governate'] = $row->governate;
                $d['data'][$k]['floor'] = $row->floor;
                $d['data'][$k]['flat'] = $row->flat;
                $d['data'][$k]['building'] = $row->building;
                $d['data'][$k]['avenue'] = $row->avenue;
                $d['data'][$k]['type'] = $row->type;
                $d['data'][$k]['notes'] = $row->notes;
                $d['data'][$k]['mobile'] = $row->mobile;
                $d['data'][$k]['city_id'] = $row->city_id;
                $d['data'][$k]['region_id'] = $row->region_id;
                $d['data'][$k]['region'] = $row->regionData?[
                    'title'=>$row->regionData->$title,
                    'delivery_cost'=>$row->regionData->delivery_cost,
                    'order_limit'=>$row->regionData->order_limit,
                ]:null;
            }
//        }



        return $this->outApiJson(true,'success',['data'=>$d['data']]);
    }
    public function deleteAdd(Request $request)
    {
        $user=auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        //check user inactive
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false,'user_inactive');
//        }

        if(
        empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=Charge::where(['user_id'=> $user->id,'id'=>$request->input('id')])->first();
        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=Charge::where('id', $request->input('id'))->delete();

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function editAdd(Request $request)
    {
        $user=auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        //check user inactive
        if ( $user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(empty($request->input('title'))){
            return $this->outApiJson(false,'data_required');
        }


        $data=[
            'lat'=>$request->input('lat'),
            'lng'=>$request->input('lng'),
            'address'=>$request->input('address'),
            'title'=>$request->input('title'),
            'street'=>$request->input('street'),
            'block'=>$request->input('block'),
            'city'=>$request->input('city'),
            'governate'=>$request->input('governate'),
            'floor'=>$request->input('floor'),
            'flat'=>$request->input('flat'),
            'building'=>$request->input('building'),
            'avenue'=>$request->input('avenue'),
            'city_id'=>$request->input('city_id'),
            'region_id'=>$request->input('region_id'),
            'type'=>$request->input('type'),
            'notes'=>$request->input('notes'),
            'mobile'=>$request->input('mobile'),
            'user_id'=> $user->id,
        ];
        try{
            $repose=Charge::find($request->input('id'));
            $repose->update($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }


    public function createProperty(Request $request)
    {


        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('title')) ||
            empty($request->input('note')) ||
            empty($request->input('price')) ||
            empty($request->input('country_id'))||
              empty($request->input('governorates_id')) ||
             empty($request->input('cat_id'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }
         try {
        $langs = ['ar', 'en'];
        $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
        $cat = Categories::find($request->input('cat_id'));

        if (!$cat) {
            return $this->outApiJson(false, 'cat_not_found');
        }


        $data['note_ar'] = $request->input('note');
        $data['note_en'] = $request->input('note');

        if(!empty($request->input('title'))){
            $data['title_ar'] = $request->title;
            $data['title_en'] = $request->title;
        }


        $images_data = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();

                $fileName = ($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'assets/tmp';
                $file->move($destinationPath, $fileName);
                // create image thumb
//                $this->createThumb($destinationPath, $fileName);
                $images_data[] = ['image' => $fileName];
            }
        }


        if (count($images_data) > 0) {
            $data['image'] = $images_data[0]['image'];
        }

        $data['user_id'] = $user->id;

        unset($data['images']);

        $add = Clothes::create($data);

        if ($request->lat and $request->lng){
            $point = DB::table('clothes')->where('id', $add->id)->limit(1)->update([ 'form_location' => DB::raw("(ST_GeomFromText('POINT($request->lat $request->lng)'))")]);

        }
        if (!$add) {
            return $this->outApiJson(false, 'create_error');
        }
        $add->charityImages()->createMany($images_data);
        // add payment link
        $userdata['advertisement_id']=$add->id;

        return $this->outApiJson(true, 'success',$userdata);
         } catch (\PDOException $ex) {
             return $this->outApiJson(false, 'pdo_exception');
         }
    }
    public function updateProperty(Request $request)
    {
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('id')) ||
            empty($request->input('title')) ||
            empty($request->input('note')) ||
            empty($request->input('price')) ||
            empty($request->input('country_id'))||
              empty($request->input('governorates_id')) ||
             empty($request->input('cat_id'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }

         try {
             $prop = Clothes::where('id',$request->input('id'))->first();
             if (!$prop) {
                 return $this->outApiJson(false, 'not_found');
             }
        $langs = ['ar', 'en'];
        $data = $request->except(['_token', 'id', 'note', 'type','use_credit','title']);
        $cat = Categories::find($request->input('cat_id'));

        if (!$cat) {
            return $this->outApiJson(false, 'cat_not_found');
        }


        $data['note_ar'] = $request->input('note');
        $data['note_en'] = $request->input('note');

        if(!empty($request->input('title'))){
            $data['title_ar'] = $request->title;
            $data['title_en'] = $request->title;
        }

        $images_data = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                    '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                $destinationPath = 'assets/tmp';
                $file->move($destinationPath, $fileName);
                // create image thumb
                $this->createThumb($destinationPath, $fileName);
                $images_data[] = ['image' => $fileName];
            }
        }

        if (count($images_data) > 0) {
            $data['image'] = $images_data[0]['image'];
        }

        $data['user_id'] = $user->id;

        unset($data['images']);

             $add = $prop->update($data);
             $prop->refresh();
//            return $add;
        if (!$add) {
            return $this->outApiJson(false, 'create_error');
        }
             $prop->charityImages()->createMany($images_data);

//             $prop->charityImages()->createMany($images_data);
        // add payment link
        $userdata['advertisement_id']=$prop;

        return $this->outApiJson(true, 'success',$userdata);
         } catch (\PDOException $ex) {
             return $this->outApiJson(false, 'pdo_exception');
         }
    }
    public function updatePropertySale(Request $request){
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('id'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }
//        try {


        $prop = Clothes::where('id',$request->input('id'))->first();
        if (!$prop) {
            return $this->outApiJson(false, 'not_found');
        }

        $data['sale'] = 1;

        $add = $prop->update($data);
        $prop->refresh();
//            return $add;
        if (!$add) {
            return $this->outApiJson(false, 'create_error');
        }


//             $prop->charityImages()->createMany($images_data);
        // add payment link
        $userdata['advertisement_id']=$prop;

                    return $this->outApiJson(true, 'success',$userdata);
//                } catch (\PDOException $ex) {
//            return $this->outApiJson(false, 'pdo_exception');
//}

    }
    public function deleteProperty(Request $request, $id)
    {
        //check user inactive
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        $add =Clothes::Where(['user_id' => $user->id, 'id' => $id])->first();
        if (!$add) {
            return $this->outApiJson(false, 'not_found');
        }
        try {
            $add->charityImages()->delete();
            $add->fixedAds()->delete();
            $repose = $add->delete();
            if ($repose) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function deleteImage(Request $request, $id)
    {
        $user = auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $add = CharityImage::where('id', $id)->first();
        if (!$add) {
            return $this->outApiJson(false, 'not_found');
        }
        try {
            $repose = CharityImage::where('id', $id)->delete();
            if ($repose) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function fixed_ads(Request $request){
        $user=auth('api')->user();
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
        if ($request->input('packege')){
            $price=[];
            $order_id=[];

            foreach ($request->input('packege') as $item){

                $pro_item[] = [
                    'packege_id' => $item['id'],
                    'clothes_id' => $item['clothes_id'],
                ];
                $product = Clothes::find($item['clothes_id']);
                if (!$product) {
                    return $this->outApiJson(false, 'not_found');
                }
                $Packages = Packages::find($item['id']);
                if (!$Packages) {
                    return $this->outApiJson(false, 'not_found');
                }

                $price[]= $Packages->price;
//                return $Packages;
//place_installation 1 home 2 cat 3 home + cat
//                return $product;
//                $now=Carbon::now()->format('Y-m-d H:i:s');
//                $now2=Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
//                $fixed_ads = Insurances::where('cat_id',$row)->where('end_at','>',$now)->where('end_at','<',$now2);
//                if ($fixed_ads->count() >= 9){
//                    $max_time = $fixed_ads->orderBy('end_at','desc')->first();
//                    if ($max_time){
//                        $time = Carbon::parse($max_time->end_at);
//                        if (Carbon::now()->gt($time)){
//                            $time = Carbon::now();
//                        }
//                    }else{
//                        $time=Carbon::now();
//                    }
//                }else{
//                    $time=Carbon::now();
//                }
//                return $Packages;
                $now=Carbon::now()->format('Y-m-d H:i:s');
                $now2=Carbon::now()->addDays($Packages->days)->format('Y-m-d H:i:s');

                $order =new FixedAds();

                    if ($Packages->place_installation == 1 ){
                        $order->cat_id=null;
                        $order->home='1';

                    }elseif($Packages->place_installation == 2 ){//cat
                        $order->cat_id=$product->cat_id;
                        $order->home='2';
                    }else{ //cat + home
                        $order->cat_id=$product->cat_id;
                        $order->home='3';
                    }

                    $order->clothes_id=$item['clothes_id'];
                    $order->packages_id=$item['id'];
                    $order->start_at=$now;
                    $order->end_at=$now2;
                    $order->status='0';
                    if ($Packages->type == 2){
                    $order->repeat_duration = 0;
                    }
                    $order->save();
                    $order_id[]=$order->id;
            }
            $orders_id=collect($order_id)->implode(',');
//            return $orders_id;


        }

        if (array_sum($price) > 0) {
            // add payment
            if ($user->mobile_number){
                $sub = substr($user->mobile_number, 0, 5);
                $number = substr($user->mobile_number, 5);
                if ($sub == '00965') {
                    $numbers = $number;
                } else {
                    $numbers = $user->mobile_number;
                }
            }

            $apiURL = 'https://api.tap.company';
//           $apiKey = 'sk_test_Zcei7lgtRAM6XKof8rY9QFw1';
//            $apiKey = 'sk_live_QRHvKxnkDctZmP9CYSWFX6gd';
        $apiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';

            $pay = Payment::find($request->input('payment_id'));
//            return $pay;
            if (!$pay) {

                return $this->outApiJson(false, 'not_found');
            }


                $postFields = [
                    //Fill required data
                    'amount' => array_sum($price),
//                    'currency' => $currency->slug ?? "KWD",
                    'currency' => 'KWD',
//            'threeDSecure'    => false,
                    'save_card' => false,
                    'description' => 'دفع فاتوره رقم # ' . $orders_id,
                    'statement_descriptor' => 'دفع فاتوره رقم # ' . $orders_id,
                    'metadata' => [
                        'udf1' => 'test 1',
                        'udf2' => 'test 2',
                    ],

                    'reference' => [
                        'transaction' => 'txn_0001',
                        'order' => $orders_id,
                    ],
                    'receipt' => [
                        'email' => true,
                        'sms' => true,
                    ],
                    'customer' => [
                        'first_name' => $user->first_name ? $user->first_name : $user->mobile_number ,

                        'phone' => [
                            'country_code' => '965',
                            'number' => $user->mobile_number ? $numbers : null,
                        ],
                        'email'=> $user->email ? $user->email : null
                    ],
                    'merchant' => [
                        'id' => '',
                    ],
                    'source' => [
//                        'id' => $pay->slug,
//                      'id'    => 'src_kw.knet',
                'id'    => 'src_card',
                    ],
                    'post' => [

                        'url' => route('paymentStatus') . '/?order_id=' . $orders_id,

                    ],
                    'redirect' => [
                        'url' => route('paymentStatus') . '/?order_id=' . $orders_id,

                    ],
//                    'ProductName' => json_encode($out),
//                    'ProductPrice' => json_encode($out2),
//                    'ProductQty' => json_encode($out3),

                ];
                $data = $this->executePayment($apiURL, $apiKey, $postFields);
//                        return $data;
//                        if ($data->error )
                $userdata['url'] = $data->transaction->url;
            }



        if ($order) {
            return $this->outApiJson(true,'success',$userdata);
        }
        return  $request->input('packege');

    }
    public function paymentStatus(Request $request)
    {

        try{
            $apiKey = 'sk_test_XKokBfNWv6FIYuTMg5sLPjhJ';
            $charge_id=$request->tap_id;
            $apiURL = 'https://api.tap.company/v2/charges/'.$charge_id;
            $data = $this->callAPI($apiURL, $apiKey,[],'GET');
//            return $data;
            $order_id = explode(',', $request->order_id);
            if ($data->status == 'INITIATED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }

                return  redirect()->route('ordersError');

            }elseif ($data->status == 'ABANDONED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'CANCELLED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }

                return  redirect()->route('ordersError');

            }
            elseif ($data->status == 'FAILED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'DECLINED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'RESTRICTED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'CAPTURED'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='1';
                    $order->status='1';
                    $order->update();
                }
                return  redirect()->route('ordersSuccess');

            }
            elseif ($data->status == 'VOID'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'TIMEDOUT'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'UNKNOWN'){
                foreach ($order_id as $k=>$value){
                    $order=FixedAds::find($value);
                    $order->payment_status='0';
                    $order->status='0';
                    $order->update();
                }
                return  redirect()->route('ordersError');
            }



        } catch (\Exception $e) {
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
    }



    function getPayment($apiURL,$charge_id, $apiKey,$requestType)
    {
        $json = $this->callAPI("$apiURL/v2/charges/$charge_id", $apiKey,$requestType);
        return $json;
    }

    function executePayment($apiURL, $apiKey, $postFields)
    {
        $json = $this->callAPI("$apiURL/v2/charges", $apiKey, $postFields);
        return $json;
    }

    function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST')
    {
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);



        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

}
