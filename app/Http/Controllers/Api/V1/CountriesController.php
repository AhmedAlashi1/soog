<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Helpers\Functions;
use App\Repositories\CountriesRepository;
use App\Repositories\CityRepository;
use App\Repositories\Criteria\AdvancedSearchCriteria;
class CountriesController extends ApiController
{
    use Functions;
    private $repo;
    private $city;

    public function __construct(Request $request,CountriesRepository $repo,CityRepository $city)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->city = $city;
    }

    public function index()
    {
        try {
            $all=$this->repo->all(['*']);
            $data=[];
            foreach($all as $row){
                $data[]=[
                    'id'=>$row->id,
                    'name'=>$row->name_ar,
                ];

            }
            return $this->outApiJson(true,'success',['count'=>count($data),'data'=>$data]);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function getRegion(Request $request)
    {
        try {
            if($request->input('city_id')){
                $where_obj = new \App\Repositories\Criteria\WhereObject();
                $where_obj->pushWhere('country_id',$request->input('city_id'),'eq');
                $where_obj->pushOrder('id','desc');
                $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
                $push::setWhereObject($where_obj);
                $this->city->pushCriteria(new AdvancedSearchCriteria());
            }
            $all=$this->city->all(['*']);
            $data=[];
            foreach($all as $row){
                $data[]=[
                    'id'=>$row->id,
                    'name'=>$row->name,
                ];

            }
            return $this->outApiJson(true,'success',['count'=>count($data),'data'=>$data]);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function getZons(Request $request)
    {
        try {
            if($request->input('region_id')){
                $where_obj = new \App\Repositories\Criteria\WhereObject();
                $where_obj->pushWhere('city_id',$request->input('region_id'),'eq');
                $where_obj->pushOrder('id','desc');
                $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
                $push::setWhereObject($where_obj);
                $this->zone->pushCriteria(new AdvancedSearchCriteria());
            }
            $all=$this->zone->all(['*']);
            $data=[];
            foreach($all as $row){
                $data[]=[
                    'id'=>$row->id,
                    'name'=>$row->name,
                ];

            }
            return $this->outApiJson(true,'success',['count'=>count($data),'data'=>$data]);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function ordersError(Request $request){

        echo 'error';
    }
    public function ordersSuccess(Request $request){

        echo 'success';
    }


    public function paymentStatus(Request $request)
    {

        try{
//            $apiKey = 'sk_test_Zcei7lgtRAM6XKof8rY9QFw1';
            $apiKey = 'sk_live_QRHvKxnkDctZmP9CYSWFX6gd';
            $charge_id=$request->tap_id;
            $apiURL = 'https://api.tap.company/v2/charges/'.$charge_id;
            $data = $this->callAPI($apiURL, $apiKey,[],'GET');
//            return $request->order_id;
            if ($data->status == 'INITIATED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');

            }elseif ($data->status == 'ABANDONED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'CANCELLED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');

            }
            elseif ($data->status == 'FAILED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'DECLINED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'RESTRICTED'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'CAPTURED'){
                $order=Order::find($request->order_id);
                $order->payment_status='1';
                $order->update();
                return  redirect()->route('ordersSuccess');

            }
            elseif ($data->status == 'VOID'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'TIMEDOUT'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
                return  redirect()->route('ordersError');
            }
            elseif ($data->status == 'UNKNOWN'){
                $order=Order::find($request->order_id);
                $order->payment_status='0';
                $order->update();
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
