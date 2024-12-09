<?php

namespace App\Http\Controllers;

use App\Jobs\sendNotifications;
use App\Models\AppUser;
use App\Models\Categories;
use App\Models\Clothes;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class NotifyController extends Controller
{
    public function index()
    {
        $cat = Categories::get();
//        return Carbon::now()->diffInDays('2022-10-17 19:37:45', true);

        $pro = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        $prod = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        return view('Notify.index', compact('cat', 'pro', 'prod'));
    }

    public function sendNotification(Request $request)
    {
        $message = new Message();
        $message->user_id = Auth::user()->id;
        $message->subject = $request->title ?? "";
        $message->message = mb_strtolower($request->body ?? "");
        $message->save();
        if ($request->type == 'all') {
            $Notifications = new Notifications();
            $Notifications->user_id = '0';
            $Notifications->title = $request->title ?? "";
            $Notifications->message = $request->body ?? "";
            $Notifications->status = '0';
            $Notifications->product_id = $request->pro_id;
            $Notifications->multi_product_id = $request->multi_product_id;
            $Notifications->cat_id = $request->cat_id;
            $Notifications->url = $request->url ?? "";
            $Notifications->save();
            $skip = 0;
            $take = 0;
            $count = AppUser::count();
            $c = round($count / 1000) + 1;
            for ($r = 1; $r < $c; $r++) {
                $take = 1000;

                if ($request->international == '1'){
                    $device_token = AppUser::where('device_token', '!=', '')->where('country_id' ,'!=' ,'3')->skip($skip)
                        ->take($take)->pluck('device_token')->toArray();
//                    $device_token = AppUser::where('device_token', '!=', '')->skip($skip)
//                        ->take($take)->get();
                    $skip += $take;
//                    return $device_token;
                }else{
                    $device_token = AppUser::where('device_token', '!=', '')->where('country_id' , '3')->skip($skip)
                        ->take($take)->pluck('device_token')->toArray();
                    $skip += $take;
//                    return $device_token;
                }
                $this->notification($device_token, $request->title ?? "", $request->body ?? "", $request->url ?? "", $request->cat_id ?? "", $request->pro_id ?? "", $request->multi_product_id ?? "");

            }
            return redirect()->back()->with('success', trans('notification.success'));
        }
        elseif ($request->type == 'customer') {

            $ids = array_map('intval', explode(',', implode(',', (array)$request->id_user)));
            $multi_product_id = array_map('intval', explode(',', implode(',', (array)$request->multi_product_id)));
            for ($i = 0; $i < count($ids); $i++) {
                $Notification = new Notifications();
                $Notification->user_id = $ids[$i];
                $Notification->title = $request->title ?? "";
                $Notification->message = $request->body ?? "";
                $Notification->status = '0';
                $Notification->url = $request->url ?? "";
                $Notification->product_id = $request->pro_id;
                $Notification->multi_product_id = $multi_product_id;
                $Notification->cat_id = $request->cat_id;
                $Notification->save();
            }

            if ($request->international == '1'){

                $device_token = AppUser::whereIn('id', $ids)->where('country_id' ,'!=', '3')
                    ->pluck('device_token')->toArray();

            }else{

                $device_token = AppUser::whereIn('id', $ids)
                    ->where('country_id' , '3')
                    ->pluck('device_token')->toArray();

            }


            if ($device_token) {

                if ( $multi_product_id == [0]){
                    $this->notification($device_token, $request->title ?? "", $request->body ?? "", $request->url ?? null, $request->cat_id ?? null, $request->pro_id ?? null, null);
//                    return 'a';
                }else{
                    $this->notification($device_token, $request->title ?? "", $request->body ?? "", $request->url ?? null, $request->cat_id ?? null, $request->pro_id ?? null, $multi_product_id ?? null);
//                    return 'b';
                }
//                 $this->notification($device_token, $request->title ?? "", $request->body ?? "", $request->url ?? null, $request->cat_id ?? null, $request->pro_id ?? null, $multi_product_id ?? null);

                return redirect()->back()->with('success', trans('notification.success'));
            } else {
                return redirect()->back()->with('warning', trans('notification.error'));
            }
        } else {
            return redirect()->back()->with('success', trans('notification.error'));

        }
    }

    public function index2()
    {

        return view('Notify.index2');
    }

    public function index3()
    {
        $cat = Categories::get();
        $cat2 = Categories::get();
        $pro = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        $pro2 = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        return view('Notify.notification', compact('cat', 'cat2', 'pro', 'pro2'));
    }

    public function get_notification()
    {
        $notification = Notification::orderBy('id', 'desc')->get();
        if ($notification) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $notification
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_notification(Request $request)
    {

        $notification = new Notification();
        $notification->title = $request->title;
        $notification->days = '0';
        $notification->send_at = $request->send_at;
        $notification->message = $request->body;
        $notification->url = $request->url ?? "";
        $notification->product_id = $request->pro_id ;
        $notification->multi_product_id = $request->multi_product_id ?? "";
        $notification->cat_id = $request->cat_id ;
        $notification->type = '2';
        $notification->save();
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
//        $dt = Carbon::parse($notification->send_at)->subHours(2)->timezone($timezone);
        $dt = Carbon::parse($notification->send_at)->timezone($timezone);
//        return $dt;
        dispatch(new sendNotifications($notification))->delay($dt);
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
        ]);
    }

    public function edit($id)
    {
        $notification = Notification::with(['categories' , 'product'])->find($id);
        if ($notification) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $notification
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->title = $request->title;
            $notification->days = '0';
            $notification->send_at = $request->send_at;
            $notification->message = $request->body;
            $notification->url = $request->url ?? "";
            $notification->product_id = $request->pro_id ?? "";
            $notification->cat_id = $request->cat_id ?? "";
            if ($request->multi_product_id){
                $notification->multi_product_id = $request->multi_product_id ?? [];
            }
            $notification->type = '2';
            $notification->save();
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $notification
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->delete();
            return response()->json([
                'message' => trans('category.property_delete_success'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $notification = Notification::find($id);
        $notification->status = request('status');
        $notification->update();
        return response()->json([
            // 'message' => 'Update Success',
            'status' => 200,
        ]);
    }


    public function notification($FcmToken = [], $title = "", $body = "", $url = "", $cat_id = "", $product_id = "", $multi_product_id = [])
    {
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "url" => $url,
                "cat_id" => $cat_id,
                "product_id" => $product_id,
                "multi_product_id" => $multi_product_id,
                "sound" => "default"
            ],
            "data" => [
                "title" => $title,
                "body" => $body,
                "url" => $url,
                "cat_id" => $cat_id,
                "product_id" => $product_id,
                "multi_product_id" => $multi_product_id,
                "sound" => "default"
            ]

        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
//            return response()
//            ->json(['status' => 'success', 'errors' => 0,
//            'data' => json_decode($response, true)])
//            ->header('Content-type', 'application/json');
    }
}
