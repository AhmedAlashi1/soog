<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Coupons;
use Carbon\Carbon;
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

class UserOrderController extends ApiController
{

    use Functions;

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
        //check user inactive
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (
            empty($request->input('products')) ||
            empty($request->input('payment_id')) ||
            empty($request->input('delivery_id')) ||
            empty($request->input('total'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }
        if ($this->user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        $data = [];
        $data['user_id'] = $this->user->id;
        $data['payment_id'] = $request->input('payment_id');
        $data['delivery_id'] = $request->input('delivery_id');
        $data['address_id'] = $request->input('address_id');
        $data['delivery_type'] = $request->input('delivery_type');
        $data['delivery_date'] = $request->input('delivery_date');
        $data['time_id'] = $request->input('time_id');
        $data['use_credit'] = $request->input('use_credit');
        $data['total_cost'] = $request->input('total');
        $data['notes'] = $request->input('notes');
        $data['status'] = 'new';
        $data['user_agent'] = $request->input('type');
        $pro_item = [];
        $ids = [];
        foreach ($request->input('products') as $item) {
            $pro_item[] = [
                'clothe_id' => $item['item_id'],
                'number' => $item['number'],
                'price' => $item['price'],
            ];
            //delete cart
            $order = $this->cart->findWhere(['user_id' => $this->user->id, 'clothe_id' => $item['item_id']])->first();
            if ($order) {
                $ids[] = $order->id;
            }
            // edit quantity
            $product = $this->cloth->find($item['item_id']);
            if (!$product) {
                return $this->outApiJson(false, 'not_found');
            }
            $err_messages = [];
            if ($product->status != 1 || $product->quntaty <= 0) {
//                $err_messages[]='المنتج '.$product->title_ar.'غير متوفر الكميه والكميه الحاليه منه هى '.$product->quntaty;
                $err_messages[]=' العدد المسموح به لطلب للمنتج '.$product->title_ar.' هو '.$product->quntaty . ' حبة لكل طلب ';
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
//                    $err_messages2[]='المنتج '.$product->title_ar.'غير متوفر الكميه والكميه الحاليه منه هى '.$product->quntaty;
                    $err_messages2[]=' العدد المسموح به لطلب للمنتج '.$product->title_ar.' هو '.$product->quntaty . ' حبة لكل طلب ';
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
        }
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
            if ($request->input('use_credit') == 1) {
                if ($this->user->credit > $request->input('total')) {
                    $credit = $request->input('total');
                    $this->user->credit -= $request->input('total');
                } else {
                    $credit = $this->user->credit;
                    $this->user->credit = 0;
                }
                $this->user->save();
                $data['credit'] = $credit;
                $this->user->promo()->createMany([['user_id' => $this->user->id, 'comment' => 'شراء من طلب ', 'credit' => $credit, 'type' => 2]]);
            }
            $order = $this->repo->create($data);
            if ($order) {
                //create order pices
                $order->pieces()->createMany($pro_item);
                //delete the cart
                $this->cart->deleteAll($ids);
                // add points to user
                $points = config('general.points');
                $user_points = round(($order->total_cost/$points),1);
                $this->user->points += $user_points;
                $this->user->save();
                //create order status
                $this->repo_status->create([
                    'order_id' => $order->id,
                    'status' => 'new',
                    'request_time' => Carbon::now(),
                    'duration' => 0,

                ]);
                $userdata = [
                    'order_id' => $order->id,
                ];
                //send email address
                $emails = explode(',', config('general.emails'));
                $emails2 = explode(',', config('general.emails2'));
                $this->sendEmail('emails.order', ['order' => $order], 'new order', $emails);
                $this->sendEmail('emails.order', ['order' => $order], 'new order', $emails2);
                return $this->outApiJson(true, 'success', $userdata);
            }
            return $this->outApiJson(false, 'create_error');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
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
                'code_limit' => $promo->code_limit,
                'code_max' => $promo->code_max,
                'type' => $promo->type,
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
        //check user inactive
        if ($this->user->status != 'active') {
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
                    $order = $this->cart->findWhere(['user_id' => $this->user->id, 'clothe_id' => $item['item_id']])->first();
                    if ($order) {
                        //create order pices
                        $this->cart->update(['user_id' => $this->user->id, 'clothe_id' => $item['item_id'], 'number' => $item['number']], $order->id);
                    } else {
                        $this->cart->create(['user_id' => $this->user->id, 'clothe_id' => $item['item_id'], 'number' => $item['number']]);
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
            $data['delivery_type_title'] = $repose->deliveryTypeTitle?$repose->deliveryTypeTitle->$title:'';
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

}
