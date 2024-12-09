<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Helpers\Functions;


class LandingPageController extends Controller
{
    use Functions;

    public function DataLandingPage($idCountry){

        // varible
        $data = [];
        $up_banner = null;
        $up_banner_commercial = null;
        $fixed_ads = null;
        $featured = null;
        $most_watched = null;
        $latest_ads = null;


        $categories = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $up_banner = Ads::where(['layout' => '1', 'status' => '1'])->orderBy('id', 'desc')->limit(6)->get();
        $up_banner_commercial = Ads::where(['layout' => '2', 'status' => '1'])->orderBy('id', 'desc')->limit(9)->get();
        $fixed_ads = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
            ->where('end_at', '>', $now)
            ->whereHas('packages', function ($q) {
                $q->where('type', 1);
            })
            ->where('status', '1')
            ->whereIn('home', ['1','3'])
            ->orderBy('created_at', 'desc')->limit(9)->get(['*']);


        //return $fixed_ads;

        $featured = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
            ->where('end_at', '>', $now)
            ->whereHas('packages', function ($q) {
                $q->where('type', 2);
            })
            ->where('status', '1')
            ->whereIn('home', ['2','3'])
            ->orderBy('created_at', 'desc')->limit(9)->get(['*']);


        $most_watched = Clothes::where('type', '1')->where('status', '1')->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('views', 'desc')->limit(9)->get(['*']);
        $latest_ads = Clothes::where('type', '1')->where('status', '1')->where('confirm', '1')->with('user', 'country', 'governorates')->orderBy('id', 'desc')->limit(9)->get(['*']);

        $data = [
            'up_banner' => $up_banner,
            'up_banner_commercial' => $up_banner_commercial,
            'categories' => $categories,
            'fixed_ads' => $fixed_ads,
            'featured' => $featured,
            'most_watched' => $most_watched,
            'latest_ads' => $latest_ads,
        ];

        return $data;
    }
    public function index(Request $request)
    {
//        $categoryId = $request->input('categoryId');
//        if ($categoryId) {
//
//        } else {
        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {

        }


//        try{

        //dd(Auth::guard('user'));
        $DataCountry = $this->getCountryFromIP();
        // return DataCountry;
        
        $slug =  $DataCountry['country_code'] ?? 'KWD' ;
        if (Auth::guard('user')->check()){
            $userData = Auth::guard('user')->user();
            if ($userData->country_id == null){
                $Countrydefualt = Country::where('slug','KWD')->first('id');
                $data = $this->DataLandingPage($Countrydefualt->id);
            }else {
                $CountryData = Country::where('slug',$userData->country_id)->first('id');
                if ($CountryData == null){
                    $Countrydefualt = Country::where('slug','KWD')->first('id');
                    $data = $this->DataLandingPage($Countrydefualt->id);
                }else{
                    $CountryData = Country::where('slug',$slug)->first('id');
                    $data = $this->DataLandingPage($CountryData->id);
                }
            }
        }else {
            $CountryData = Country::where('slug',$slug)->first('id');
            if ($CountryData == null){
                $Countrydefualt = Country::where('slug','KWD')->first('id');
                $data = $this->DataLandingPage($Countrydefualt->id);
            }else{
                $CountryData = Country::where('slug',$slug)->first('id');
                $data = $this->DataLandingPage($CountryData->id);
            }
        }
        return view("Front.LandingPage.main", compact('data'));

    }

