<?php

namespace App\Http\Controllers;

use App\Exports\ClothesExport;
use App\Http\Repositories\ProductRepositories;
use App\Models\Categories as Category;
use App\Models\Clothes as Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AdvertisementsController extends Controller
{
    public function getSubCat($parent, $dilemeter)
    {
        $cats = Category::where(['parent_id' => $parent])->get();
        $out = '';
        if (App::getLocale() == 'en') {
            foreach ($cats as $row) {
                $out .= '<option value="' . $row->id . '">' . $dilemeter . ' ' . $row->title_en . '</option>';
                $out .= $this->getSubCat($row->id, $dilemeter . ' ' . '&#8675;');
            }
        } else {
            foreach ($cats as $row) {
                $out .= '<option value="' . $row->id . '">' . $dilemeter . ' ' . $row->title_ar . '</option>';
                $out .= $this->getSubCat($row->id, $dilemeter . ' ' . '&#8675;');
            }
        }

        return $out;
    }

    public function index()
    {
        $getSubCat = $this->getSubCat(0, '');
        if (Gate::denies('product-view')) {
            abort(403);
        }
//        $cat = Category::get();
        return view('Prodect.index', compact('getSubCat'));
    }

    public function get_prodect(Request $request, ProductRepositories $productRepo)
    {
//        return $request->search['value'];

//        $payment_status = $request->payment_status;
//
//        $prodect = Product::query();
//        $prodect->with('categories:id,title_ar,title_en');
//        if (!empty($payment_status)) {
//            if($payment_status == 1){
//                $prodect->where('status' , $payment_status);
//            }else{
//                $prodect->where('status' , 0);
//            }
//        }

        $dataTable = $productRepo->getDataTableClasses($request->all());

        $dataTable->addIndexColumn();
//            ->filter(function ($instance) use ($request) {
////                dd($request->all());
//        if (!empty($request->get('search'))) {
//            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
//                if (Str::contains(Str::lower($row['email']), Str::lower($request->get('search')))){
//                    return true;
//                }else if (Str::contains(Str::lower($row['name']), Str::lower($request->get('search')))) {
//                    return true;
//                }
//
//                return false;
//            });
//        }
//
//    });
//        $dataTable->addColumn('action', function ($row) {
//            $data['status'] = $row->status;
//            return view('admin.classes.parts.status', $data)->render();
//        });
//        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);

//        if ($prodects) {
//            return response()->json([
//                'message' => 'Data Found',
//                'status' => 200,
//                'data' => $prodects
//            ]);
//        } else {
//            return response()->json([
//                'message' => 'Data Not Found',
//                'status' => 404,
//            ]);
//        }
    }

    public function index_show()
    {
        $getSubCat = $this->getSubCat(0, '');

        return view('Prodect.show' , compact('getSubCat'));
    }

    public function show(Request $request , $id)
    {
//        return $request->all();
        if($request->status){
            $status = $request->status;
            if ($status == '1'){
                $prodects = Product::where('cat_id', $id)->where('status' , $status)->with(['categories', 'user'])->orderBy('sort_order')->get();
            }else{
                $prodects = Product::where('cat_id', $id)->where('status' , '!=' , '1')->with(['categories', 'user'])->orderBy('sort_order')->get();
            }

        }else{
            $prodects = Product::where('cat_id', $id)->with(['categories', 'user'])->orderBy('sort_order')->get();
        }
        if ($prodects) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $prodects
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_prodect(Request $request)
    {
        if ($request->typeer == '1') {
//            return $request->all();
            $validator = Validator::make($request->all(), Product::$rules);
            $data = $request->except('image');
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
            $data['confirm']='1';
//            return $data;
            Product::create($data);
            return response()->json([
                'message' => trans('category.success_add_property'),
                'status' => 200,
            ]);
        } elseif ($request->typeer == '2') {
            $product = Product::find($request->id);
            $product->type = $request->typeupdatemm;
            $product->save();
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
            ]);
        }
    }

    public function edit($id)
    {
        $product = Product::with('categories')->find($id);
        if ($product) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $product
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Product::$rules);
        $product = Product::find($id);
//        return $product;
//        if ($validator->fails()) {
//            return response()->json([
//                'status' => 400,
//                'errors' => $validator->messages(),
//            ]);
//        }
        if ($product) {
            $data = $request->except('image');
//            if ($request->hasFile('image')){
//                if (File::exists($product->image)){
//                    File::delete($product->image);
//                }
//                $file = $request->file('image');
//                $filename = $file->getClientOriginalName();
//                $local =  request()->getSchemeAndHttpHost();
//                $path = $file->storeAs('category' , $filename ,  ['disk' => 'uploads']);
//                $data['image']  = $local .'/'.'uploads/'.$path;
//                // return $data['image'];
//            }
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
            $product->update($data);
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $product
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
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

    public function updateStatus(Request $request)
    {
        if ($request->typeint == '1'){
            $id = $request->id;
            $Product = Product::find($id);
            if($Product->international == '1'){
                $Product->international = '0';
            }
            else{
                $Product->international = '1';
            }
            $Product->update();
            return response()->json([
                'message' => 'Update Success',
                'status' => 200,
            ]);
        }
        else{
            $id = $request->id;
            $Product = Product::find($id);
            $Product->status = request('status');
            if (request('status') == 0){
                $Product->quntaty = 0;
            }else{
                $Product->quntaty = 100;
            }

            $Product->update();
            return response()->json([
                'message' => 'Update Success',
                'status' => 200,
            ]);
        }
    }

    public function add100($id)
    {
        $Product = Product::find($id);
        $Product->quntaty += 100;
        $Product->update();
//        return redirect()->back();
    }

    public function minas100($id)
    {
        $minas = 100;
        $Product = Product::find($id);
        $Product->quntaty -= 100;
        $Product->update();
//        return redirect()->back();
    }

    public function export(Request $request)
    {
//        return $request;
        return Excel::download(new ClothesExport($request), 'Products.xlsx');
    }

    public function update_sort_order(Request $request)
    {
//        return $request->all();
        foreach ($request->order as $order) {
            $c = Product::find($order['id']);
            $c->sort_order = $order['position'];
            $c->save();
        }
        return response('Update Successfully.', 200);
    }
}
