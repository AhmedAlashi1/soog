<?php

namespace App\Http\Controllers;

use App\Models\Categories as Category;
use App\Models\Clothes as Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function category()
    {
        if (Gate::denies('categories-view')) {
            abort(403);
        }
        $categories = Category::orderBy('sort_order')->where('parent_id', '0')->get();
        return view('Category.index', compact('categories'));
    }

    public function get_categories()
    {
        $categories = Category::orderBy('sort_order')->where('parent_id', '0')->get();
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

    public function add_category(Request $request)
    {
        $parent_id = $request->parent_id ?? '0';
        if ($request->type == '2') {
            $validator = Validator::make($request->all(), Category::$rules);
            $data = $request->except('image');
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
             $data['parent_id'] = $parent_id;
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->messages(),
                ]);
            } else {
                $c = Category::create($data);
                $c->parent_id = $parent_id;
                $c->save();
                return response()->json([
                    'message' => trans('category.success_add_property'),
                    'status' => 200,
                ]);
            }
        } else {
            $validator = Validator::make($request->all(), Category::$rules);
            $data = $request->except('image');
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }

            Category::create($data);
            return response()->json([
                'message' => trans('category.success_add_property'),
                'status' => 200,
            ]);
//            }
        }
    }


    public function edit($id)
    {
        $category = Category::find($id);
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

        $category = Category::find($id);

        if ($category) {
            $data = $request->except('image');

            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
            $data['color'] = $request->color;
            $data['number_rooms'] = ($request->number_rooms) ? $request->number_rooms : null;
            $data['swimming_pool'] = ($request->swimming_pool) ? $request->swimming_pool : null;
            $data['Jim'] = ($request->Jim) ? $request->Jim : null;
            $data['working_condition'] = ($request->working_condition) ? $request->working_condition : null;
            $data['year'] = ($request->year) ? $request->year : null;
            $data['cere'] = ($request->cere) ? $request->cere : null;
            $data['number_cylinders'] = ($request->number_cylinders) ? $request->number_cylinders : null;
            $data['brand'] = ($request->brand) ? $request->brand : null;
            $data['salary'] = ($request->salary) ? $request->salary : null;
            $data['educational_level'] = ($request->educational_level) ? $request->educational_level : null;
            $data['specialization'] = ($request->specialization) ? $request->specialization : null;
            $data['biography'] = ($request->biography) ? $request->biography : null;
            $data['animal_type'] = ($request->animal_type) ? $request->animal_type : null;
            $data['fashion_type'] = ($request->fashion_type) ? $request->fashion_type : null;
            $data['subjects'] = ($request->subjects) ? $request->subjects : null;
            $data['location'] = ($request->location) ? $request->location : null;

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
        $category = Category::find($id);
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

    public function MenuShow() {
        $this->categories = Category::with('sub')->get();
        view()->composer('Front.LandingPage.Menu', function($view) {
            $view->with(['categories' => $this->categories]);
        });
    }
}
