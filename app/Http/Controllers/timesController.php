<?php

namespace App\Http\Controllers;

use App\Models\Times;
use Illuminate\Http\Request;

class timesController extends Controller
{
    public function Times (){
        return view('time.index');
    }

    public function get_times (){
        $Times = Times::get();
        if ($Times) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Times
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_times (Request $request){
        Times::create($request->all());
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $Times
        ]);
    }

    public function edit ($id){
        $Times = Times::find($id);
        if ($Times) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Times
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
        $Times = Times::find($id);
        if ($Times) {
            // $Times->title_ar = $request->title_ar;
            // $Times->title_en = $request->title_en;
            // $Times->time_from = $request->time_from;
            // $Times->time_to = $request->time_to;
            $Times->update($request->all());
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Times
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
        $Times = Times::find($id);
        if ($Times) {
            $Times->delete();
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
        $categories = Times::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            'message' => 'Update Success',
            'status' => 200,
        ]);
    }
}