    public function DataAllPage($idCountry,$type){

        // varible
        $data = [];
        $up_banner = null;
        $up_banner_commercial = null;
        $defualtid = Country::where('slug','KWD')->first('id');

        $up_banner = Ads::where([
            'layout' => '1',
            'status' => '1',
            'country_id' => $idCountry
        ])->orderBy('id', 'desc')->limit(6)->get();
        if (count($up_banner) == 0) {
            $up_banner = Ads::where([
                'layout' => '1',
                'status' => '1',
                'country_id' => $defualtid->id
            ])->orderBy('id', 'desc')->limit(6)->get();
        }
        if ($type == 'adsbuss'){
            $result = Ads::where([
                'layout' => '2',
                'status' => '1' ,
                'country_id' => $idCountry
            ])->orderBy('id', 'desc')->paginate(12);
            if (count($result) == 0) {
                $result = Ads::where([
                    'layout' => '2',
                    'status' => '1' ,
                    'country_id' => $defualtid->id
                ])->orderBy('id', 'desc')->paginate(12);
            }

        }
        elseif ($type == 'adsfixed'){
            $result = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->whereHas('packages', function ($q) {
                    $q->where('type', 1);
                })
                ->where([
                    'status'=> '1',
                    'country_id' => $idCountry
                ])
                ->whereIn('home', ['1','3'])
                ->orderBy('created_at', 'desc')->paginate(16);
            if (count($result) == 0) {
                $result = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                    ->whereHas('packages', function ($q) {
                        $q->where('type', 1);
                    })
                    ->where([
                        'status'=> '1',
                        'country_id' => $defualtid->id
                    ])
                    ->whereIn('home', ['1','3'])
                    ->orderBy('created_at', 'desc')->paginate(16);
            }

        }
        elseif ($type == 'adsfeat'){
            $result = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->whereHas('packages', function ($q) {
                    $q->where('type', 2);
                })
                ->where([
                    'status'=> '1',
                    'country_id' => $idCountry
                ])
                ->whereIn('home', ['2','3'])
                ->orderBy('created_at', 'desc')->paginate(16);

            if (count($result) == 0) {
                $result = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                    ->whereHas('packages', function ($q) {
                        $q->where('type', 2);
                    })
                    ->where([
                        'status'=> '1',
                        'country_id' => $defualtid->id
                    ])
                    ->whereIn('home', ['2','3'])
                    ->orderBy('created_at', 'desc')->paginate(16);
            }


        }
        elseif ($type == 'adswatched'){
            $result = Clothes::where('type', '1')
                ->where([
                    'status'=> '1',
                    'country_id' => $idCountry
                ])->where('confirm', '1')
                ->with('user', 'country', 'governorates')
                ->orderBy('views', 'desc')->paginate(16);
            if (count($result) == 0) {
                $result = Clothes::where('type', '1')
                    ->where([
                        'status'=> '1',
                        'country_id' => $defualtid->id
                    ])->where('confirm', '1')
                    ->with('user', 'country', 'governorates')
                    ->orderBy('views', 'desc')->paginate(16);
            }
        }
        elseif ($type == 'adslatest'){
            $result = Clothes::where('type', '1')
                ->where([
                    'status'=> '1',
                    'country_id' => $idCountry
                ])->where('confirm', '1')
                ->with('user', 'country', 'governorates')
                ->orderBy('id', 'desc')->paginate(16);
            if (count($result) == 0) {
                $result = Clothes::where('type', '1')
                    ->where([
                        'status'=> '1',
                        'country_id' => $defualtid->id
                    ])->where('confirm', '1')
                    ->with('user', 'country', 'governorates')
                    ->orderBy('id', 'desc')->paginate(16);
            }
        }else {
            return redirect()->back();
        }
        $data = [
            'type' => $type,
            'result' => $result,
            'up_banner' => $up_banner
        ];
        return $data;
    }
    public function all($type){
        $result = null;
        $DataCountry = $this->getCountryFromIP();
        $slug =  $DataCountry['country_code'] ?? 'KWD' ;
        if (Auth::guard('user')->check()){
            $userData = Auth::guard('user')->user();
            if ($userData->country_id == null){
                $Countrydefualt = Country::where('slug','KWD')->first('id');
                $data = $this->DataAllPage($Countrydefualt->id,$type);
            }else {
                $CountryData = Country::where('slug',$userData->country_id)->first('id');
                if ($CountryData == null){
                    $Countrydefualt = Country::where('slug','KWD')->first('id');
                    $data = $this->DataAllPage($Countrydefualt->id,$type);
                }else{
                    $CountryData = Country::where('slug',$slug)->first('id');
                    $data = $this->DataAllPage($CountryData->id,$type);
                }
            }
        }
        else {
            $CountryData = Country::where('slug',$slug)->first('id');
            if ($CountryData == null){
                $Countrydefualt = Country::where('slug','KWD')->first('id');
                $data = $this->DataAllPage($Countrydefualt->id,$type);
            }else{
                $CountryData = Country::where('slug',$slug)->first('id');
                $data = $this->DataAllPage($CountryData->id,$type);
            }
        }
        return view("Front.LandingPage.all", compact('data'));
    }

    public function privacy()
    {
        return view("Front.LandingPage.PrivacyPolicy");
    }
    public function fav(Request $request){
        $user=auth('user')->user();
        $fav=Fav::where('user_id', $user->id)->where('charity_id',$request->id)->first();
        if (!$fav){
            $data=[
                'charity_id'=>$request->input('id'),
                'user_id'=> $user->id,
            ];
            $repose=Fav::create($data);

            if ($repose) {
                return response()->json([
                    'message' => 'add',
                    'status' => 200,
                    'data' => $repose,
                    'clothes_id' => $request->id
                ]);
            }
        }else{
            $repose=$fav->delete();
            return response()->json([
                'message' => 'delete',
                'status' => 201,
                'data' => $repose,
                'clothes_id' => $request->id
            ]);


        }


    }


}
