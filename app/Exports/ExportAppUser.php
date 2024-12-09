<?php

namespace App\Exports;

use App\Models\AppUser;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportAppUser implements FromCollection
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
        $app_user = AppUser::select(
            'id' , 'first_name' , 'mobile_number' , 'credit' , 'status' ,'activation_code'
        );


        if (!empty($this->request->phone)){
            $app_user->where('mobile_number' , 'like' , '%'. $this->request->phone . '%');
        }
        if (!empty($this->request->name)){
            $app_user->where('first_name' , 'like' , '%'. $this->request->name . '%');
        }
        if (!empty($this->request->status)){
            $app_user->where('status' , $this->request->status);
        }
        if (!empty($this->request->credit)){
            $app_user->where('credit' , $this->request->credit);
        }
        if (!empty($this->request->from) && !empty($this->request->to)){
            $from = $this->request->from;
            $to = $this->request->to;
            $app_user->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }
        $app_user=$app_user->get();
  
        $data= [];
        foreach ($app_user as $key => $value){
            $data[$key]['id'] = $value->id;
            $data[$key]['first_name'] = $value->first_name  ?? "";
            $data[$key]['mobile_number'] = $value->mobile_number ?? "";
            $data[$key]['credit'] = $value->credit ?? "";
            $data[$key]['status'] = $value->status ?? "";
            $data[$key]['activation_code'] = $value->activation_code ?? "";
        }
        array_unshift($data ,[
            'id'=> '#',
            'first_name'=> 'إسم الزبون',
            'mobile_number'=> 'رقم هاتف',
            'credit' => 'الرصيد',
            'status' => 'الحالة',
            'activation_code' => 'كود التفعيل',
        ]);
        $data = collect($data);
        return $data;
    }
}
