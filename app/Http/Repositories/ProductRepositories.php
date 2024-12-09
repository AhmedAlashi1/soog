<?php

namespace App\Http\Repositories;

use App\Models\Clothes;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;

class ProductRepositories
{

    public function getDataTableClasses(array $data)
    {
        $skip = $data['start'] ?? 0;
        $take = $data['length'] ?? 25;
        $query = $this->query($data);
        $info = $query->skip($skip)->take($take);

        $count = $this->countDataTableClasses($data);

        return Datatables::of($info)->setTotalRecords($count);


    }

    private function query($data)
    {
        $query = Clothes::query()->with('categories');
        $search = $data['search']["value"] ?? null;
        $status = $data['status'] ?? null;
        $name = $data['name'] ?? null;
        $cat_id = $data['cat_id'] ?? null;
        $type = $data['type'] ?? null;
        $lang = $data['lang'] ?? 'ar';
        if ($status){
            if ($status == '1'){
                $query->where('status' , $status);
            }else{
                $query->where('status' , '!=' , '1');
            }
        }
        if ($name){
            $query->where(function ($query)use ($name){
                $query->where('title_ar','like','%'.$name.'%')
                    ->orwhere('title_en','like','%'.$name.'%')
                    ->orwhere('keywords','like','%'.$name.'%');
            });
        }
        if ($cat_id){
            $query->whereHas('categories', function ($query) use ($cat_id) {
                $query->where('title_ar', 'like', '%' . $cat_id . '%')
                    ->orWhere('title_en' , 'like' , '%'. $cat_id . '%');
            });
        }
        if ($type != 0) {
                $query->whereHas('fixedAds' ,function ($q) use ($type){
                    $q->whereHas('packages' ,function ($qq) use ($type){
                        $qq->where('type',$type);
                    })
                        ->where('status',1);
                });
        }
        $query->with('user','fixedAds.packages')->orderBy('created_at','desc');
        return $query;
    }

    public function countDataTableClasses(array $data)
    {
        return $this->query($data)->count('id');
    }
}
