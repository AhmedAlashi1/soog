<?php

namespace App\Http\Controllers;

use App\Exports\ExportAppUser;
use App\Models\AppUser;
use App\Http\Repositories\AppUserRepositories;
use App\Models\Charge;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Governorates;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AppUsersController extends Controller
{
    public function app_user()
    {
        $query = AppUser::paginate();

//            ->withSum(
//                ['orders'],
//                'total_cost'
//            )
//            ->first();
//        return $query;
        return view('App_User.index');
    }

    public function get_appUser(Request $request, AppUserRepositories $appuserRepo)
    {
        $dataTable = $appuserRepo->getDataTableClasses($request->all());
        $dataTable->addIndexColumn();
        $dataTable->escapeColumns(['*']);
        return $dataTable->make(true);

//        $app_users = AppUser::get();
//        if ($app_users) {
//            return response()->json([
//                'message' => 'Data Found',
//                'status' => 200,
//                'data' => $dataTable->make(true),
//                'count' => $count,
//            ]);
//        } else {
//            return response()->json([
//                'message' => 'Data Not Found',
//                'status' => 404,
//            ]);
//        }
    }

    public function app_user_address_index($id)
    {
//        $Charge = Charge::where('user_id' , $id)->with('cityData' , 'regionData')->get();
//        return $Charge;
        $Governorates = Governorates::get();
        $Cities = Cities::get();
        return view('App_User.addresses', compact('Governorates', 'Cities'));
    }

    public function get_app_user_address($id)
    {
        $Charge = Charge::where('user_id', $id)->with('cityData', 'regionData')->get();
        return response()->json([
            'message' => 'Update Success',
            'status' => 200,
            'data' => $Charge
        ]);
    }

    public function add_app_user_address(Request $request, $id, $type)
    {
        if ($type == '1') {
            $Charge = Charge::find($id);
            return response()->json([
                'status' => 200,
                'data' => $Charge
            ]);
        }
        elseif ($type == '2') {
            $Charges = Charge::find($request->id_categoryd);
//            $Charges->user_id = $id;
            $Charges->type = $request->type;
            $Charges->city_id = $request->governate;
            $Charges->region_id = $request->city;
            $Charges->block = $request->block;
            $Charges->avenue = $request->avenue;
            $Charges->street = $request->street;
            $Charges->building = $request->building;
            $Charges->floor = $request->floor;
            $Charges->flat = $request->flat;
            $Charges->notes = $request->notes;
            $Charges->address = $request->street;
            $Charges->save();
        } elseif ($type == '3') {
            $user = AppUser::find($id);
            $user->mobile_number = $request->mobile_number;
            $user->save();
            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
            ]);
        } else {
            $Chargew = new Charge();
            $Chargew->user_id = $id;
            $Chargew->type = $request->type;
            $Chargew->city_id = $request->governate;
            $Chargew->region_id = $request->city;
            $Chargew->block = $request->block;
            $Chargew->avenue = $request->avenue;
            $Chargew->street = $request->street;
            $Chargew->building = $request->building;
            $Chargew->floor = $request->floor;
            $Chargew->flat = $request->flat;
            $Chargew->notes = $request->notes;
            $Chargew->address = $request->street;
            $Chargew->save();
            return response()->json([
                'message' => trans('category.success_add_property'),
                'status' => 200,
            ]);
        }

    }


    public function delete_app_user_address($id)
    {
        $Charge = Charge::find($id);
        if ($Charge) {
            $Charge->delete();
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

    public function delete($id)
    {
        $app_user = AppUser::find($id);
        if ($app_user) {
            $app_user->delete();
            return response()->json([
                'message' => 'Data Found',
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
        $categories = AppUser::find($id);
        $categories->status = request('status');
        $categories->update();
        return response()->json([
            'message' => trans('category.success_update_property'),
            'status' => 200,
        ]);
    }

    public function add100(Request $request)
    {
        $id = $request->id;
        $user = AppUser::find($id);
//        return $user->country_id;
        $country=Country::find( $user->country_id);
//        $country=Country::where('id',$country_id)->where('status','1')->first();

        $note=$request->note;
        if ($request->credit < 0){
            $request->credit = 0;
        }
        if (!empty($country)){

            if (!empty($user->credit)){
                $credit=  (string)(round($request->credit / $country->coin_price,3));
                $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . ' '.$country->coin_name .' '.$note;

            }else{
                $credit=$request->credit;
                $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . 'دينار'.' '.$note;

            }
        }else{
            $credit=$request->credit;
            $messages = 'تم اضافة رصيد الى حسابك بقيمه ' . $credit . 'دينار'.' '.$note;

        }
//        $credit = $request->credit;
        $credit_user = $user->credit;
        $sum = $credit + $credit_user;
        $user->credit = $sum;
        $user->update();
        $message = $messages;
        $this->notification($user->device_token , 'حلاو ككاو' , $message);

        return response()->json([
            'message' => 'تم إضافة رصيد بنجاح',
            'status' => 404,
        ]);
    }

    public function export(Request $request)
    {
//        return new ExportAppUser($request);
        return Excel::download(new ExportAppUser($request), 'AppUsers.xlsx');
    }

    public function usersAll (Request $request){
        if ($request->type == '1') {
            $users = AppUser::whereIn('id', $request->ids);
            if ($users) {
                $users->delete();
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
        }elseif ($request->type == '2'){
            $users = AppUser::whereIn('id', $request->ids);
            if ($users) {
                $users->update(['status' => "active"]);
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
    }
    public function notification($FcmToken = [], $title = "", $body = "")
    {
        $data = [
            "to" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default"
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . env('FCM_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
//            return response()
//            ->json(['status' => 'success', 'errors' => 0,
//            'data' => json_decode($response, true)])
//            ->header('Content-type', 'application/json');
    }
}
