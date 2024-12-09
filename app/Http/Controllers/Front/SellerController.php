<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Charge;
use App\Models\Clothes;
use App\Models\Fav;
use App\Models\Follow;
use App\Models\Governorates;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Setting;
use App\Models\SmsGate;
use App\Models\SmsLog;
use App\Repositories\AppUsersRepository;
use Illuminate\Http\Request;
use App\Helpers\SmsGateways;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\Hash;
use App\Http\Controllers\ApiController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Twilio;
use Laravel\Socialite\Facades\Socialite;



class SellerController extends Controller
{
    public function show(Request $request)
    {
        $users=null;
        try{
            $users = auth('api')->user();
        }catch (JWTException $e) {

        }

//        $user = auth('api')->user();
        $user = AppUser::find($request->input('id'));
        $length = ($request->input('count')) ? $request->input('count') : 10;

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false, 'user_inactive');
//        }
        $title = 'title_'.$request->header('lang');
        $region = null;

        if ( $user->addres) {
            if ( $user->addres->regionData) {
                $country_id=Governorates::where('id',$user->addres->city_id)->first();
                $region = [
                    'id' =>  $user->addres->regionData->id,
                    'title' =>  $user->addres->regionData->$title,
                    'delivery_cost' =>  $user->addres->regionData->delivery_cost,
                    'order_limit' =>  $user->addres->regionData->order_limit,
                    'country_id' => $country_id->country_id,

                ];
            }
        }
        $country=Country::where('id',$user->country_id)->where('status','1')->first();

        $follow=Follow::where('follow',$user->id)->count();
        $followers=Follow::where('followers',$user->id)->count();
        $clothes=Clothes::where('user_id',$user->id)->count();
        $clothes_user= Clothes::query();
        $clothes_user->where('type','1')->where('status','1')
            ->where('confirm','1')->where('user_id',$user->id)
            ->with('user','country')->orderBy('id','desc');

        $clothes_users = $clothes_user->paginate(10);
        $ddd = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');
        foreach($clothes_users->items() as $k=>$row){
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

            $ddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
            $ddd[$k]['email_icon'] = $row->email == 1 ?true : false;
            $ddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
            $ddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
            $ddd[$k]['call_icon'] = $row->call == 1 ?true : false;


            if($users) {
                $ddd[$k]['fav'] = ($row->favorites->where('user_id', $users->id) ->count() > 0)?true: false;
            }
            $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
        }
        if($users) {
            $follw = Follow::where('follow', $user->id)->where('followers', $users->id)->first();
        }else{
            $follw = null;
        }
        $user_data = [
            'user_id' =>  $user->id,
            'mobile' =>  $user->mobile_number,
            'email' =>  $user->email,
            'first_name' =>  $user->first_name,
            'address' =>  $user->addres,
            'country_id' =>  $user->country_id,
            'avatar' => asset("assets/tmp/" .  $user->avatar),
            'region' => $region,
            'whats_number' => $user->whats_number,
            'note' => $user->note,
            'follow' => $follow,
            'followers' => $followers,
            'advertisements' => $clothes,

            'follows' => ($follw)?true: false,



        ];
        $data=[
            'user_data' => $user_data,
            'count_total' => $clothes_users->total(),
            'nextPageUrl' => $clothes_users->nextPageUrl(),
            'pages'=>ceil($clothes_users->total()/$length),
            'clothes'=>$ddd,

        ];
        $count_total = $clothes_users->total();
        $clothes_items = $clothes_users->items();
        return view("Front.LandingPage.Seller",compact('user','follow','followers','count_total','clothes_items'));
    }
}

