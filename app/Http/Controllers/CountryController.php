<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CountryController extends Controller
{
    public function country()
    {
        return view('Country.index');
    }

    public function get_country()
    {
        $country = Country::get();
        if ($country) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $country
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_country(Request $request)
    {
        $data = $request->except('image');
        if ($request->file('image')) {
            $name = Str::random(12);
            $path = $request->file('image');
            $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $data['image'] = $name;
            $path->move('assets/tmp', $name);
        }
        $country = Country::create($data);
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
        ]);
    }

    public function edit($id)
    {
        $country = Country::find($id);
        if ($country) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $country
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
        $country = Country::find($id);
        if ($country) {
            $data = $request->except('image');
            if ($request->file('image')) {
                $name = Str::random(12);
                $path = $request->file('image');
                $name = $name . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $data['image'] = $name;
                $path->move('assets/tmp', $name);
            }
            $country->update($data);
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $country
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
        $country = Country::find($id);
        if ($country) {
            $country->delete();
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
        $country = Country::find($id);
        $country->status = request('status');
        $country->update();
        return response()->json([
            'status' => 200,
        ]);
    }


    public function ordersError(Request $request){

        echo 'error';
    }
    public function ordersSuccess(Request $request){

        echo 'success';
    }
}
