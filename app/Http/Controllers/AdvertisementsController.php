<?php

namespace App\Http\Controllers;

use App\Exports\ClothesExport;
use App\Http\Repositories\ProductRepositories;
use App\Models\AppUser;
use App\Models\Categories;
//use App\Models\Categories;
use App\Models\Categories as Category;
use App\Models\Clothes;
use App\Models\Clothes as Product;
use App\Models\Country;
use App\Models\Setting;
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

    public function index(Request $request)
    {


        $type=$request->type;
        $getSubCat = $this->getSubCat(0, '');
        if (Gate::denies('advertisements-view')) {
            abort(403);
        }
//        $cat = Category::get();
        return view('advertisements.index', compact('getSubCat','type'));
    }
    public function create(){
//        return 'a';
        $this->data['countries'] = Country::get();
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();

        return view('advertisements.create')->with($this->data);

    }
    public function store(Request $request){

        if ($request->mobile_user){
            $user = AppUser::where(['mobile_number' => $request->input('mobile_user')])->first();
            $data['country_id'] = $request->input('country_id');
            $data['mobile_number'] = $request->input('mobile_user');
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('mobile_user'));
            $data['status'] = 'pending_activation';

            if (!$user) {

                $user = AppUser::create($data);
            } else {
                $user->update($data);

            }
        }
            $data = $request->except(['_token', 'id', 'note', 'type','title']);

            $data['note_ar'] = $request->input('note');
            $data['note_en'] = $request->input('note');

            if(!empty($request->input('title'))){
                $data['title_ar'] = $request->title;
                $data['title_en'] = $request->title;
            }


            $images_data = [];
            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                        '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
                    $destinationPath = 'assets/tmp';
                    $file->move($destinationPath, $fileName);
                    // create image thumb
//                $this->createThumb($destinationPath, $fileName);
                    $images_data[] = ['image' => $fileName];
                }
            }


            if (count($images_data) > 0) {
                $data['image'] = $images_data[0]['image'];
            }

            $data['user_id'] = $user->id;

            unset($data['images']);

            $add = Clothes::create($data);

            $add->charityImages()->createMany($images_data);
            // add payment link
//            $userdata['advertisement_id']=$add->id;

            return redirect('/admin/advertisements?type=0');

    }

    public function get_prodect(Request $request, ProductRepositories $productRepo)
    {
        $dataTable = $productRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        return $dataTable->make(true);

    }

    public function index_show()
    {
        $getSubCat = $this->getSubCat(0, '');

        return view('advertisements.show' , compact('getSubCat'));
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
        $this->data['advertisements']  = Clothes::with('user','categories')->find($id);
        $this->data['countries'] = Country::get();
        $this->data['categories'] = Categories::where(['status' => '1', 'parent_id' => '0'])->orderBy('sort_order', 'asc')->get();
//        return   $this->data['advertisements'];
        return view('advertisements.edit')->with($this->data);




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
        return Excel::download(new ClothesExport($request), 'advertisements.xlsx');
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
    public function subcat_filter(Request $request){
        return 'a';

    }
}
