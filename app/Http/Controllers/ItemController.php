<?php

namespace App\Http\Controllers;

use App\Models\Categories as Category;
use App\Models\Clothes as Product;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index()
    {
        if (Gate::denies('categories-view')) {
            abort(403);
        }
        $item = Item::orderBy('id')->get();
        return view('item.index', compact('item'));
    }

    public function get_item()
    {
        $item = Item::orderBy('id')->get();

        if ($item) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $item
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update_sort_order(Request $request)
    {
//        return $request->all();
        $Category = Category::all();

//        foreach ($posts as $post) {
//            foreach ($request->order as $order) {
//                if ($order['id'] == $post->id) {
//                    $post->update(['order' => $order['position']]);
//                }
//            }
//        }


//        foreach ($Category as $cat) {
        foreach ($request->order as $order) {
            $c = Category::find($order['id']);
            $c->sort_order = $order['position'];
            $c->save();
//                $cat->update(['order' => $order['position']]);
            /*  if ($order['id'] == $cat->id) {
                  $c = Category::find($cat->id);
                  $c->sort_order = $order['position'];
                  $c->save();
                  return $c;
              }*/
        }
//        }

        return response('Update Successfully.', 200);
    }

    public function show()
    {
        if (Gate::denies('categories-view')) {
            abort(403);
        }
        return view('Category.show');
    }

    public function show_categories($id)
    {
        $categories = Category::where('parent_id', $id)->orderBy('sort_order')->get();
        if ($categories) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $categories
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_item(Request $request)
    {


            $validator = Validator::make($request->all(), Item::$rules);
            $data = $request->except('image');
            Item::create($data);
            return response()->json([
                'message' => trans('category.success_add_property'),
                'status' => 200,
            ]);


    }


    public function edit($id)
    {
        $category = Item::find($id);
        if ($category) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $category
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

        $category = Item::find($id);

        if ($category) {
            $data = $request->except('image');



            $category->update($data);
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $category
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
        $category = Item::find($id);
        if ($category) {
            $category->delete();
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
        if ($request->typeint == '1') {
            $id = $request->id;
            $categories = Category::find($id);
            if ($categories->international == '0'){
                $categories->international = '1';
            }else{
                $categories->international = '0';
            }
            $categories->save();
            return response()->json([
                // 'message' => 'Update Success',
                'status' => 200,
            ]);
        }elseif ($request->typeint == '2'){
            $id = $request->id;
            $categories = Category::find($id);
            if ($categories->home == '0'){
                $categories->home = '1';
            }else{
                $categories->home = '0';
            }
            $categories->save();
            return response()->json([
                // 'message' => 'Update Success',
                'status' => 200,
            ]);
        } else {
//            return $request->all();
            $id = $request->id;
            $categories = Category::find($id);
            $categories->status = request('status');
            $categories->update();
            $Products = Product::where('cat_id' , $categories->id)->get();
            foreach ($Products as $key){
                $Product = Product::where('id' , $key->id)->first();
                if ($categories->status == '1'){
                }else{
                    $Product->status = '0';
                }
                $Product->update();
            }
            return response()->json([
                // 'message' => 'Update Success',
                'status' => 200,
            ]);
        }
    }
}
