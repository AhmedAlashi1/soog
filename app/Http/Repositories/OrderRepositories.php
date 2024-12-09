<?php

namespace App\Http\Repositories;

use App\Models\Order;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;

class OrderRepositories
{

    public function getDataTableClasses(array $data)
    {
        $query = Order::query();
//            ->whereBetween('id',['27328','27329']);
        $skip = $data['start'] ?? 0;
        $take = $data['length'] ?? 25;
        $name = $data['name'] ?? null;
        $id_order = $data['id_order'] ?? 0;
        $phone = $data['phone'] ?? null;
        $payment_method = $data['payment_method'] ?? null;
        $status = $data['status'] ?? null;
        $payment_status = $data['payment_status'] ?? null;
        $created_at = $data['created_at'] ?? null;
        $created_at_to = $data['created_at_to'] ?? null;
        $country = $data['country_id'] ?? null;

        if ($name) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('first_name', 'like', '%' . $name . '%');
            });
        }
        if ($created_at_to and $created_at ){
//            $query->whereBetween('created_at',[$created_at,$created_at_to]);
           $query ->whereDate('created_at', '>=',$created_at)
            ->whereDate('created_at', '<=',$created_at_to);
            }elseif ($created_at) {
            $query->whereDate('created_at',$created_at);
            }



        if ($phone) {
            $query->whereHas('user', function ($query) use ($phone) {
                $query->where('mobile_number', 'like', '%' . $phone . '%');
            });
        }

        if ($payment_method) {
            $query->whereHas('payment', function ($query) use ($payment_method) {
                $query->where('id', '=', $payment_method);
            });
        }

        if ($id_order) {
            $query->where('id', $id_order);
        }

        if ($country) {
            $Country23 = Country::where('title_en' , 'like' , '%' . $country . '%')->Orwhere('title_ar' , 'like' , '%' . $country . '%')->first();
            $query->whereHas('user' , function ($query) use ($country , $Country23) {
//                $Country23 = Country::where('title_en' , 'like' , '%' . $country . '%')->where('title_ar' , 'like' , '%' . $country . '%')->first();
//                return $Country23;
                if($Country23){
                    $query->where('country_id' ,  $Country23->id);
                }else{
                    $query->where('country_id' ,  '$Country23->id');
                }
            });
        }


        if ($status) {
            $query->where('status', $status);
        }

        if ($payment_status) {
            if ($payment_status == '1') {
                $query->where('payment_status', '1');
            } elseif ($payment_status == '2') {
                $query->where('payment_status', '2');
            } elseif ($payment_status == '3') {
//                $query->whereNotIn('payment_status', ['1', '2']);
                $query->where('payment_status','!=','1');
            }else {
                $query->whereNotIn('payment_status', ['1', '2']);
            }
        }
        $count = $query->count();
        $info = $query->orderBy('id', 'desc')
            ->with(['user.Country', 'payment', 'address.cityData','address.regionData', 'deliveryTypeTitle', 'pieces.clothe',
        'pieces' => function ($query) {
            $query->withCount('clothe');
        } , 'user' => function ($query) {
                $query->withCount('orders')->withSum(['orders'], 'total_cost');
            }])->skip($skip)->take($take);
        return Datatables::of($info)->setTotalRecords($count);
    }

//    public function countDataTableClasses(array $data)
//    {
//        $query = Order::query();
//
//        return $query->count('id');
//    }
}
