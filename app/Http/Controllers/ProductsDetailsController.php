<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Categories as Category;
use App\Models\Clothes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class ProductsDetailsController extends Controller
{
    public function getSubCat($parent,$dilemeter){
        $cats = Category::where(['parent_id'=>$parent])->get();
        $out='';
        if(App::getLocale() == 'en'){
            foreach ($cats as $row){
                $out .='<option value="'.$row->id.'">'.$dilemeter.' '.$row->title_en.'</option>';
                $out .=$this->getSubCat($row->id,$dilemeter.' '.'&#8675;');
            }
        }
        else{
            foreach ($cats as $row){
                $out .='<option value="'.$row->id.'">'.$dilemeter.' '.$row->title_ar.'</option>';
                $out .=$this->getSubCat($row->id,$dilemeter.' '.'&#8675;');
            }
        }

        return $out;
    }

    public function index()
    {
        // $prodects = Clothes::with(['categories' , 'user' , 'pieces'])->get();
        // dd ($prodects);
        $getSubCat =$this->getSubCat(0,'');

        if(Gate::denies('productList-view')){
            abort(403);
        }
        $cat = Categories::get();
        return view('Prodect.indexd' , compact('getSubCat'));
    }

    public function get_d()
    {
        $prodects = Clothes::orderBy('sort_order')->withCount('pieces')->with(['categories' , 'user'])->get();
        return response()->json([
            'message' => 'Data Found',
            'status' => 200,
            'data' => $prodects
        ]);
    }


    public function indexm()
    {
        if(Gate::denies('productList-view')){
            abort(403);
        }
        $getSubCat =$this->getSubCat(0,'');
        $cat = Categories::get();
        return view('Prodect.indexm' , compact('getSubCat'));
    }

    public function get_m(Request $request)
    {

        if($request->type == '1'){
            $prodects = Clothes::where('type' , '1')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }elseif ($request->type == '2'){
            $prodects = Clothes::where('type' , '2')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }elseif ($request->type == '3'){
            $prodects = Clothes::where('type' , '3')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }elseif ($request->type == '5'){
            $prodects = Clothes::where('international' , '0')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }elseif ($request->type == '6'){
            $prodects = Clothes::where('international' , '1')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }
        else{
            $prodects = Clothes::where('type' , '4')
                ->orderBy('sort_order')->with('categories')->get();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        }
//        $data_arr = array();
//        $sno = 1;
//        foreach($prodects as $record){
//            $id = $record->id;
//            if(App::getLocale() == 'en'){
//                $title = $record->title_en ?? "";
//            }else{
//                $title = $record->title_ar ?? "";
//            }
//            $image = $record->image ?? "";
//            $price = $record->price ?? "";
//            $quntaty = $record->quntaty ?? "";
//            $status = $record->status ?? "";
//            if(App::getLocale() == 'en'){
//                $title_c = $record->categories->title_en ?? "";
//            }else{
//                $title_c = $record->categories->title_ar ?? "";
//            }
//            $data_arr[] = array(
//                "id" => $id,
//                "title_en" => $title,
//                // "title_ar" => $title_ar,
//                "image" => $image,
//                "price" => $price,
//                "quntaty" => $quntaty,
//                "status" => $status,
//                "title_c" => $title_c,
//                // "title_ar_c" => $title_ar_c,
//            );
//        }
        // return ;
    }

    public function update_type($id)
    {
        $product = Clothes::find($id);
        if ($product) {
            $product->update(['type' => 0]);
            return response()->json([
                'message' => trans('category.property_delete_success'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }
}
