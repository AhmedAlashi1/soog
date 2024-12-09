<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Governorates;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function cities (){
        $Gov = Governorates::orderBy('id','DESC')->get();
        // $Cities = Cities::with('Governorates')->get();
//         return $Gov;
        return view('city.index' , compact('Gov'));
    }

    public function get_cities (){
        $cities = Cities::latest()->get();
        if ($cities) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $cities
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function index_show()
    {
        $Gov = Governorates::get();
        return view('city.show' , compact('Gov'));
    }

    public function show ($id){

        $cities = Cities::where('governorat_id' , $id)->latest()->get();
        if ($cities) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $cities
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_Cities (Request $request){
        Cities::create($request->all());
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $Cities
        ]);
    }



    public function edit ($id){
        $Cities = Cities::with('Governorates')->find($id);
        if ($Cities) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Cities
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        $Cities = Cities::find($id);
        if ($Cities) {
            $Cities->update($request->all());
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Cities
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
        $Cities = Cities::find($id);
        if ($Cities) {
            $Cities->delete();
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
        $id = $request->id;
        $categories = Cities::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            // 'message' => 'Update Success',
            'status' => 200,
        ]);
    }
}
