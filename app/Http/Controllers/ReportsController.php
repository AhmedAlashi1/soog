<?php

namespace App\Http\Controllers;

use App\Models\Clothes;
use App\Models\Order;
use App\Models\AppUser;
use App\Models\Pieces;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $productss = Clothes::get(['id', 'title_ar', 'title_en']);
        $produc = Clothes::count();
        $employee = User::get(['id', 'name']);
        if ($request->employee) {
            $datas['total'] = [];
            $datas['country'] = [];
            $country = [];
            $month = [];
            $pp = Clothes::where('user_id', $request->employee)->pluck('id')->toArray();
            $ordersss_pices = Pieces::where('clothe_id', $pp)->pluck('order_id')->toArray();
            for ($j = 11; $j >= 0; $j--) {
                $p = Clothes::where('user_id', $request->employee)->pluck('id')->toArray();
                $orders_pices = Pieces::where('clothe_id', $p)->pluck('order_id')->toArray();
                $count = Order::where('status', 'complete')->whereIn('id', $orders_pices)
                    ->whereYear('created_at', '=', Carbon::now()->subMonth($j)->format('Y'))
                    ->whereMonth('created_at', '=', Carbon::now()->subMonth($j)->format('m'))->sum('total_cost');
                $month[] = Carbon::now()->subMonth($j)->format('M/y');
                $country[] = ($count) ? $count : 0;
            };
            $count_uus = Order::where('status', 'complete')->whereIn('id', $orders_pices)->pluck('user_id')->toArray();
            $events = Clothes::count();
            $orders_count = Order::count();
            $countCustmer = AppUser::whereIn('id' , $count_uus)->count();
            $countOrder = Order::where('status', 'complete')->whereIn('id', $orders_pices)->count();
            $sumOrder = Order::where('status', 'complete')->whereIn('id', $orders_pices)->sum('total_cost');
            $date = [];
            $products = [];
            $orders = [];
            for ($i = 0; $i < 7; $i++) {
                $range = \Carbon\Carbon::now()->subDays($i)->format('20y-m-d');
                $user_id = Clothes::where('user_id', $request->employee)->pluck('id')->toArray();
                $orders_pices = Pieces::where('clothe_id', $user_id)->pluck('order_id')->toArray();
                $product = Clothes::whereDate('created_at', $range)->where('user_id', $request->employee)->get();
                $order = Order::whereDate('created_at', $range)
                    ->whereIn('id', $orders_pices)->orderBy('id', 'DESC')->get();
                $date[] = $range;
                $products[] = $product->count();
                $orders[] = $order->count();
            }
            return view('Report.index', compact('sumOrder', 'countOrder', 'countCustmer'
                , 'productss', 'employee', 'events', 'orders_count', 'date', 'products', 'orders', 'country', 'month'));
        }
        elseif ($request->product) {
            $datas['total'] = [];
            $datas['country'] = [];
            $country = [];
            $month = [];
            $orderss_pices = Pieces::where('clothe_id', $request->product)->pluck('order_id')->toArray();
            for ($j = 11; $j >= 0; $j--) {
                $orders_pices = Pieces::where('clothe_id', $request->product)->pluck('order_id')->toArray();
                $count = Order::where('status', 'complete')->whereIn('id', $orders_pices)->whereYear('created_at', '=', Carbon::now()->subMonth($j)->format('Y'))
                    ->whereMonth('created_at', '=', Carbon::now()->subMonth($j)->format('m'))->sum('total_cost');

                $month[] = Carbon::now()->subMonth($j)->format('M/y');
                $country[] = ($count) ? $count : 0;
            };

            $countuse = Order::where('status', 'complete')->whereIn('id', $orders_pices)->pluck('user_id')->toArray();
            $events = Clothes::count();
            $orders_count = Order::count();

            $countCustmer = AppUser::whereIn('id' , $countuse)->count();

            $countOrder = Order::where('status', 'complete')->whereIn('id', $orderss_pices)->count();
            $sumOrder = Order::where('status', 'complete')->whereIn('id', $orderss_pices)->sum('total_cost');

            $date = [];
            $products = [];
            $orders = [];
            for ($i = 0; $i < 7; $i++) {
                $range = \Carbon\Carbon::now()->subDays($i)->format('20y-m-d');
                $product = Clothes::whereDate('created_at', $range)->where('id', $request->product)->get();
                $orders_pices = Pieces::where('clothe_id', $request->product)
                    ->pluck('order_id')->toArray();
                $order = Order::whereDate('created_at', $range)
                    ->whereIn('id', $orders_pices)->orderBy('id', 'DESC')->get();
                $date[] = $range;
                $products[] = $product->count();
                $orders[] = $order->count();
            }
            return view('Report.index', compact('sumOrder', 'countOrder', 'countCustmer'
                , 'productss', 'employee', 'events', 'orders_count', 'date', 'products', 'orders', 'country', 'month'));
        }
        elseif ($request->datefilter){
            $date    =   explode(' - ',$request->datefilter);
            $from = \Carbon\Carbon::parse($date[0])
                ->format('Y-m-d H:i:s');
            $to = \Carbon\Carbon::parse($date[1])
                ->format('Y-m-d H:i:s');
            $datas['total'] = [];
            $datas['country'] = [];
            $country = [];
            $month = [];
            for ($j = 11; $j >= 0; $j--) {
                $count = Order::where('status', 'complete')->whereYear('created_at', '=', Carbon::now()->subMonth($j)->format('Y'))
                    ->whereMonth('created_at', '=', Carbon::now()->subMonth($j)->format('m'))->sum('total_cost');

                $month[] = Carbon::now()->subMonth($j)->format('M/y');
                $country[] = ($count) ? $count : 0;
            };

//            Carbon::createFromDate($year, $month, $day, $tz);
            $events = Clothes::count();
            $orders_count = Order::count();
            $countCustmer = AppUser::whereBetween('created_at', [$from, $to])->count();
            $countOrder = Order::whereBetween('created_at', [$from, $to])->count();
            $sumOrder = Order::whereBetween('created_at', [$from, $to])->where('status', 'complete')->sum('total_cost');
            $date = [];
            $products = [];
            $orders = [];
            for ($i = 0; $i < 7; $i++) {
                $range = \Carbon\Carbon::now()->subDays($i)->format('20y-m-d');
                $product = Clothes::whereDate('created_at', $range)->get();
                $order = Order::whereDate('created_at', $range)->orderBy('id', 'DESC')->get();
                $date[] = $range;
                $products[] = $product->count();
                $orders[] = $order->count();
            }
            return view('Report.index', compact('sumOrder', 'countOrder', 'countCustmer'
                , 'productss', 'employee', 'events', 'orders_count', 'date', 'products', 'orders', 'country', 'month'));
         }
        else {
            $datas['total'] = [];
            $datas['country'] = [];
            $country = [];
            $month = [];
            for ($j = 11; $j >= 0; $j--) {
                $count = Order::where('status', 'complete')->whereYear('created_at', '=', Carbon::now()->subMonth($j)->format('Y'))
                    ->whereMonth('created_at', '=', Carbon::now()->subMonth($j)->format('m'))->sum('total_cost');

                $month[] = Carbon::now()->subMonth($j)->format('M/y');
                $country[] = ($count) ? $count : 0;
            };


            $events = Clothes::count();
            $orders_count = Order::count();
            $countCustmer = AppUser::count();
            $countOrder = Order::count();
            $sumOrder = Order::where('status', 'complete')->sum('total_cost');
            $date = [];
            $products = [];
            $orders = [];
            for ($i = 0; $i < 7; $i++) {
                $range = \Carbon\Carbon::now()->subDays($i)->format('20y-m-d');
                $product = Clothes::whereDate('created_at', $range)->get();
                $order = Order::whereDate('created_at', $range)->orderBy('id', 'DESC')->get();
                $date[] = $range;
                $products[] = $product->count();
                $orders[] = $order->count();
            }
            return view('Report.index', compact('sumOrder', 'countOrder', 'countCustmer'
                , 'productss', 'employee', 'events', 'orders_count', 'date', 'products', 'orders', 'country', 'month'));
        }

    }

//    public function get_reports (){
//        $categories = Category::where('parent_id' , '0')->get();
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
//    }
}
