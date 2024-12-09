<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportOrders implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $orders = Order::select(
            'id' , 'status' , 'credit' ,'total_cost' , 'user_id' , 'payment_id' , 'address_id' , 'delivery_id','created_at'
        )->orderBy('id', 'desc')->with(['user' , 'payment' , 'address' , 'deliveryTypeTitle' , 'pieces', 'user' => function ($query) {
            $query->withCount('orders');
        }]);


        if (!empty($this->request->name)){
            $name = $this->request->name;
            $orders->whereHas('user', function ($query) use($name) {
                $query->where('first_name' , 'like' , '%'. $name . '%');
            });
        }
        if ($this->request->created_at and $this->request->created_at_to ){
//            $query->whereBetween('created_at',[$created_at,$created_at_to]);
            $orders ->whereDate('created_at', '>=',$this->request->created_at)
                ->whereDate('created_at', '<=',$this->request->created_at_to);
        }elseif ($this->request->created_at) {
            $orders->whereDate('created_at',$this->request->created_at);
        }

        if (!empty($this->request->phone)){
            $phone = $this->request->phone;
            $orders->whereHas('user', function ($query) use($phone) {
                $query->where('mobile_number' , 'like' , '%'. $phone . '%');
            });
        }

        if (!empty($this->request->payment_method)){
            $payment_method = $this->request->payment_method;
            $orders->whereHas('payment', function ($query) use($payment_method) {
                $query->where('id' , '=' , $payment_method);
            });
        }

        if (!empty($this->request->status)){
            $orders->where('status' , $this->request->status);
        }

        if (!empty($this->request->id_order)){
            $orders->where('id' , $this->request->id_order);
        }

        if (!empty($this->request->payment_status)){
            if ($this->request->payment_status == '1'){
                $orders->where('payment_status' , '1');
            }elseif ($this->request->payment_status == '2'){
                $orders->where('payment_status' , '2');
            }else{
                $orders->whereNotIn('payment_status' ,  ['1' , '2']);
            }
        }





         $orders=$orders->get();

         $data= [];
        foreach ($orders as $key => $value){
            $data[$key]['id'] = $value->id;
            $data[$key]['id_user'] = $value->user->id  ?? "" ;
            $data[$key]['first_name'] = $value->user->first_name  ?? "" ;
            $data[$key]['mobile_number'] = $value->user->mobile_number ?? "";
            $data[$key]['orders_count'] = $value->user->orders_count ?? "";
            $data[$key]['created_at'] = $value->created_at ?? "";

            $data[$key]['payment_method'] = $value->payment->title_ar ?? "لا توجد طريقة دفع";
            $data[$key]['total_cost'] = $value->total_cost ?? "لا توجد اجمالي فاتورة";
            if ($value->status == 'new') {
                $data[$key]['status'] = 'قبول الطلب' ?? "لا يوجد حالة";
            } else if ($value->status == 'pay_pending') {
                $data[$key]['status'] = 'جاري تجهيز الطلب' ?? "لا يوجد حالة";
            } else if ($value->status == 'shipping') {
                $data[$key]['status'] = 'تم إلغاء الطلب' ?? "لا يوجد حالة";
            } else if ($value->status == 'shipping_complete') {
                $data[$key]['status'] = 'تم الشحن' ?? "لا يوجد حالة";
            } else if ($value->status == 'complete') {
                $data[$key]['status'] = 'تم تأكيد الإستلام' ?? "لا يوجد حالة";
            } else {
                $data[$key]['status'] = "لا يوجد حالة";
            }
            $data[$key]['governorat'] = $value->address->regionData->title_ar ?? "لا يوجد منطقة ";
            $data[$key]['city'] = $value->address->cityData->title_ar ?? "لا يوجد محافظة";
        }
        array_unshift($data ,[
            'id'=> '#',
            'id_user'=> 'رقم المستخدم',
            'first_name'=> 'الأسم',
            'mobile_number'=> 'رقم الجوال',
            'orders_count'=> 'عدد الطلبات',
            'created_at'=> 'تاريخ الطلب',
            'payment_method' => 'طريقة الدفع',
            'total_cost' => 'اجمالي الفاتوره',
            'status' => 'الحالة',
            'governorat' => 'المنطقة',
            'city' => 'المحافظة',
        ]);
        $data = collect($data);
        return $data;
    }
}
