<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Governorates;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class GovernoratesController extends Controller
{
    public function governorates (){
        $Country = Country::get();
        $Country2 = Country::get();
        return view('Governorat.index' , compact('Country' , 'Country2'));
    }

    public function get_governorates (){
        $Governorates = Governorates::latest()->with('country')->get();

        if ($Governorates) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Governorates
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_governorat (Request $request){
        Governorates::create($request->all());
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            // 'data' => $Governorates
        ]);
    }

    public function edit ($id){
        $Governorates = Governorates::find($id);
        if ($Governorates) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $Governorates
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
        $Governorates = Governorates::find($id);
        if ($Governorates) {
            $Governorates->update($request->all());
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $Governorates
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
        $Governorates = Governorates::find($id);
        if ($Governorates) {
            $Governorates->delete();
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
        $Governorates = Governorates::find($id);
        $Governorates->status = request('status');
        $Governorates->update();
        return response()->json([
            'status' => 200,
        ]);
    }
}
