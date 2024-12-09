<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersAndAdminController extends Controller
{

    // Admins
    public function admin (){
        $admin = User::with('roles')->find(1);

//        return $admin;
        if(Gate::denies('member-view')){
            abort(403);
        }
        $role = Role::get();
//        $admins = User::with('roles')->get();
//        return  $admins;
//        $admins = DB::table('users')
//            ->join('role_user', 'users.id', '=', 'role_user.user_id')
//            ->join('role', 'role_user.role_id', '=', 'role.id')
//            ->get();
//        return $admins ;

//        $admins = User::with(['roles' => function ($query) {
//            $query->select('name');
//        }])->get('id');

//        return $admins ;

        return view('Admin/index' , compact('role'));
    }

    public function get_admins (){
        $admins = User::with('roles')->get();
//        $role = DB::table('users')
//            ->join('role_user', 'users.id', '=', 'role_user.user_id')
//            ->join('role', 'role_user.role_id', '=', 'role.id')
//            ->get();
        if ($admins) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $admins,
//                'role' => $role
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_admin (Request $request){
//        return $request->all();
        $admin = new User();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->mobile = $request->phone ?? "";
        $admin->type = '1';
        $admin->user_name = Str::slug($request->name);
        $admin->password = Hash::make($request->password);
        $admin->save();
        $role_user = new RoleUser();
        $role_user->role_id = $request->role;
        $role_user->user_id = $admin->id;
        $role_user->save();
        return response()->json([
            'message' => trans('category.success_add_property'),
            'status' => 200,
            'data' => $admin
        ]);
    }

    public function edit ($id){
        $admin = User::with('roles')->find($id);
        if ($admin) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $admin
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function update (Request $request , $id){
//        return $request->all();
        $admin = User::find($id);
        if ($admin) {
            $admin->name = $request->name;
            $admin->email = $request->email;
//            $admin->mobile = $request->phone;
            $admin->type = $request->role;
            $admin->user_name = Str::slug($request->name);
            if ($request->password){
                $admin->password = bcrypt($request->password);
            }
            $admin->update();
            $role = RoleUser::where('user_id' , $id)->first();
            if ($role){
                DB::table('role_user')
                    ->where('user_id' , $id)
                    ->update(['role_id' => $request->role]);
            }else{
                $role_user = new RoleUser();
                $role_user->role_id = $request->role;
                $role_user->user_id = $id;
                $role_user->save();
            }

            return response()->json([
                'message' => trans('category.success_update_property'),
                'status' => 200,
                'data' => $admin
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete ($id){
        $admin = User::find($id);
        if ($admin) {
            $admin->delete();
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




    public function edit_admin()
    {
        $id=auth()->user()->id;
        $user = User::find($id);

        return view('users_admin.edit',compact('user'));
    }

    public function update_admin(Request $request)
    {
//        return $request->all();
        $id=$request->id;
//        return $id;
        $rules = [
            'email' => 'required|email|unique:users,email,'.$id,
        ];

        $validation = $request->validate($rules);

        $users = User::find($id);
//        return $users;
        $users->update( $request->all());
        session()->flash('success', 'تم تعديل المستخدم بنجاح ');
        return redirect('/');
    }

    public function reset_Password()
    {
        $id=auth()->user()->id;
        $user = User::find($id);
        return view('users_admin.reset_password',compact('user'));
    }

    public function resetPassword(Request $request)
    {
//        return $request->all();
//        dd('a');
        $rules = [
            'old_password' => 'required|min:3',
            'new_password' => 'required|min:3',
            'confirm_password' => 'required|min:3|same:new_password',
        ];
        $validated = $request->validate($rules);
        $user = auth()->user();
        if (!Hash::check($request->get('old_password'), $user->password)) {
            $message = __('api.old_password'); //wrong old
            return response()->json(['status' => false, 'code' => 400, 'message' => $message,
                'validator' => $validated]);
        }
        $user->password = bcrypt($request->get('new_password'));
        $data=$user->save();
        return redirect('/');
    }





    //Clients
    public function client (){
        return view('Client/index');
    }

    public function get_clients (){
        $clients = Client::get();
        if ($clients) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $clients
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function add_client (Request $request){
        $client = Client::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'status' => $request->status,
                ]);
        return response()->json([
            'message' => 'Data Found',
            'status' => 200,
            'data' => $client
        ]);
    }

    public function client_edit ($id){
        $client = Client::find($id);
        if ($client) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $client
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function client_update (Request $request , $id){
        $client = Client::find($id);
        if ($client) {
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone;
            $client->status = $request->status;
            $client->update();
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $client
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function client_delete ($id){
        $client = Client::find($id);
        if ($client) {
            $client->delete();
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

}
