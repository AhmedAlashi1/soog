<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Models\Clothes;
use App\Models\FixedAds;
use App\Models\Item;
use App\Models\Categories;
use App\Models\Country;




class ProductController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
//        return $request->all();

        $user = null;
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {

        }
//        return $user;

        if (empty($request->input('id'))) {
            return $this->outApiJson(false, 'data_required');
        }
//        try{
        $repose = Clothes::with('favorites')->find($request->input('id'));
            //return $repose;
        if ($request->cat_id){
            $similar = Clothes::where('type', '1')->where('status', '1')->where('confirm', '1')->where('cat_id', $repose->cat_id)->with('user')->orderBy('id', 'desc')->limit(3)->get(['*']);

        }

        $data = [];
        if ($repose) {
//            $title = 'title_' . $request->header('lang');
            $title = 'title_ar';
            $note = 'note_ar';
//            $note = 'note_' . $request->header('lang');
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $featured = FixedAds::with('clothes.favorites', 'clothes.user', 'clothes.country', 'clothes.governorates', 'packages', 'cat')
                ->where('end_at', '>', $now)
                ->whereHas('packages', function ($q) {
                    $q->where('type', 2);
                })
                ->where('status', '1')
                ->whereIn('home', ['2','3'])
                ->orderBy('created_at', 'desc')->limit(9)->get(['*']);

            $data['featured'] = $featured;
            $data['id'] = $repose->id;
            $data['title'] = $repose->$title;
            $data['note'] = $repose->$note;
            $data['end_date'] = $repose->end_date;
            $data['created_at'] = $repose->created_at;
            $data['price'] = $repose->price;
            $data['cat_id'] = $repose->cat_id;
            $data['views'] = $repose->views;
            $data['country_id'] = $repose->country_id;
            $data['governorates_id'] = $repose->governorates_id;

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


//            $data['brand_id'] = $repose->brand_id ? Item::find($repose->brand_id)->title_ar : null;
//            $data['educational_level_id'] = $repose->educational_level_id ? Item::find($repose->educational_level_id)->$title : null;
//            $data['specialization_id'] = $repose->specialization_id ? Item::find($repose->specialization_id)->$title : null;
//            $data['animal_type_id'] = $repose->animal_type_id ? Item::find($repose->animal_type_id)->$title : null;
//            $data['fashion_type_id'] = $repose->fashion_type_id ? Item::find($repose->fashion_type_id)->$title : null;
//            $data['subjects_id'] = $repose->subjects_id ? Item::find($repose->subjects_id)->$title : null;


            $data['user_id'] = $repose->user->id;
            $data['user_name'] = $repose->first_name;
            $data['user_email'] = $repose->user->email;
            $data['user_mobile_number'] = $repose->user->mobile_number;
            $data['user_whats_number'] = $repose->user->whats_number;
            $data['user_image'] = url('/') . '/assets/tmp/' . $repose->user->avatar;

            $data['chat_icon'] = $repose->chat == 1 ? true : false;
            $data['email_icon'] = $repose->email == 1 ? true : false;
            $data['sms_icon'] = $repose->sms == 1 ? true : false;
            $data['whatsApp_icon'] = $repose->whatsApp == 1 ? true : false;
            $data['call_icon'] = $repose->call == 1 ? true : false;

            if ($user) {
                $data['fav'] = ($repose->favorites->where('user_id', $user->id)->count() > 0) ? true : false;
                $follw = Follow::where('follow', $user->id)->where('followers', $repose->user->id)->first();
                $data['follow'] = ($follw) ? true : false;

            }
            $data['image'] = url('/') . '/assets/tmp/' . $repose->image;

            $data['images'] = [];
            $kk = 0;
            foreach ($repose->charityImages as $kk => $item) {
                //$data['images'][$kk]['id']=$item->id;
                $data['images'][$kk]['image'] = url('/') . '/assets/tmp/' . $item->image;
                $kk++;
            }
            $data['images'][$kk] = ['image' => url('/') . '/assets/tmp/' . $repose->image];


            $dddd = [];
            foreach ($similar as $k => $row) {
                $dddd[$k]['id'] = $row->id;
                $dddd[$k]['title'] = $row->$title;
                $dddd[$k]['note'] = $row->$note;
                $dddd[$k]['end_date'] = $row->end_date;
                $dddd[$k]['price'] = $row->price;
                $dddd[$k]['cat_id'] = $row->cat_id;

                $dddd[$k]['user_id'] = $row->user->id;
                $dddd[$k]['user_email'] = $row->user->email;
                $dddd[$k]['user_mobile_number'] = $row->user->mobile_number;
                $dddd[$k]['user_whats_number'] = $row->user->whats_number;

                $dddd[$k]['chat_icon'] = $row->chat == 1 ? true : false;
                $dddd[$k]['email_icon'] = $row->email == 1 ? true : false;
                $dddd[$k]['sms_icon'] = $row->sms == 1 ? true : false;
                $dddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ? true : false;
                $dddd[$k]['call_icon'] = $row->call == 1 ? true : false;

                if ($user) {
                    $dddd[$k]['fav'] = ($row->favorites->where('user_id', $user->id)->count() > 0) ? true : false;
                }
                $dddd[$k]['image'] = url('/') . '/assets/tmp/' . $row->image;
            }

//            $datas = [
//                'data' => $repose,
//                'similar' => $similar,
//
//            ];
//            return $repose;
            return view('Front.LandingPage.DetailProduct',compact('similar','repose'));
        }


    }

}
