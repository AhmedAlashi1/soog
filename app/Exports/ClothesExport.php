<?php

namespace App\Exports;

use App\Models\Clothes;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClothesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Clothes::select(
            'id' , 'title_ar' , 'title_en' , 'price' , 'quntaty' ,'status' , 'cat_id'
        )->with('categories');
        $lang = $this->request->lang ?? 'ar';

        if (!empty($this->request->status)){
            if ($this->request->status == '1'){
                $query->where('status' , $this->request->status);
            }else{
                $query->where('status' , '!=' , '1');
            }
        }
        if (!empty($this->request->name)){
            $query->where('title_'.$lang , 'like' , '%'. $this->request->name . '%');
        }
        if (!empty($this->request->cat_idd)){
            $cat = $this->request->cat_idd;
            $query->whereHas('categories', function ($query) use ($cat) {
                $query->where('title_ar', 'like', '%' . $cat . '%')
                    ->orWhere('title_en' , 'like' , '%'. $cat . '%');
            });
//            $query->where('cat_id' , $this->request->cat_idd);
        }


        $query=$query->get();

        $data= [];
        foreach ($query as $key => $value){
            $data[$key]['id'] = $value->id;
            $data[$key]['title_ar'] = $value->title_ar  ?? "";
            $data[$key]['title_en'] = $value->title_en ?? "";
            $data[$key]['cat'] = $value->categories->title_ar ?? "";
            $data[$key]['price'] = $value->price ?? "";
            $data[$key]['quntaty'] = $value->quntaty ?? "";
            if($value->status == '1'){
                $data[$key]['status'] = "فعال";
            }else{
                $data[$key]['status'] = "غير فعال";
            }
        }
        array_unshift($data ,[
            'id'=> '#',
            'title_ar'=> 'إسم العربي',
            'title_en'=> 'رقم الإنجليزي',
            'cat' => 'الفئة',
            'price' => 'السعر',
            'quntaty' => 'الكمية',
            'status' => 'الحالة',
        ]);
        $data = collect($data);
        return $data;
    }
}
