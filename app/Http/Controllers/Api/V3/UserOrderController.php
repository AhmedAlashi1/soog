<?php

namespace App\Http\Controllers\Api\V3;

use App\Helpers\PusherApp;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Clothes;
use App\Models\Coupons;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pieces;
use App\Models\Setting;
use App\Models\Status;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\OrderRepository;
use App\Repositories\StatusRepository;
use App\Repositories\CartRepository;
use App\Repositories\ClothesRepository;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Criteria\AdvancedSearchCriteria;
use App\Repositories\ReviewRepository;
use App\Mail\OrderMail;

class UserOrderController extends ApiController
{

    use Functions;
//    use PusherApp;

    private $repo;
    private $repo_status;
    private $review = null;
    private $cart = null;
    private $cloth = null;

    public function __construct(Request $request, OrderRepository $repo, StatusRepository $repo_status, ReviewRepository $review, CartRepository $cart, ClothesRepository $cloth)
    {
        parent::__construct($request);
        $this->repo = $repo;
        $this->repo_status = $repo_status;
        $this->review = $review;
        $this->cart = $cart;
        $this->cloth = $cloth;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $user = auth('api')->user();
//         return $user;
        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
//        //check user inactive
//        if ($user->status != 'active') {
//            return $this->outApiJson(false, 'user_inactive');
//        }


        if (
            empty($request->input('products')) ||
            empty($request->input('payment_id')) ||
            empty($request->input('delivery_id')) ||
            empty($request->input('total'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }

//        if ($user->status != 'active') {
//            return $this->outApiJson(false, 'user_inactive');
//        }

        $data = [];
        $data['user_id'] =  $user->id;
        $data['payment_id'] = $request->input('payment_id');
        $data['delivery_id'] = $request->input('delivery_id');
        $data['address_id'] = $request->input('address_id');
        $data['delivery_type'] = $request->input('delivery_type');
        $data['delivery_date'] = $request->input('delivery_date');
        $data['time_id'] = $request->input('time_id');
        $data['use_credit'] = $request->input('use_credit');
        $data['total_cost'] = $request->input('total');
        $data['notes'] = $request->input('notes');
        $data['country_id'] = $request->header('country');
        $data['status'] = 'new';
        $data['user_agent'] = $request->input('type');
        $data['wallet_payment'] = $request->input('wallet_payment');
        $data['delivery_cost'] = $request->input('delivery_cost');
        $pro_item = [];
        $ids = [];
        $out = [];
        $out1 = [];
        $out2 = [];
        $out3 = [];

        foreach ($request->input('products') as $item) {
            $pro_item[] = [
                'clothe_id' => $item['item_id'],
                'number' => $item['number'],
                'price' => $item['price'],
            ];
//            return $request->all();
            //delete cart
//            $order = $this->cart->findWhere(['user_id' =>  $user->id, 'clothe_id' => $item['item_id']])->first();
//            $order = Cart::Where(['user_id' =>  $user->id, 'clothe_id' => $item['item_id']])->first();
//
//
//            if ($order) {
//                $ids[] = $order->id;
//            }
            // edit quantity
//            $product = $this->cloth->find($item['item_id']);
            $product = Clothes::find($item['item_id']);

            $orders_pices=Pieces::where('clothe_id',$item['item_id'])
                ->whereHas('order',function ($q){
                    $q->where('user_id',auth('api')->user()->id);
                })->with('order')
                ->get();
            if (!$product) {
                return $this->outApiJson(false, 'not_found');
            }

            $err_messages3 = [];

            if ($product->order_limit_user){
                if ($orders_pices->count() > 0 ) {
                        $pices=  $product->order_limit_user -$orders_pices->sum('number');

                        if ($pices <  $item['number']) {

//                            $err_messages3[]= $product->title_ar.'نعتذر لقد استنفذت الكمية المسموحة لك بطلب المنتج';
                            $err_messages3[]=' المنتج '.$product->title_ar.' نعتذر الكمية المتاحة لك هي '.$pices;
                            //return $this->outApiJson(false, 'product_not_available_limit');
                        }
                }elseif ($item['number'] > $product->order_limit_user){
//                    $err_messages3[]= $product->title_ar.'نعتذر لقد استنفذت الكمية المسموحة لك بطلب المنتج';
                        $err_messages3[]=' المنتج '.$product->title_ar.' نعتذر الكمية المتاحة لك من المنتج هي  '.$product->order_limit_user;
                }
            }
            if (count($err_messages3)>0){
                $outData = [];
                $outData['status'] = false;
                $outData['code'] = 167;
                $outData['message'] = implode(' - ',$err_messages3);
                return response()->json($outData, 200);
            }
//            $product = Clothes::all();
//            return $product;


            $err_messages = [];
            if ($product->status != 1 || $product->quntaty <= 0) {
//                $err_messages[]='المنتج '.$product->title_ar.'غير متوفر الكميه والكميه الحاليه منه هى '.$product->quntaty;
//                $err_messages[]=' العدد المسموح به لطلب للمنتج '.$product->title_ar.' هو '.$product->quntaty . ' حبة لكل طلب ';
                $err_messages[]= $product->title_ar.'نعتذر لقد استنفذت الكمية المسموحة لك بطلب المنتج';
                //return $this->outApiJson(false, 'product_not_available');
            }

            if (count($err_messages)>0){
                $outData = [];
                $outData['status'] = false;
                $outData['code'] = 167;
                $outData['message'] = implode(' - ',$err_messages);
                return response()->json($outData, 200);
            }

            $err_messages2 = [];
            if ($product->order_limit) {
                if ($item['number'] > $product->order_limit) {
//                    $err_messages2[]=' المنتج '.$product->title_ar.' غير متوفر الكميه والكميه الحاليه منه هى  '.$product->order_limit;
//                    $err_messages2[]=' العدد المسموح به لطلب للمنتج '.$product->title_ar.' هو '.$product->order_limit . ' حبة لكل طلب ';
                    $err_messages2[]= $product->title_ar.'نعتذر لقد استنفذت الكمية المسموحة لك بطلب المنتج';
                    //return $this->outApiJson(false, 'product_not_available_limit');
                }
            }


            if (count($err_messages2)>0){
                $outData = [];
                $outData['status'] = false;
                $outData['code'] = 167;
                $outData['message'] = implode(' - ',$err_messages2);
                return response()->json($outData, 200);
            }
            if ($product) {
                $product->quntaty = $product->quntaty - $item['number'];
                $product->save();
            }


            $out[]=$product->title_ar;
            $out1[]=$product->id;
            $out2[]=$item['price'];
            $out3[]=$item['number'];

        }

//
//        $orders_pices=Pieces::whereIn('clothe_id',$out1)
//            ->whereHas('order',function ($q){
//                $q->where('user_id',auth('api')->user()->id);
//            })
//            ->get();
//        return $orders_pices;
        try {
            //check promo codes
            if ($request->input('promo_code')) {
                $promo = Coupons::where(['code' => $request->input('promo_code'), 'status' => 1])->first();
                if (!$promo) {
                    return $this->outApiJson(false, 'promo_not_found');
                }
                if ($promo->use_number >= $promo->count_number) {
                    return $this->outApiJson(false, 'promo_not_valid');
                }
                $promo->use_number = $promo->use_number + 1;
                $promo->save();
                $data['promo_code'] = $request->input('promo_code');
            }



            $total = $data['total_cost'];
            if ($request->input('use_credit') == 1) {
                $data['credit'] = $user->credit;
//                return $request->input('use_credit');
                if ( $user->credit > $request->input('total')) {
                    $credit = $request->input('total');
                     $user->credit -= $request->input('total');
                    $total = 0;
                } else {
                    $total =  $request->input('total')- $user->credit;
                    $credit =  $user->credit;
                     $user->credit = 0;
                }
                 $user->save();
                $data['credit'] = $credit;

                 $user->promo()->createMany([['user_id' =>  $user->id, 'comment' => 'شراء من طلب ', 'credit' => $credit, 'type' => 2]]);
                if ($user->credit > 0){
                    $credit =  $user->credit;
                    $data['return_credit'] = $credit;
//                $data['credit'] = '0';
                }else{
                    $data['return_credit'] = '0';
                    $data['credit'] = '0';
                }
            }
            $data['driver_id'] = '0';
//            $data['credit'] = '0';
//            if ($user->credit > 0){
//                $credit =  $user->credit;
//                $data['return_credit'] = $credit;
////                $data['credit'] = '0';
//            }else{
//                $data['return_credit'] = '0';
////                $data['credit'] = '0';
//            }
//            $data['country_id'] = $user->country_id;

            $order = Order::create($data);



            if ($order) {

                //create order pices
                $order->pieces()->createMany($pro_item);

                //delete the cart
//                Cart::whereIn('id',$ids)->delete();
//                $this->cart->deleteAll($ids);
                // add points to user
                $points = Setting::where('key_id','points')->first()->value;
                $user_points = round(($order->total_cost/$points),1);
                 $user->points += $user_points;
                 $user->save();
                //create order status
                Status::create([
                    'order_id' => $order->id,
                    'status' => 'new',
                    'request_time' => Carbon::now(),
                    'duration' => 0,

                ]);
                $userdata = [
                    'order_id' => $order->id,
                    'url' => null,
                ];

                if ($total > 0) {
                    // add payment
                    $sub = substr( $user->mobile_number, 0, 5);
                    $number = substr( $user->mobile_number, 5);
                    if ($sub == '00965') {
                        $numbers = $number;
                    } else {
                        $numbers =  $user->mobile_number;
                    }


                    $apiURL = 'https://api.tap.company';
 
//
//                        'form_params' => [
//
//
//
//
//                            'ProductName' => json_encode($out),
//                            'ProductPrice' => json_encode($out2),
//                            'ProductQty' => json_encode($out3),
//                            'reference' => $order->id,
//                        ]
//                    ]);
                    $pay = Payment::find($request->input('payment_id'));
//                    return $pay;
                    if (!$pay) {
//                        return 'a';
                        return $this->outApiJson(false, 'not_found');
                    }

                    $currency = Country::where('id' , $user->country_id)->first();
//                    if($currency){
//
//                    }else{
//                        $currency = 'KWD';
//                    }

                    if ( $pay->id != 6){
                        $postFields = [
                            //Fill required data
                            'amount' => $total,
                            'currency' => $currency->slug ?? "KWD",
//                            'currency' => 'KWD',
//            'threeDSecure'    => false,
                            'save_card'    => false,
                            'description'    =>'دفع فاتوره رقم # ' . $order->id,
                            'statement_descriptor'    =>  'دفع فاتوره رقم # ' . $order->id,
                            'metadata'    => [
                                'udf1'    => 'test 1',
                                'udf2'    => 'test 2',
                            ],

                            'reference'    =>  [
                                'transaction'    => 'txn_0001',
                                'order'    => $order->id,
                            ],
                            'receipt'    =>  [
                                'email'    => true,
                                'sms'    => true,
                            ],
                            'customer'    =>  [
                                'first_name'    => $user->first_name,

                                'phone'    => [
                                    'country_code'    => '965',
                                    'number'    => $numbers,
                                ],
                            ],
                            'merchant'    =>  [
                                'id'    => '',
                            ],
                            'source'    =>  [
                                'id'    => $pay->slug,
//                'id'    => 'src_kw.knet',
//                'id'    => 'src_card',
                            ],
                            'post'    =>  [

                                'url'    =>  route('paymentStatus').'/?order_id='.$order->id,

                            ],
                            'redirect'    =>  [
                                'url'    => route('paymentStatus').'/?order_id='.$order->id,

                            ],
                            'ProductName' => json_encode($out),
                            'ProductPrice' => json_encode($out2),
                            'ProductQty' => json_encode($out3),

                        ];
                        $data = $this->executePayment($apiURL, $apiKey, $postFields);
//                        return $data;
                        $userdata['url'] = $data->transaction->url;
                    }

//                    $url=$data->transaction->url;


//
//                    $headers = [
//                        'x-Authorization' => env('PAYMENT_TOKEN'),
//                    ];
//                    $client = new Client([
//                        'base_uri' => 'https://api.upayments.com',
//                        //'headers' => $headers
//                    ]);
//                    $sub = substr( $user->mobile_number, 0, 5);
//                    $number = substr( $user->mobile_number, 5);
//                    if ($sub == '00965') {
//                        $numbers = $number;
//                    } else {
//                        $numbers =  $user->mobile_number;
//                    }
//                    $pay = Payment::find($request->input('payment_id'));
//                    if (!$pay) {
//                        return $this->outApiJson(false, 'not_found');
//                    }
//                    $response = $client->post('/payment-request', [
//                        'form_params' => [
//                            'merchant_id' => env('PAYMENT_MERCHANT'),
//                            'username' => env('PAYMENT_USER'),
//                            'password' => env('PAYMENT_PASSWORD'),
//                            'api_key' => bcrypt(env('PAYMENT_APP_KEY')),
//                            'order_id' => rand(111111111111111, 999999999999999),
//                            'total_price' => $total,
//                            'CurrencyCode' => 'KWD',
//                            'success_url' => route('ordersSuccess'),
//                            'error_url' => route('ordersError'),
//                            'test_mode' => 0,
//                            'whitelabled' => true,
//                            'CstFName' =>  $user->first_name,
//                            //'CstEmail' => $order->id,
//                            'CstMobile' => $numbers,
//                            'payment_gateway' => $pay->slug,
//                            'ProductTitle' => 'دفع فاتوره رقم # ' . $order->id,
//                            'ProductName' => json_encode($out),
//                            'ProductPrice' => json_encode($out2),
//                            'ProductQty' => json_encode($out3),
//                            'reference' => $order->id,
//                        ]
//                    ]);

//                    $jsonResponse = json_decode($response->getBody());
//                    //dd($jsonResponse);
//                    if ($jsonResponse->status == 'success') {
//                        $userdata['url'] = $jsonResponse->paymentURL;
//                    }
                }

                // send notification
                PusherApp::pushNotifications([
                    'message' => 'هناك طلب جديد في الطلبات الجديده',
                    'type' => 'cart-submitted',
                    'id' => $order->id
                ]);
                //send email address
//                $emails = explode(',', config('general.emails'));
//                $emails2 = explode(',', config('general.emails2'));
                $emails = Setting::where('key_id', 'emails')->first()->value;
                $emails2 = Setting::where('key_id', 'emails2')->first()->value;
                \Mail::to($emails)->send(new OrderMail($order));
                \Mail::to($emails2)->send(new OrderMail($order));
//                \Mail::to('info@ocean-it.co')->send(new OrderMail($order));
//                \Mail::send('emails.order', ['order' => $order], function ($message) use ($subject, $emails) {
//                    $message->to($emails)->subject($subject)->from(env('APP_EMAIL'), env('APP_NAME'));
//                });
//                $this->sendEmail('emails.order', ['order' => $order], 'new order', $emails);
//                $this->sendEmail('emails.order', ['order' => $order], 'new order', $emails2);
                // add order to crm


                //ملغي من طرف نادر
//                $headers = [
//                    'Accept' => 'application/json',
//                    'Content-Type' => 'application/json',
//                ];
//                $client = new Client([
//                    'base_uri' => 'https://gpsmajestic.com:9017/',
//                    'headers'=>$headers
//                ]);
//                $response = $client->post('/api/Majestic/Token', [
//                    'body' => json_encode([
//                        'userName' => env('MAJESTIC_USER').' '.env('MAJESTIC_USER_2'),
//                        'password' => env('MAJESTIC_PASSWORD'),
//                    ])
//                ]);
//
//                $jsonResponse = json_decode($response->getBody());
//                if($jsonResponse->message == 'Success'){
//                    $dataValue = $jsonResponse->dataValue;
//                    $token = $jsonResponse->token;
//                   //  add order to crm
//                    $headers = [
//                        'Accept' => 'application/json',
//                        'Content-Type' => 'application/json',
//                        'Authorization' => 'Bearer '.$token,
//                    ];
//                    $client = new Client([
//                        'base_uri' => 'https://gpsmajestic.com:9017/',
//                        'headers'=>$headers
//                    ]);
//                    $response = $client->post('/api/Majestic/SaveJobOrder', [
//                        'body' => json_encode([
//                            'companyId' => $dataValue->companyId,
//                            'userId' => $dataValue->userId,
//                            'orderNo' => 'Hk_'.(string)$order->id,
//                            'orderDate' => Carbon::now()->toIso8601String(),
//                            'orderAmount' => $total,
//                            'deliveryCustomerName' =>  $user->first_name,
//                            'deliveryCustomerMobile' => str_replace('00965','', $user->mobile_number),
//                            'deliveryNotes' => $order->notes,
//                            'deliveryGovernorate' => $order->address?$order->address->cityData->title_ar:'',
//                            'deliveryArea' => $order->address?$order->address->regionData->title_ar:'',
//                            'deliveryBlock' => $order->address?$order->address->block:'',
//                            'deliveryStreet' => $order->address?$order->address->street:'',
//                            'deliveryAvenue' => $order->address?$order->address->avenue:'',
//                            'deliveryBuilding' => $order->address?$order->address->building:'',
//                            'deliveryFloor' => $order->address?$order->address->floor:'',
//                            'deliveryFlat' => $order->address?$order->address->flat:'',
//                            'deliveryAddress' => $order->address?$order->address->address:'',
//                            'deliveryLat' => '0.0',
//                            'deliveryLng' => '0.0',
//                            'paymentMethod' => $order->payment?$order->payment->title_ar:'',
//
//                        ])
//                    ]);
//                    $jsonResponse = json_decode($response->getBody());
//                    //dd($jsonResponse);
//                }
/// نهاية
                return $this->outApiJson(true, 'success', $userdata);
            }

            return $this->outApiJson(false, 'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }catch (RequestException $ex) {

            return $this->outApiJson(true, 'success', $userdata);
        } catch (ClientException $ex) {
            //return $this->outApiJson(true, 'success', ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()]);
            //return ['status' => false, 'response' => $ex->getResponse()->getBody() !== null ? $ex->getResponse()->getBody() : $ex->getMessage()];
            return $this->outApiJson(true, 'success', $userdata);
        }
    }

    public function checkPromo(Request $request)
    {
        if (
        empty($request->input('promo_code'))) {
            return $this->outApiJson(false, 'data_required');
        }
        try {
            $promo = Coupons::where(['code' => $request->input('promo_code'), 'status' => 1])->first();
            if (!$promo) {
                return $this->outApiJson(false, 'promo_not_valid');
            }
            //check code valid
            $now = Carbon::now();
            $date = Carbon::parse($promo->end_at);
            if ($date->lt($now)) {
                return $this->outApiJson(false, 'promo_not_valid');
            }
            if ($promo->use_number >= $promo->count_number) {
                return $this->outApiJson(false, 'promo_not_valid');
            }
            $data = [
                'code' => $promo->code,
                'type' => $promo->type,
                'code_max' => $promo->code_max,
                'discount_type' => $promo->type == 1 ? 'fixed_number' : 'percentage',
                'discount' => $promo->type == 1 ? $promo->discount : (string)$promo->percent,
            ];

            return $this->outApiJson(true, 'success', $data);
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function cart(Request $request)
    {
        $user = auth('api')->user();
        //check user inactive
        if ( $user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (
        empty($request->input('cart'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }
        try {
            $error_ids = [];
            $ids = [];
            foreach ($request->input('cart') as $item) {
                $ids[] = $item['item_id'];
                $product = $this->cloth->find($item['item_id']);
                if ($product->status != 1 || $product->quntaty <= 0) {
                    $error_ids[] = $item['item_id'];
                } else {
                    $order = $this->cart->findWhere(['user_id' =>  $user->id, 'clothe_id' => $item['item_id']])->first();
                    if ($order) {
                        //create order pices
                        $this->cart->update(['user_id' =>  $user->id, 'clothe_id' => $item['item_id'], 'number' => $item['number']], $order->id);
                    } else {
                        $this->cart->create(['user_id' =>  $user->id, 'clothe_id' => $item['item_id'], 'number' => $item['number']]);
                        //return $this->outApiJson(true, 'success');
                    }
                }
            }
            if (count($error_ids) == 0) {
                return $this->outApiJson(true, 'success');
            }
            if (count($ids) == count($error_ids)) {
                return response()->json(['status' => false, 'code' => 168, 'message' => trans('api.not_available')], 200);
            }

            if (count($ids) > count($error_ids)) {
                return response()->json(['status' => true, 'code' => 200, 'message' => trans('api.not_available_some')], 200);
            }

        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function checkCart(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }
        try {
            $title = 'title_' . app()->getLocale();
            $order = $this->cart->findWhere(['user_id' => $this->user->id]);
            if ($order) {
                $error = [];
                foreach ($order as $row) {
                    $product = $this->cloth->find($row->clothe_id);
                    if ($product) {
                        if ($product->quntaty < $row->number) {
                            $error[] = ['item_id' => $product->id];
                        }
                    }
                }
                //create order pices
                if ($error) {
                    return $this->outApiJson(false, 'outOfStock', ['out_of_stock' => $error]);
                }
                return $this->outApiJson(true, 'success');
            } else {
                $this->cart->create(['user_id' => $this->user->id, 'clothe_id' => $request->input('item_id'), 'number' => $request->input('number')]);
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function cartDelete(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (
        empty($request->input('id'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }
        try {
            $order = $this->cart->delete($request->input('id'));
            if ($order) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'update_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function clearCart(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }
        try {
            $order = $this->cart->clear($this->user->id);
            if ($order) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'update_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function getData(Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage) {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('user_id', $this->user->id, 'eq');
        //$where_obj->pushWhereIn('status',['new','accept']);
        $where_obj->pushOrder('id', 'desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $d['data'] = [];
        $title = 'title_' . app()->getLocale();
        foreach ($paginate->items() as $k => $row) {
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['status'] = trans('app.status_' . $row->status);
            $d['data'][$k]['payment'] = ($row->payment) ? $row->payment->$title : '';
            $d['data'][$k]['total_cost'] = $row->total_cost;
            $d['data'][$k]['delivery'] = ($row->delivery) ? $row->delivery->$title : '';
            $d['data'][$k]['cost'] = ($row->delivery) ? $row->delivery->cost : '';
            $last_activity = new Carbon($row->created_at);
            $last_activity->setLocale('ar');
            $d['data'][$k]['from_date'] = $last_activity->diffForHumans();
            $d['data'][$k]['date'] = $this->getDataChat($row->created_at)['date'];
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true, 'success', ['count_total' => $paginate->total(), 'count' => count($d['data']), 'pages' => ceil($paginate->total() / $length), 'data' => $d['data']]);
    }

    public function getDataCart(Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 1000;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage) {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('user_id', $this->user->id, 'eq');
        $where_obj->pushOrder('id', 'desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->cart->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->cart->paginate($length);
        $d['data'] = [];
        $title = 'title_' . app()->getLocale();
        $i = 0;
        foreach ($paginate->items() as $k => $row) {
            if ($row->clothe) {
                $d['data'][$i]['id'] = $row->id;
                $d['data'][$i]['number'] = $row->number;
                $d['data'][$i]['item'] = ($row->clothe) ? $row->clothe->$title : '';
                $d['data'][$i]['price'] = ($row->clothe->price_after) ? $row->clothe->price_after : $row->clothe->price;
                $d['data'][$i]['order_limit'] = ($row->clothe) ? $row->clothe->order_limit : null;
                $d['data'][$i]['item_id'] = $row->clothe_id;
                $d['data'][$i]['image'] = $row->clothe ? url('/') . '/assets/tmp/' . $row->clothe->image : '';
                $i++;
            }
        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true, 'success', ['count_total' => count($d['data']), 'count' => count($d['data']), 'pages' => ceil($paginate->total() / $length), 'data' => $d['data']]);
    }

    /**
     * @param $type
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeOrders($type, Request $request)
    {

        $length = ($request->input('count')) ? $request->input('count') : 10;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;
        LengthAwarePaginator::currentPageResolver(function () use ($perPage) {
            return $perPage;
        });
        $where_obj = new \App\Repositories\Criteria\WhereObject();
        $where_obj->pushWhere('user_id', $this->user->id, 'eq');
        if ($type == 'complete') {
            $where_obj->pushWhereIn('status', ['complete', 'shipping']);
        } else {
            $where_obj->pushWhereIn('status', ['new', 'pay_pending', 'shipping_complete']);
        }
        $where_obj->pushOrder('id', 'desc');
        $push = new \App\Repositories\Criteria\AdvancedSearchCriteria;
        $push::setWhereObject($where_obj);
        $this->repo->pushCriteria(new AdvancedSearchCriteria());
        $paginate = $this->repo->paginate($length);
        $title = 'title_' . app()->getLocale();

        $d['data'] = [];
        foreach ($paginate->items() as $k => $row) {
            $d['data'][$k]['id'] = $row->id;
            $d['data'][$k]['status'] = trans('app.status_' . $row->status);
            $d['data'][$k]['payment'] = ($row->payment) ? $row->payment->$title : '';
            $d['data'][$k]['total_cost'] = $row->total_cost;
            $d['data'][$k]['delivery'] = ($row->delivery) ? $row->delivery->$title : '';
            $d['data'][$k]['cost'] = ($row->delivery) ? $row->delivery->cost : '';
            $last_activity = new Carbon($row->created_at);
            $last_activity->setLocale('ar');
            $d['data'][$k]['from_date'] = $last_activity->diffForHumans();
            $d['data'][$k]['date'] = $this->getDataChat($row->created_at)['date'];

        }
        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true, 'success', ['count_total' => $paginate->total(), 'count' => count($d['data']), 'pages' => ceil($paginate->total() / $length), 'data' => $d['data']]);
    }

    public function getRow(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (empty($request->input('id'))) {
            return $this->outApiJson(false, 'data_required');
        }
        $repose = $this->repo->find($request->input('id'));
        $data = [];
        if ($repose) {
            $title = 'title_' . app()->getLocale();
            $data['id'] = $repose->id;
            $data['status'] = trans('app.status_' . $repose->status);
            $data['total'] = $repose->total_cost;
            $data['payment'] = ($repose->payment) ? $repose->payment->$title : '';
            $data['delivery'] = ($repose->delivery) ? $repose->delivery->$title : '';
            $data['delivery_cost'] = ($repose->delivery) ? $repose->delivery->cost : '';
            $data['promo_code'] = $repose->promo?$repose->promo:null;
//            $data['promo_code_type'] = $repose->promo?$repose->promo->type:1;
//            if ($repose->promo){
//                $data['discount'] = $repose->promo->type==1?$repose->promo->discount:$repose->promo->percent;
//            }else{
//                $data['discount'] = '';
//            }
            $data['delivery_type'] = $repose->delivery_type;
            $data['delivery_date'] = $repose->delivery_date;
            $data['time_id'] = $repose->time_id;
            $data['use_credit'] = $repose->use_credit;
            $data['credit'] = $repose->credit;
            $data['notes'] = $repose->notes;
            if ($repose->address) {
                $data['cost_delivery'] = ($repose->address->regionData) ? $repose->address->regionData->delivery_cost : null;
                $data['address'] = [
                    'id' => $repose->address->id,
                    'address' => $repose->address->address,
                    'lat' => $repose->address->lat,
                    'lng' => $repose->address->lng,
                    'title' => $repose->address->title,
                    'street' => $repose->address->street,
                    'block' => $repose->address->block,
                    'city' => $repose->address->city,
                    'governate' => $repose->address->governate,
                    'floor' => $repose->address->floor,
                    'flat' => $repose->address->flat,
                    'building' => $repose->address->building,
                    'avenue' => $repose->address->avenue,
                    'type' => $repose->address->type,
                    'notes' => $repose->address->notes,
                    'city_id' => $repose->address->city_id,
                    'region_id' => $repose->address->region_id,
                    'region' => $repose->address->regionData ? [
                        'title' => $repose->address->regionData->$title,
                        'delivery_cost' => $repose->address->regionData->delivery_cost,
                        'order_limit' => $repose->address->regionData->order_limit,
                    ] : null,
                ];
            } else {
                $data['cost_delivery'] = null;
                $data['address'] = null;
            }
            $last_activity = new Carbon($repose->created_at);
            $last_activity->setLocale(app()->getLocale());
            $data['from_date'] = $last_activity->diffForHumans();
            $data['created_at'] = $repose->created_at;
            $data['products'] = [];
            foreach ($repose->pieces as $kk => $item) {
                $data['products'][$kk]['title'] = $item->clothe->$title;
                $data['products'][$kk]['item_id'] = $item->clothe_id;
                $data['products'][$kk]['image'] = url('/') . '/assets/tmp/' . $item->clothe->image;
                $data['products'][$kk]['number'] = $item->number;
                $data['products'][$kk]['price'] = $item->price;
                $data['products'][$kk]['price_before'] = $item->clothe->price;
                $data['products'][$kk]['price_after'] = $item->clothe->price_after;
                $data['products'][$kk]['order_limit'] = $item->clothe->order_limit;
                $data['products'][$kk]['end_offer'] = $item->clothe->end_offer;
                $data['products'][$kk]['quntaty'] = $item->clothe->quntaty;
            }
            return $this->outApiJson(true, 'success', $data);
        }
        return $this->outApiJson(false, 'pdo_exception');
    }

    public function paymentStatus(Request $request)
    {
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (empty($request->input('order_id'))) {
            return $this->outApiJson(false, 'data_required');
        }
        $repose = $this->repo->find($request->input('order_id'));
        $data = [];
        if ($repose) {
            $repose->payment_status = $request->input('status');
            if ($request->input('status') == 0) {
                $repose->status = 'shipping';
                //update products quantaty
                foreach ($repose->pieces as $row) {
                    // edit quantity
                    $product = $this->cloth->find($row->clothe_id);
                    if ($product) {
                        $product->quntaty = $product->quntaty + $row->number;
                        $product->save();
                    }
                }
                if ($repose->credit) {
                    $repose->user->credit += $repose->credit;
                    $repose->user->save();
                }
            }
            $repose->save();
            return $this->outApiJson(true, 'success');
        }
        return $this->outApiJson(false, 'pdo_exception');
    }

    public function sendReview(Request $request)
    {
        if (
            empty($request->input('id')) ||
            empty($request->input('rate'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }
        $order = $this->repo->find($request->input('id'));
        if (!$order) {
            return $this->outApiJson(false, 'order_not_found');
        }
        $data = $this->review->findWhere(['user_id' => $order->user_id, 'order_id' => $request->input('id'), 'driver_id' => $order->user_id])->first();
        if ($data) {
            return $this->outApiJson(false, 'already_rate');
        }
        try {
            $save = $this->review->create(['user_id' => $order->user_id, 'order_id' => $request->input('id'), 'driver_id' => $order->user_id, 'rate' => $request->input('rate')]);
            if ($save) {
                return $this->outApiJson(true, 'success');
            }
            return $this->outApiJson(false, 'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function deliverNow(Request $request)
    {
        if (
        empty($request->input('id'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }
        $order = $this->repo->find($request->input('id'));
        if (!$order) {
            return $this->outApiJson(false, 'order_not_found');
        }

        try {
            $message = 'العميل صاحب الطلب رقم #' . $request->input('id') . 'فى انتظارك الان فى مكان تسلم الطلب';
            $subject = 'استلام الطلب';
            $token = $order->driver->registration_id;
            $this->sendPush($request->input('id'), $message, $subject, '', $token, 0, $order->driver_id, 'car_finish');
            return $this->outApiJson(false, 'success');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function later(Request $request)
    {
        if (
        empty($request->input('id'))

        ) {
            return $this->outApiJson(false, 'data_required');
        }
        $order = $this->repo->find($request->input('id'));
        if (!$order) {
            return $this->outApiJson(false, 'order_not_found');
        }

        try {
            $message = 'العميل صاحب الطلب رقم #' . $request->input('id') . 'غير متواجد الان وسوف يقوم باستلام الطلب من المغسلة';
            $subject = 'استلام الطلب';
            $token = $order->driver->registration_id;
            $this->sendPush($request->input('id'), $message, $subject, '', $token, 0, $order->driver_id, 'car_finish');
            return $this->outApiJson(false, 'success');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
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
