<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function delivery (){
        return view('Delivery.index');
    }

    public function get_delivery (){
        $categories = Delivery::get();
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

    public function add_delivery (Request $request){
        Delivery::create($request->all());
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $Delivery
        ]);
    }



    public function edit ($id){
        $Delivery = Delivery::find($id);
        if ($Delivery) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Delivery
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        $Delivery = Delivery::find($id);
        if ($Delivery) {
            $Delivery->update($request->all());
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Delivery
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
        $Delivery = Delivery::find($id);
        if ($Delivery) {
            $Delivery->delete();
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
        $id = $request->id;
        $categories = Delivery::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            // 'message' => 'Update Success',
            'status' => 200,
        ]);
    }
}