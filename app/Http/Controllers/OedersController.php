<?php

namespace App\Http\Controllers;

use App\Exports\ExportOrders;
use App\Models\AppUser;
use App\Models\Notifications;
use App\Models\Order;
use App\Http\Repositories\OrderRepositories;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Pieces;
use App\Models\Clothes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OedersController extends Controller
{
    public function orders (){
        $payment = Payment::get();
//        $order = Order::where('id' , 16609)->with(['user' , 'payment' , 'address' , 'deliveryTypeTitle' , 'pieces.clothe'
//            , 'pieces'=> function($query){
//            $query->withCount('clothe');
//        }])->get();
//
//
//        return $order;
        return view('order.index' , compact('payment'));
    }

    public function get_orders (Request$request , OrderRepositories $orderRepo){

//        $categories = Order::with(['user' , 'payment' , 'address' , 'deliveryTypeTitle' , 'pieces'])->get();

        $dataTable = $orderRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);

//        if ($categories) {
//            return response()->json([
//                'message' => 'Data Found',
//                'status' => 200,
//                'data' => $categories
//            ]);
//        } else {
//            return response()->json([
//                'message' => 'Data Not Found',
//                'status' => 404,
//            ]);
//        }
    }

    public function add_category (Request $request){

        Order::create($request);
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $category
        ]);
    }



    public function edit ($id){
        $category = Order::find($id);
        if ($category) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $category
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        $category = Order::find($id);
        if ($category) {
            $category->update($request);
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $category
            ]);
            }
          else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete ($id){
        $category = Order::find($id);
        if ($category) {
            $category->delete();
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

    public function ordersAll (Request $request){
        if($request->type == '1'){
            $Order = Order::whereIn('id' , $request->ids);
            if ($Order) {

                $Order->delete();

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
        if($request->type == '2'){

            $Order = Order::whereIn('id' , $request->ids)->where('status' , '!=' , 'complete')->with('user');
                if ($Order) {
                    $Order->update(['status' => "new"]);
//                    return $Order->get();
                    foreach ( $Order->get() as $value){
                        $this->seveNotfication($value->user_id, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى قبول الطلب',$value->id);
                        $this->notification($value->user->device_token, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى قبول الطلب',$value->id);

                    }
                    $device_token = AppUser::where('device_token', '!=', '')->whereIn('id' ,$Order->pluck('user_id')->toArray())->pluck('device_token')->toArray();


                    return response()->json([
                        'message' => trans('category.success_update_property'),
                        'status' => 200,
                    ]);
                }
                else {
                    return response()->json([
                        'message' => 'Data Not Found',
                        'status' => 404,
                    ]);
                }
        }
        if($request->type == '3'){
            $Order = Order::whereIn('id' , $request->ids)->where('status' , '!=' , 'complete');
            if ($Order) {
                $Order->update(['status' => "pay_pending"]);
                foreach ( $Order->get() as $value){
                    $this->seveNotfication($value->user_id, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تجهيز الطلب',$value->id);
                    $this->notification($value->user->device_token, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تجهيز الطلب',$value->id);

                }
                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }
        if($request->type == '4'){
            $Order = Order::whereIn('id' , $request->ids)->where('status' , '!=' , 'complete');
            if ($Order) {
                foreach ( $Order->get() as $key){
                    if ($key->return_credit > 0){
                        $sum = $key->return_credit + $key->credit;
                        $app_user = AppUser::where('id' , $key->user_id)->first();
                        $app_user->credit = $sum;
                        $app_user->save();
                    }
                }
                $Order->update(['status' => "shipping"]);
                foreach ( $Order->get() as $value){
                    $this->seveNotfication($value->user_id, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى الغاء الطلب',$value->id);
                    $this->notification($value->user->device_token, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى الغاء الطلب',$value->id);
                }
                $Pieces = Pieces::where('order_id' , $Order->first()->id)->get();
                foreach ($Pieces as $pieces){
                    $product = Clothes::where('id' , $pieces->clothe_id)->first();
                    $product->update([
                        'quntaty' => $product->quntaty + $pieces->number,
                    ]);
                }

                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }
        if($request->type == '5'){
            $Order = Order::whereIn('id' , $request->ids)->where('status' , '!=' , 'complete');
            if ($Order) {
                $Order->update(['status' => "shipping_complete"]);
                foreach ( $Order->get() as $value){
                    $this->seveNotfication($value->user_id, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تم شحن الطلب',$value->id);
                    $this->notification($value->user->device_token, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تم شحن الطلب',$value->id);

                }
                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }
        if($request->type == '6'){
            $Order = Order::whereIn('id' , $request->ids);
            if ($Order) {
                $Order->update(['status' => "complete"]);
//                foreach ( $Order->get() as $value){
//                    $this->seveNotfication($value->user_id, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تم تاكيد استلام الطلب',$value->id);
//                    $this->notification($value->user->device_token, 'تغيير حالة الطلب',' تم تغيير حالة الطلب الخاص بك الى تم تاكيد استلام الطلب',$value->id);
//
//                }
                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }
        if($request->type == '7'){
//            return $request->all();
            $Order = Order::where('id' , $request->idw);
            if ($Order) {
                $Order->update(['comment' => $request->notes]);

                return response()->json([
                    'message' => trans('category.success_update_property'),
                    'status' => 200,
                ]);
            } else {
                return response()->json([
                    'message' => 'Data Not Found',
                    'status' => 404,
                ]);
            }
        }

        if($request->type == '8'){
//            return $request->ids;
            $order = Order::whereIn('id' , $request->ids)
                ->with('user' , 'payment' , 'address.regionData' , 'deliveryTypeTitle' , 'pieces' , 'promo')
                ->get();
//            return $order;
            return view('order.multiPDF' , compact('order'));
        }

    }

    public function updateStatus(Request $request)
    {
        // return $request->all();
        $categories = Order::whereIn('id' , $request->ids);
//        if($request->status == 'new'){
//            $categories->status = 'shipping';
//        }
//        if($request->status == 'shipping'){
//            $categories->status = 'shipping_complete';
//        }
//        if($request->status == 'shipping_complete'){
//            $categories->status = 'complete';
//        }
//        $categories->status = 'complete';

        $categories->update(['status' => 'complete']);
        return response()->json([
            // 'message' => 'Update Success',
            'status' => 200,
        ]);
    }

    public function flters(Request $request , OrderRepositories $orderRepo)
    {
        $dataTable = $orderRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);

//        $payment_status = $request->payment_status ;
//        $type_customer = $request->type_customer ;
//        $entry_status = $request->entry_status;
//        $cat_id = $request->cat_id;
//
//        $this->payment_status = $request->payment_status ;
//        $this->type_customer = $request->type_customer ;
//        $this->entry_status = $request->entry_status;
//        $this->cat_id = $request->cat_id;
//        $order = Order::with(['user' , 'payment' , 'address' , 'deliveryTypeTitle' , 'pieces']);
//        if (!empty($payment_status)) {
//            $order->where('status' , $payment_status);
//        }
//        elseif (!empty($type_customer)) {
//            $order->where('payment_id' , $type_customer);
//        }
//        elseif (!empty($entry_status)) {
//            $u = AppUser::where('mobile_number', $entry_status)->first();
//            if($u){
//                $order->where('user_id' , $u->id);
//            }else{
//                $order->where('user_id' , 00000000);
//            }
//
//        }
//        elseif (!empty($cat_id)) {
//            $order->where('payment_status' , $cat_id);
//        }
//        else{
//            $order->get();
//        }
//        $order=$order->get();
//        // $order = Order::with(['user' , 'payment' , 'address' , 'deliveryTypeTitle' , 'pieces'])->first();
//        if($order){
//            return response()->json([
//                'message' => 'Data Found',
//                'status' => 200,
//                'data' => $order
//            ]);
//        }
//        else{
//            return response()->json([
//                'message' => 'Data Not Found',
//                'status' => 404,
//            ]);
//        }
    }

    public function export(Request $request){
//        return $request;
        return Excel::download(new ExportOrders($request), 'Orders.xlsx');
    }

    public function DownloadOrderPDF($id) {

        $order = Order::with('user' , 'payment' , 'address.regionData' , 'deliveryTypeTitle' , 'pieces' , 'promo')->find($id);
//        $stting =Setting::all();
//        return $order;

//        $pdf = PDF::loadView('order.PrintOrder', compact('order'));
////        return $pdf;

        return view('order.PrintOrder' , compact('order'));
    }

    public function MULTIDownloadOrderPDF(Request $request) {
//        return ;
        $ids = array_map('intval', explode(',' , $request->ids));
//        return $explode_id;
        $order = Order::whereIn('id' , $ids)->with('user' , 'payment' , 'address.regionData' , 'deliveryTypeTitle' , 'pieces' , 'promo')->get();

        return view('order.multiPDF' , compact('order'));
    }

    public function notification($FcmToken = [], $title = "", $body = "", $order_id = "")
    {
        $data = [
            "to" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "order_id"=>$order_id,
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
    public function seveNotfication($user_id,$title ,$message,$order_id){
        $Notifications = new Notifications();
        $Notifications->user_id = $user_id;
        $Notifications->title =$title ?? "";
        $Notifications->message = $message ?? "";
        $Notifications->status = '0';
        $Notifications->order_id = $order_id;
        $Notifications->save();
    }

}
