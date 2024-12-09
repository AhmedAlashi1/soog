<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\Categories;

//use App\Models\Categories;
use App\Models\Categories as Category;

use App\Models\Clothes;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdsController extends Controller
{
    public function getSubCat(Request $request){
//        return $request->all();
        $parent= $request->category;
        $cats = Category::where(['parent_id'=>$parent])->get();
        $out='';
        if(App::getLocale() == 'en'){
            foreach ($cats as $row){
                $out .='<option value="'.$row->id.'"> '.$row->title_en.'</option>';
//                $out .=$this->getSubCat($row->id,$dilemeter.' '.'&#8675;');
            }
        }
        else{
            foreach ($cats as $row){
                $out .='<option value="'.$row->id.'"> '.$row->title_ar.'</option>';

//                $out .='<option value="'.$row->id.'">'.$dilemeter.' '.$row->title_ar.'</option>';
//                $out .=$this->getSubCat($row->id,$dilemeter.' '.'&#8675;');
            }
        }

        return $out;
    }
    public function getCountry($parent,$dilemeter){
        $country = Country::get();
//        return $country;
        $out='';
        if(App::getLocale() == 'en'){
            foreach ($country as $row){
                $out .='<option value="'.$row->id.'">'.$dilemeter.' '.$row->title_en.'</option>';
//                $out .=$this->getCountry($row->id,$dilemeter.' '.'&#8675;');
            }
        }
        else{
            foreach ($country as $row){
                $out .='<option value="'.$row->id.'">'.$dilemeter.' '.$row->title_ar.'</option>';
//                $out .=$this->getCountry($row->id,$dilemeter.' '.'&#8675;');
            }
        }

        return $out;
    }
    public function create(){
        $cat = Categories::get();
//        return Carbon::now()->diffInDays('2022-10-17 19:37:45', true);

        $pro = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        $prod = Clothes::select('id', 'title_en', 'title_ar')->take(1500)->get();
        return view('Ads.create', compact('cat', 'pro', 'prod'));
//        return view('Ads.create');
    }
    public function ads (){
//        $coupons = Ads::with(['categories' , 'Products'])->get();
//        return $coupons;
        $getCountry =$this->getCountry(0,'');
        $prodect = Clothes::select('id', 'title_en' , 'title_ar')->take(500)->get();
        $prodec = Clothes::select('id', 'title_en' , 'title_ar')->take(500)->get();
        $pro = Clothes::select('id', 'title_en' , 'title_ar')->take(500)->get();
        return view('Ads.index' , compact('getCountry' , 'prodect' , 'prodec' , 'pro'));
    }

    public function get_ads (Request $request){
        // $coupons = Ads::with(['categories' , 'Products'])->get();
        if ($request->type == 'local'){
            $coupons = Ads::where('international' , '!=' , '1')->orderBy('id', 'desc')->get();
        }elseif ($request->type == 'international'){
            $coupons = Ads::where('international' , '1')->orderBy('id', 'desc')->get();
        }else{
            $coupons = Ads::where('international' , '!=' , '1')->orderBy('id', 'desc')->get();
        }

        if ($coupons) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $coupons,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_ads (Request $request){
//         return $request->all();
        $data = $request->except('image');
//        if ($request->hasFile('image')){
//            $file = $request->file('image');
//            $filename = $file->getClientOriginalName();
//            $local =  request()->getSchemeAndHttpHost();
//            $path = $file->storeAs('ads' , $filename ,  ['disk' => 'uploads']);
//            $data['image']  = $local .'/'.'uploads/'.$path;
//        }
        if ($request->file('image')) {
            $name = Str::random(12);
            $path = $request->file('image');
            $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $data['image'] = $name;
            $path->move('assets/tmp', $name);
        }
        if($data['layout']==1){
            $data['lauout_title']='اعلى الرئيسيه';
        }elseif($data['layout']==2){
            $data['lauout_title']='تجاري';
        }elseif($data['layout']==3){
            $data['lauout_title']='اعلى الرئيسيه الثانى';
        }elseif($data['layout']==4){
            $data['lauout_title']='اعلى الرئيسيه قديم';
        }elseif($data['layout']==5){
            $data['lauout_title']='صفحة الاقسام';
        }elseif($data['layout']==6){
            $data['lauout_title']='صفحة اتمام الطلب';
        }
        $data['multi_product_id'] = explode(',', $request->multi_product_id);

        Ads::create($data);
//        session()->flash('Add', 'تم اضافة  الاعلان بنجاح ');
//        return redirect('admin/ads');
        return response()->json([
            'message' => trans('category.success_add_property')
            ,
            'status' => 200,
        ]);
    }

    public function edit ($id){
        $Ads = Ads::with('categories' , 'Products')->find($id);
        if ($Ads) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Ads
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        $Ads = Ads::find($id);
        if ($Ads) {
            $data = $request->except('image');
//            if ($request->hasFile('image')){
//                if (File::exists($Ads->image)){
//                    File::delete($Ads->image);
//                }
//                $file = $request->file('image');
//                $filename = $file->getClientOriginalName();
//                $local =  request()->getSchemeAndHttpHost();
//                $path = $file->storeAs('ads' , $filename ,  ['disk' => 'uploads']);
//                $data['image']  = $local .'/'.'uploads/'.$path;
////                return $data['image'];
//            }
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
            $data['status'] = '1';
            if($data['layout']==1){
                $data['lauout_title']='اعلى الرئيسيه';
            }elseif($data['layout']==2){
                $data['lauout_title']='تجاري';
            }elseif($data['layout']==3){
                $data['lauout_title']='اعلى الرئيسيه الثانى';
            }elseif($data['layout']==4){
                $data['lauout_title']='اعلى الرئيسيه قديم';
            }elseif($data['layout']==5){
                $data['lauout_title']='صفحة الاقسام';
            }elseif($data['layout']==6){
                $data['lauout_title']='صفحة اتمام الطلب';
            }
            $Ads->update($data);
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Ads
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
        $Ads = Ads::find($id);
        if ($Ads) {
            $Ads->delete();
            return response()->json([
                'message' => trans('category.success_delete_property'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        if ($request->typeint == '1') {
            $id = $request->id;
            $ads = Ads::find($id);
            if ($ads->international == '0'){
                $ads->international = '1';
            }else{
                $ads->international = '0';
            }
            $ads->save();
            return response()->json([
                'status' => 200,
            ]);
        } else {
            $id = $request->id;
            $Coupons = Ads::find($id);
            $Coupons->status = request('status');
            $Coupons->update();
            return response()->json([
                'message' => 'Update Success',
                'status' => 200,
            ]);
        }
    }
}
