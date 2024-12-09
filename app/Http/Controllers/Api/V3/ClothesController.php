<?php

namespace App\Http\Controllers\Api\V3;

use App\Models\Ads;
use App\Models\Country;
use App\Models\Categories;
use App\Models\Clothes;
use App\Models\DeliveryTypes;
use App\Models\Fav;
use App\Models\FixedAds;
use App\Models\Item;
use App\Models\Packages;
use App\Models\Payment;
use App\Models\Times;
use App\Repositories\TimesRepository;
use Carbon\Carbon;
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
use App\Models\Setting;

class   ClothesController extends ApiController
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
    private $times;
    public function __construct(Request $request, ClothesRepository $repo,CategoriesRepository $cat,ContactRepository $contact, FavRepository $fav,AdsRepository $ads,AppUsersChargeRepository $address,DeliveryRepository $dev,PaymentRepository $pay,SliderRepository $slider, TimesRepository $times)
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
        $this->times = $times;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        if($request->input('cat_id')) {
            $where_obj->pushWhere('cat_id', $request->input('cat_id'), 'eq');
        }
        //$where_obj->pushWhere('end_date',Carbon::now(),'gte');
        $where_obj->pushWhere('status',1,'eq');
        $where_obj->pushWhere('confirm',1,'eq');
        if($request->input('keyword')){
            $where_obj->pushWhere('title_'.$request->header('lang'),$request->input('keyword'),'contain');
        }
        if($request->input('order')){
           if($request->input('order')==1){
                $where_obj->pushOrder('id','desc');
            }elseif($request->input('order')==2){
                $where_obj->pushOrder('id','asc');
            }elseif($request->input('order')==3){
                $where_obj->pushOrder('price','desc');
            }elseif($request->input('order')==4){
                $where_obj->pushOrder('price','asc');
            }
        }else{
           $where_obj->pushOrder('id','desc');
        }
        $where_obj_cat = new \App\Repositories\Criteria\WhereObject();
        $where_obj_cat->pushWhere('status',1,'eq');
        $where_obj->pushHas('cat',$where_obj_cat);

        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['end_date'] = $row->end_date;
            $d['data'][$k]['price_before'] = $row->price;
            $d['data'][$k]['price_after'] = $row->price_after;
            $d['data'][$k]['quntaty'] = $row->quntaty;
            $d['data'][$k]['order_limit'] = $row->order_limit;
            $d['data'][$k]['end_offer'] = $row->end_offer;

            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
            if($user) {
                $d['data'][$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    public function setting(Request $request)
    {
        $first_interface_title='first_interface_title_'.$request->header('lang');
        $second_interface_title='second_interface_title_'.$request->header('lang');
        $description_first_interface='description_first_interface_'.$request->header('lang');
        $description_second_interface='description_second_interface_'.$request->header('lang');

        try {

            $data=[
                'android_version'=> Setting::where('key_id','version')->first()->value,
                'ios_version'=> Setting::where('key_id','version_ios')->first()->value,
                'force_update'=> Setting::where('key_id','force_update')->first()->value == 1?true:false,
                'force_close'=> Setting::where('key_id','force_close')->first()->value == 1?true:false,
                'whats'=> Setting::where('key_id','picasa')->first()->value,
                 'snap'=> Setting::where('key_id','Snapchat')->first()->value,
                 'instagram'=> Setting::where('key_id','Instagram')->first()->value,
//                'TikTok'=> Setting::where('key_id','TikTok')->first()->value,
                'twitter'=> Setting::where('key_id','twitter')->first()->value,
                'activation_url'=> Setting::where('key_id','whats')->first()->value,

                'first_interface_title'=> Setting::where('key_id',$first_interface_title)->first()->value,
                'description_first_interface'=> Setting::where('key_id',$description_first_interface)->first()->value,
                'second_interface_title'=> Setting::where('key_id',$second_interface_title)->first()->value,
                'description_second_interface'=> Setting::where('key_id',$description_second_interface)->first()->value,


            ];
            return $this->outApiJson(true,'success',$data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    /**
     * @param Request $request
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataType(Request $request,$type)
    {

        if($type=='hot'){
            $deal=1;
        }elseif($type=='flash'){
            $deal=2;
        }else{
            $deal=3;
        }
        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('type',$deal,'eq');
        //$where_obj->pushWhere('end_date',Carbon::now(),'gte');
        $where_obj->pushWhere('status',1,'eq');
        $where_obj->pushWhere('confirm',1,'eq');
        $where_obj->pushOrder('id','desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['end_date'] = $row->end_date;
            $d['data'][$k]['price_before'] = $row->price;
            $d['data'][$k]['price_after'] = $row->price_after;
            $d['data'][$k]['quntaty'] = $row->quntaty;
            $d['data'][$k]['order_limit'] = $row->order_limit;
            $d['data'][$k]['end_offer'] = $row->end_offer;
            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRow(Request $request)
    {
        $user=null;
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        try{
            $repose = $this->repo->find($request->input('id'));

            $data=[];
            if ($repose) {
                $title='title_'.$request->header('lang');
                $note='note_'.$request->header('lang');
                $data['id']=$repose->id;
                $data['title']=$repose->$title;
                $data['image']=url('/').'/assets/tmp/'.$repose->image;
                $data['note']=$repose->$note;
                $data['price_before']=$repose->price;
                $data['price_after']=$repose->price_after;
                $data['category']=($repose->cat)?$repose->cat->$title:'';
                $data['end_date']=$repose->end_date;
                $data['quntaty']=$repose->quntaty;
                $data['end_offer']=$repose->end_offer;
                $data['order_limit']=$repose->order_limit;
                $data['lat']=$repose->lat;
                $data['lng']=$repose->lng;
                if($user) {
                $data['fav'] = ($repose->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $data['images']=[];
                $kk=0;
                foreach($repose->charityImages as $kk=>$item){
                    //$data['images'][$kk]['id']=$item->id;
                    $data['images'][$kk]['image']=url('/').'/assets/tmp/'.$item->image;
                    $kk++;
                }
                $data['images'][$kk]=['image'=>url('/').'/assets/tmp/'.$repose->image];
                return $this->outApiJson(true,'success',$data);
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
//        return 'a';
//        $country_id= $request->header('country');
//        $country=Country::where('id',$country_id)->where('status','1')->first();
//
//        return $country;
//        $up_banner=Ads::where(['layout'=>'5','status'=>'1'])->get();
//
//        if (empty($country) or $country->id == 3 ){
//
//        }else{
//            $up_banner=Ads::where(['layout'=>'5','status'=>'1','international'=>'1'])->get();
////            $paginate =$where_obj->where('international','1')->paginate($length);
//
////            $Clothes_ban=Clothes::whereIn('id',$up->multi_product_id)->where('international','1')->get();
//        }
//        return $up_banner;
//        $ban=[];
//        $ban=[];
//            $Clothes_ban=Clothes::whereIn('id',$up_banner->multi_product_id)->get();
//        $title='title_'.$request->header('lang');
//        foreach($up_banner as $k=>$up){
//            $ban[$k]['url'] = $up->url;
//            $ban[$k]['cat_id'] = $up->cat_id;
//            $ban[$k]['product_id'] = $up->product_id;
//            $ban[$k]['image'] = url('/').'/assets/tmp/'.$up->image;
////                $ban[$k]['multi_product_id'] = $up->multi_product_id ;
//            if (!empty($up->multi_product_id )){
//                $Clothes_ban=Clothes::whereIn('id',$up->multi_product_id)->get();
//                if ($Clothes_ban->count() > 0){
//                    foreach($Clothes_ban as $x=>$row){
//                        $ban[$k]['multi_product_id'][$x]['id'] = $row->id;
//                        $ban[$k]['multi_product_id'][$x]['title'] = $row->$title;
//                        $ban[$k]['multi_product_id'][$x]['end_date'] = $row->end_date;
//                        if (empty($country)){
//                            $ban[$k]['multi_product_id'][$x]['price_before'] = $row->price;
//                            $ban[$k]['multi_product_id'][$x]['price_after'] = $row->price_after;
//                        }else{
//                            $ban[$k]['multi_product_id'][$x]['price_before'] = (string)(round( $row->price  / $country->coin_price,3));
//                            if ($row->price_after != ''){
//                                $ban[$k]['multi_product_id'][$x]['price_after'] =(string)(round( $row->price_after  / $country->coin_price,3));
//                            }else{
//                                $ban[$k]['multi_product_id'][$x]['price_after'] = (string)$row->price_after;
//                            }
//
//                        }
////                    $ban[$k]['multi_product_id'][$x]['price_before'] = $row->price;
////                    $ban[$k]['multi_product_id'][$x]['price_after'] = $row->price_after;
//                        $ban[$k]['multi_product_id'][$x]['quntaty'] = $row->quntaty;
//                        $ban[$k]['multi_product_id'][$x]['order_limit'] = $row->order_limit;
//                        $ban[$k]['multi_product_id'][$x]['end_offer'] = $row->end_offer;
//                        $ban[$k]['multi_product_id'][$x]['weight'] = $row->weight;
//
////                    if($user) {
////                        $ban[$k]['multi_product_id'][$x]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
////                    }
//                        $ban[$k]['multi_product_id'][$x]['image']=url('/').'/assets/tmp/'.$row->image;
//                    }
//                }
//            }
//
//            $title='title_'.$request->header('lang');
//
//        }

        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        $parent_id = ($request->input('parent_id')) ? $request->input('parent_id') : 0;

        $where_obj =Categories::query();
        $where_obj->where('status','1');
        $where_obj->where('parent_id',$parent_id);

        if($request->input('keyword')){
            $where_obj->where('title_'.$request->header('lang'),'like', '%'.$request->input('keyword') .'%');
        }
        $where_obj->orderBy('sort_order','asc');
        $paginate =$where_obj->paginate($length);



        $d['data'] = [];

     $title='title_'.$request->header('lang');
        // return $paginate;
        foreach($paginate->items() as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['color'] = $row->color;
            $d['data'][$k]['image']=url('/').'/assets/tmp/thumb/'.$row->image;
            $d['data'][$k]['products']=$row->products->count();
            $d['data'][$k]['subCategory']=$row->sub->count();
            if ($request->input('parent_id')){
            $d['data'][$k]['number_rooms']=$row->number_rooms == 1 ?  true : false;
            $d['data'][$k]['swimming_pool']=$row->swimming_pool == 1 ?  true : false;
            $d['data'][$k]['Jim']=$row->Jim == 1 ?  true : false;
            $d['data'][$k]['working_condition']=$row->working_condition == 1 ?  true : false;
            $d['data'][$k]['year']=$row->year == 1 ?  true : false;
            $d['data'][$k]['cere']=$row->cere == 1 ?  true : false;
            $d['data'][$k]['number_cylinders']=$row->number_cylinders == 1 ?  true : false;
            $d['data'][$k]['brand']=$row->brand == 1 ?  true : false;//1
            $d['data'][$k]['salary']=$row->salary == 1 ?  true : false;
            $d['data'][$k]['educational_level']=$row->educational_level == 1 ?  true : false;//2
            $d['data'][$k]['specialization']=$row->specialization == 1 ?  true : false;//3
            $d['data'][$k]['subjects']=$row->subjects == 1 ?  true : false;//4
            $d['data'][$k]['biography']=$row->biography == 1 ?  true : false;
            $d['data'][$k]['animal_type']=$row->animal_type == 1 ?  true : false;//5
            $d['data'][$k]['fashion_type']=$row->fashion_type == 1 ?  true : false;//6

            $d['data'][$k]['location']=$row->location == 1 ?  true : false;

                if ($row->brand == 1){
                    $brand=Item::where('type',1)->get();
                    if ($brand->count() > 0) {
                        foreach ($brand as $kk => $b) {
                            $d['data'][$k]['brand_item'][$kk]['id'] = $b->id;
                            $d['data'][$k]['brand_item'][$kk]['title'] = $b->$title;
                        }
                    }else{
                        $d['data'][$k]['brand_item']=null;
                    }
                }else{
                    $d['data'][$k]['brand_item']=null;
                }
                if ($row->educational_level == 1){
                    $educational_level=Item::where('type',2)->get();

                    if ($educational_level->count() > 0){
                        foreach ($educational_level as $kk=>$b){
                            $d['data'][$k]['educational_level_item'][$kk]['id']=$b->id ;
                            $d['data'][$k]['educational_level_item'][$kk]['title']=$b->$title ;
                        }
                    }else{
                        $d['data'][$k]['educational_level_item']=null;
                    }

                }else{
                    $d['data'][$k]['educational_level_item']=null;
                }

                if ($row->specialization == 1){
                    $specialization=Item::where('type',3)->get();
                    if ($specialization->count() > 0) {
                        foreach ($specialization as $kk => $b) {
                            $d['data'][$k]['specialization_item'][$kk]['id'] = $b->id;
                            $d['data'][$k]['specialization_item'][$kk]['title'] = $b->$title;
                        }
                    }else{
                        $d['data'][$k]['specialization_item']=null;
                    }
                }else{
                    $d['data'][$k]['specialization_item']=null;
                }

                if ($row->subjects == 1){
                    $subjects=Item::where('type',4)->get();
                    if ($subjects->count() > 0) {
                        foreach ($subjects as $kk => $b) {
                            $d['data'][$k]['subjects_item'][$kk]['id'] = $b->id;
                            $d['data'][$k]['subjects_item'][$kk]['title'] = $b->$title;
                        }
                    } else{
                        $d['data'][$k]['subjects_item']=null;
                    }
                }else{
                    $d['data'][$k]['subjects_item']=null;
                }

                if ($row->animal_type == 1){
                    $animal_type=Item::where('type',5)->get();
                    if ($animal_type->count() > 0) {
                        foreach ($animal_type as $kk => $b) {
                            $d['data'][$k]['animal_type_item'][$kk]['id'] = $b->id;
                            $d['data'][$k]['animal_type_item'][$kk]['title'] = $b->$title;
                        }
                    }else{
                        $d['data'][$k]['animal_type_item']=null;
                    }
                }else{
                    $d['data'][$k]['animal_type_item']=null;
                }
                if ($row->fashion_type == 1){
                    $fashion_type=Item::where('type',6)->get();
                    if ($fashion_type->count() > 0) {
                        foreach ($fashion_type as $kk=>$b){
                        $d['data'][$k]['fashion_type_item'][$kk]['id']=$b->id ;
                        $d['data'][$k]['fashion_type_item'][$kk]['title']=$b->$title ;
                    }
                    }else{
                        $d['data'][$k]['fashion_type_item']=null;
                    }
                }else{
                    $d['data'][$k]['fashion_type_item']=null;
                }


            }
        }
        $dd=['categories'=>['data'=>$d['data']]];
        //$d['data'] = $paginate->items();
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',$dd);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 20;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;

        $paginate =Payment::where('status','1')->orderby('id','desc')->paginate($length);



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
    public function packages(Request $request){


        $title='title_'.$request->header('lang');

        $questions=Packages::query();
        $questions->where('status','1');
        if ($request->type){
            $questions->where('type', $request->type);

        }
//        return $questions->get();
        $d=[];
        foreach($questions->get() as $k=>$row){
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['type'] = $row->type;

            $d[$k]['price'] = $row->price;
            $d[$k]['days'] = $row->days;
            $d[$k]['status'] = $row->status;

        }
        return $this->outApiJson(true,'success',$d);

    }

    public function times(Request $request)
    {

        $paginate = Times::where('status',1)->get();
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
        }
        return $this->outApiJson(true,'success',$d['data']);
    }
    public function deliveryTypes(Request $request)
    {

        $paginate = DeliveryTypes::where('status',1)->get();
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate as $k=>$row){
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['title'] = $row->$title;
            $d['data'][$k]['time_from'] = $row->time_from;
            $d['data'][$k]['time_to'] = $row->time_to;
            $d['data'][$k]['sat'] = $row->sat;
            $d['data'][$k]['sun'] = $row->sun;
            $d['data'][$k]['mon'] = $row->mon;
            $d['data'][$k]['tue'] = $row->tue;
            $d['data'][$k]['wed'] = $row->wed;
            $d['data'][$k]['thu'] = $row->thu;
            $d['data'][$k]['fri'] = $row->fri;
        }
        return $this->outApiJson(true,'success',$d['data']);
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
            empty($request->input('message'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $data=[
            'name'=>$request->input('name'),
            'mobile'=>$request->input('subject'),
            'email'=>$request->input('email'),
            'message'=>$request->input('message'),
        ];
        try{
            $repose=$this->contact->create($data);
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
    public function about(Request $request)
    {

        try{
            $s=Setting::where('key_id','about_ar')->first()->value;

            $data=[

//                'about'=>Setting::where('key_id','about_'.$request->header('lang'))->first()->value,
//                'help'=>Setting::where('key_id','help_'.$request->header('lang'))->first()->value,
//                'privacy'=>Setting::where('key_id','p rivacy_'.$request->header('lang'))->first()->value,
                'conditions'=>Setting::where('key_id','conditions_'.$request->header('lang'))->first()->value,

            ];
            return $this->outApiJson(true,'success',$data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function country(Request $request){
//        return 'a';
        $Country=Country::query();
        $Country->where('status','1');
        $title='title_'.$request->header('lang');

        if ($request->input('key')){

            $Country->where($title,'like','%'.$request->input('key').'%');
        }
        $paginate=$Country->get();
//        return $paginate;
        $d = [];
        foreach($paginate as $k=>$row){
            $d[$k]['id'] = $row->id;
            $d[$k]['title'] = $row->$title;
            $d[$k]['status'] = $row->status;
            $d[$k]['coin_price'] = $row->coin_price;
            $d[$k]['coin_name'] = $row->coin_name;
            $d[$k]['coin_name_en'] = $row->coin_name_en;
            $d[$k]['phone_code'] = $row->phone_code;
            $d[$k]['regus'] = $row->regus;
            $d[$k]['image']=url('/').'/assets/tmp/'.$row->image;
        }
        $data=[

            'Country'=>$d,
        ];
        return $this->outApiJson(true,'success',$d);

    }
    public function home(Request $request)
    {


//        return $request->all();
    $user = null;
    try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (JWTException $e) {

        }

        try{


        $country=$request->header('country');
        if ($country){
            $country=$request->header('country');

        }else{
            $country=3;
        }



            $up_banner=Ads::where(['layout'=>'1','status'=>'1'])->where('country_id',$country)->orderBy('id','desc')->limit(6)->get();


            $up_banner_commercial=Ads::where(['layout'=>'2','status'=>'1'])->where('country_id',$country)->orderBy('id','desc')->limit(6)->get();


//        return $country;
            $categories=Categories::where(['status'=>'1','parent_id' => '0'])->orderBy('sort_order','asc')->get();
//            return $categories;
//            $fixed_ads= Clothes::where('type','1')->where('status','1')->where('confirm','1')->orderBy('id','asc')->limit(6)->get(['*']);
            $now=Carbon::now()->format('Y-m-d H:i:s');
//            $now2=Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
            $fixed_ads=FixedAds::with('clothes.favorites','clothes.user','clothes.country','clothes.governorates','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('packages' ,function ($q){
                    $q->where('type',1);
                })
                ->where('status','1')
                ->whereHas('clothes' ,function ($qq) use ($country){
                    $qq->where('country_id',$country);
                })
//                ->where('home', '1')
                ->whereIn('home', ['1','3'])
                ->orderBy('created_at','desc')->limit(9)->get(['*']);

//            return $fixed_ads;

            $featured=FixedAds::with('clothes.favorites','clothes.user','clothes.country','clothes.governorates','packages','cat')
                ->where('end_at','>',$now)
                ->whereHas('packages' ,function ($q){
                    $q->where('type',2);
                })
                ->whereHas('clothes' ,function ($qq) use ($country){
                    $qq->where('country_id',$country);
                })
                ->where('status','1')
                ->where('home', '1')
                ->orderBy('created_at','desc')->limit(9)->get(['*']);

//            $featured= Clothes::where('type','3')->where('status','1')->where('confirm','1')->orderBy('id','asc')->limit(6)->get(['*']);

            $most_watched= Clothes::where('type','1')->where('status','1')->where('confirm','1')
                ->where('country_id',$country)
                ->with('user.block','country','governorates')
                ->orderBy('views','desc')->limit(6)->get(['*']);

            $latest_ads= Clothes::where('status','1')
                ->where('country_id',$country)
                ->with('user','country','governorates')->orderBy('id','desc')->limit(6)->get(['*']);

            $d = [];
            $title='title_'.$request->header('lang');
            $note='note_'.$request->header('lang');
            foreach($fixed_ads as $k=>$row){
                $d[$k]['id'] = $row->clothes->id;
                $d[$k]['title'] = $row->clothes->$title;
                $d[$k]['note'] = $row->clothes->$note;
                $d[$k]['end_date'] = $row->end_date;
                $d[$k]['cat_id'] = $row->clothes->cat_id;
                $d[$k]['price'] = $row->clothes->price;

                $d[$k]['country'] = ($row->clothes->country) ? $row->clothes->country->$title : null;
                $d[$k]['coin_name'] =($row->clothes->country) ? $row->clothes->country->coin_name : null;

                $d[$k]['user_id'] = $row->clothes->user->id;
                $d[$k]['user_email'] = $row->clothes->user->email;
                $d[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $d[$k]['user_whats_number'] = $row->clothes->user->whats_number;

                $d[$k]['sale'] = $row->clothes->sale >= 1 ?true : false;
                $d[$k]['ishidden'] = $row->clothes->block_user >= 1 ?true : false;


                $d[$k]['chat_icon'] = $row->clothes->chat == 1 ?true : false;
                $d[$k]['email_icon'] = $row->clothes->email == 1 ?true : false;
                $d[$k]['sms_icon'] = $row->clothes->sms == 1 ?true : false;
                $d[$k]['whatsApp_icon'] = $row->clothes->whatsApp == 1 ?true : false;
                $d[$k]['call_icon'] = $row->clothes->call == 1 ?true : false;



                if($user) {
                $d[$k]['fav'] = ($row->clothes->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
            }
                $d[$k]['image']=url('/').'/assets/tmp/'.$row->clothes->image;
            }
            $dd = [];
            foreach($featured as $k=>$row){
                $dd[$k]['id'] = $row->clothes->id;
                $dd[$k]['title'] = $row->clothes->$title;
                $dd[$k]['note'] = $row->clothes->$note;
                $dd[$k]['end_date'] = $row->end_date;
                $dd[$k]['cat_id'] = $row->clothes->cat_id;
                $dd[$k]['price'] = $row->clothes->price;
                $dd[$k]['country'] = ($row->clothes->country) ? $row->clothes->country->$title : null;
                $dd[$k]['coin_name'] =($row->clothes->country) ? $row->clothes->country->coin_name : null;
                $dd[$k]['user_id'] = $row->clothes->user->id;
                $dd[$k]['user_email'] = $row->clothes->user->email;
                $dd[$k]['user_mobile_number'] = $row->clothes->user->mobile_number;
                $dd[$k]['user_whats_number'] = $row->clothes->user->whats_number;
                $dd[$k]['sale'] = $row->clothes->sale >= 1 ?true : false;
                $dd[$k]['ishidden'] = $row->clothes->block_user >= 1 ?true : false;
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
            $ddd = [];
            foreach($most_watched as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['note'] = $row->$note;
                $ddd[$k]['end_date'] = $row->end_date;
                $ddd[$k]['price'] = $row->price;

                $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $ddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $ddd[$k]['cat_id'] = $row->cat_id;

                $ddd[$k]['views'] = $row->views;
                $ddd[$k]['user_id'] = $row->user->id;
                $ddd[$k]['user_email'] = $row->user->email;
                $ddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $ddd[$k]['user_whats_number'] = $row->user->whats_number;
                $ddd[$k]['sale'] = $row->sale >= 1 ?true : false;
                $ddd[$k]['ishidden'] = $row->block_user >= 1 ?true : false;

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


            $dddd = [];
            foreach($latest_ads as $k=>$row){
                $dddd[$k]['id'] = $row->id;
                $dddd[$k]['title'] = $row->$title;
                $dddd[$k]['note'] = $row->$note;
                $dddd[$k]['end_date'] = $row->end_date;
                $dddd[$k]['price'] = $row->price;
                $dddd[$k]['cat_id'] = $row->cat_id;

                $dddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
                $dddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;

                $dddd[$k]['user_id'] = $row->user->id;
                $dddd[$k]['user_email'] = $row->user->email;
                $dddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $dddd[$k]['user_whats_number'] = $row->user->whats_number;
                $dddd[$k]['sale'] = $row->sale >= 1 ?true : false;
                $dddd[$k]['ishidden'] = $row->block_user >= 1 ?true : false;
                $dddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
                $dddd[$k]['email_icon'] = $row->email == 1 ?true : false;
                $dddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
                $dddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
                $dddd[$k]['call_icon'] = $row->call == 1 ?true : false;

                if($user) {
                    $dddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id) ->count() > 0)?true: false;
                }
                $dddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }


            $categoriesData = [];
            foreach($categories as $k=>$row){
                $categoriesData[$k]['id'] = $row->id;
                $categoriesData[$k]['title'] = $row->$title;
                $categoriesData[$k]['color'] = $row->color  ;
                $categoriesData[$k]['image'] = url('/').'/assets/tmp/'.$row->image;
            }
            $ban=[];

            foreach($up_banner as $k=>$up){
                $ban[$k]['url'] = $up->url;
//                $ban[$k]['cat_id'] = $up->cat_id;
//                $ban[$k]['product_id'] = $up->product_id;
                $ban[$k]['image'] = url('/').'/assets/tmp/'.$up->image;
            }
            $ban2=[];
            foreach($up_banner_commercial as $up){
                $ban2[]=['url'=>$up->url,
//                    'cat_id'=>$up->cat_id,
//                    'product_id'=>$up->product_id,
                    'image'=>url('/').'/assets/tmp/'.$up->image,

                ];
            }

            $data=[
                'up_banner'=>$ban,
                'up_banner_commercial'=>$ban2,

                'categories'=>$categoriesData,
                'fixed_ads'=>$d,
                'featured'=>$dd,
                'most_watched'=>$ddd,
                'latest_ads'=>$dddd,


            ];
            return $this->outApiJson(true,'success',$data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function interested(Request $request)
    {
//        return 'a';
        try{
//            $newest=$this->repo->getHomeAds(4,500);

            $country_id= $request->header('country');
            $country=Country::where('id',$country_id)->where('status','1')->first();


            if (empty($country) or $country->id == 3 ){
                $newest=Clothes::where('type',4)->where('status',1)->where('confirm',1)->orderBy('sort_order','asc')->limit(500)->get(['*']);

            }else{

//                return 'a';
                $newest=Clothes::where('type',4)->where('international','1')->where('status',1)->where('confirm',1)->orderBy('sort_order','asc')->limit(500)->get(['*']);

            }
            $ddd = [];
            $title='title_'.$request->header('lang');
            foreach($newest as $k=>$row){
                $ddd[$k]['id'] = $row->id;
                $ddd[$k]['title'] = $row->$title;
                $ddd[$k]['end_date'] = $row->end_date;

                if (empty($country)){
                    $ddd[$k]['price_before'] = $row->price;
                    $ddd[$k]['price_after'] = $row->price_after;
                }else{
                    $ddd[$k]['price_before'] = (string)(round( $row->price  / $country->coin_price,3));
                    if ($row->price_after != ''){
                        $ddd[$k]['price_after'] =(string)(round( $row->price_after  / $country->coin_price,3));
                    }else{
                        $ddd[$k]['price_after'] = (string)$row->price_after;
                    }

                }
//
//                $ddd[$k]['price_before'] = $row->price;
//                $ddd[$k]['price_after'] = $row->price_after;

                $ddd[$k]['quntaty'] = $row->quntaty;
                $ddd[$k]['order_limit'] = $row->order_limit;
                $ddd[$k]['end_offer'] = $row->end_offer;
                $ddd[$k]['weight'] = $row->weight;
                $ddd[$k]['international'] = $row->international;
                $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
            }
//            return $ddd;
//            if (!empty($ddd)){
                return $this->outApiJson(true,'success',$ddd);

//            }else{
//                return $this->outApiJson(true,'success', 'null');
//            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }

        $add=$this->fav->findWhere(['user_id'=>$this->user->id,'charity_id'=>$request->input('id')])->first();
        if($add){
            return $this->outApiJson(false,'fav_exists');
        }
        $data=[
            'charity_id'=>$request->input('id'),
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->fav->create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(
        empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=$this->fav->findWhere(['user_id'=>$this->user->id,'charity_id'=>$request->input('id')])->first();
        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=$this->fav->deleteFav($request->input('id'),$this->user->id);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFav(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushOrder('id','desc');
        $where_obj->pushOrWhere('user_id',$this->user->id,'eq');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->fav->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->fav->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            $d['data'][]=[
                'id'=>($row->charity)?$row->charity->id:'',
                'title'=>($row->charity)?$row->charity->$title:'',
                'end_date'=>($row->charity)?$row->charity->end_date:'',
                'price_before'=>($row->charity)?$row->charity->price:'',
                'price_after'=>($row->charity)?$row->charity->price_after:'',
                'end_offer'=>($row->charity)?$row->charity->end_offer:'',
                'order_limit'=>($row->charity)?$row->charity->order_limit:'',
                'fav'=>true,
                'image'=>$row->charity?url('/').'/assets/tmp/'.$row->charity->image:'',
            ];
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }

    public function addAdd(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
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
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->address->create($data);
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function getAdd(Request $request)
    {
        ini_set('precision', 10);
ini_set('serialize_precision', 10);
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage)
        {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushOrder('id','desc');
        $where_obj->pushOrWhere('user_id',$this->user->id,'eq');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->address->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->address->paginate($length);
        $d['data'] = [];
        $title='title_'.$request->header('lang');
        foreach($paginate->items() as $k=>$row){
            if($this->user->address==$row->id){
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
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }
    public function deleteAdd(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false,'user_inactive');
        }

        if(
        empty($request->input('id'))
        ){
            return $this->outApiJson(false,'data_required');
        }
        $add=$this->address->findWhere(['user_id'=>$this->user->id,'id'=>$request->input('id')])->first();
        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }
        try{
            $repose=$this->address->delete($request->input('id'));
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
        if(empty($request->input('id'))){
            return $this->outApiJson(false,'data_required');
        }
        //check user inactive
        if ($this->user->status != 'active') {
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
            'user_id'=>$this->user->id,
        ];
        try{
            $repose=$this->address->update($data,$request->input('id'));
            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }

}
