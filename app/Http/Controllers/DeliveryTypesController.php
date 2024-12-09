<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTypes as Delivery_types;
use Illuminate\Http\Request;

class DeliveryTypesController extends Controller
{
    public function deliveryTypes (){
        return view('DeliveryType.index');
    }

    public function get_deliveryTypes (){
        $Delivery_types = Delivery_types::get();
        if ($Delivery_types) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Delivery_types
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_deliveryTypes (Request $request){
        Delivery_types::create($request->all());
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $Delivery_types
        ]);
    }

    public function edit ($id){
        $Delivery_types = Delivery_types::find($id);
        if ($Delivery_types) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Delivery_types
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        // return $request->all();
        $Delivery_types = Delivery_types::find($id);
        if ($Delivery_types) {
            $Delivery_types->title_ar = $request->title_ar;
            $Delivery_types->title_en = $request->title_en;
            $Delivery_types->time_from = $request->time_from;
            $Delivery_types->time_to = $request->time_to;
            if($request->sat){
                $Delivery_types->sat = $request->sat;
            }else{
                $Delivery_types->sat = 0;
            }
            if($request->sun){
            $Delivery_types->sun = $request->sun;
            }else{
                $Delivery_types->sun = 0;
            }
            if($request->mon){
                $Delivery_types->mon = $request->mon;
            }else{
                $Delivery_types->mon = 0;
            }
            if($request->tue){
                $Delivery_types->tue = $request->tue;
            }else{
                $Delivery_types->tue = 0;
            }
            if($request->wed){
                $Delivery_types->wed = $request->wed;
            }else{
                $Delivery_types->wed = 0;
            }
            if($request->thu){
                $Delivery_types->thu = $request->thu;
            }else{
                $Delivery_types->thu = 0;
            }
            if($request->fri){
                $Delivery_types->fri = $request->fri;
            }else{
                $Delivery_types->fri = 0;
            }
            $Delivery_types->update();
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Delivery_types
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
        $Delivery_types = Delivery_types::find($id);
        if ($Delivery_types) {
            $Delivery_types->delete();
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
        $categories = Delivery_types::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            'message' => 'Update Success',
            'status' => 200,
        ]);
    }
}
