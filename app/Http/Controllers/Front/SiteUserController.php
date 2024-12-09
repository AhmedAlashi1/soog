<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\Charge;
use App\Models\Clothes;
use App\Models\Fav;
use App\Models\Follow;
use App\Models\Governorates;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Setting;
use App\Models\SmsGate;
use App\Models\SmsLog;
use App\Repositories\AppUsersRepository;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Helpers\SmsGateways;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use phpseclib3\Crypt\Hash;
use App\Http\Controllers\ApiController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Twilio;
use Laravel\Socialite\Facades\Socialite;

class SiteUserController extends Controller
{
    public function register(Request $request)
    {
        if (
            empty($request->input('mobile_number'))
        ) {
            return $this->outApiJson(false, 'data_required');
        }
        if (!ctype_digit($request->input('mobile_number'))) {
            return $this->outApiJson(false, 'mobile_invalid');
        }
        if ($request->input('mobile_number') == '0096512345678') {

            $activation_code = 1234;
        } else {
            $activation_code = 1234;
        }

        if ($request->input('mobile_number')[0] == '0' and $request->input('mobile_number')[1] == '0') {
//            return 'A';
            $str = ltrim($request->input('mobile_number'), $request->input('mobile_number')[1]);
//
        } else {
            $str = $request->input('mobile_number');
        }


        $data = [];
        $data['mobile_number'] = $request->input('mobile_number');
        $data['device_token'] = $request->input('device_token');
        $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('mobile_number'));
        $data['first_name'] = $request->input('first_name');
        $data['last_name'] = $request->input('last_name');
        $data['city_id'] = $request->input('city_id');
        $data['region_id'] = $request->input('region_id');
        $data['country_id'] = $request->input('country_id');
        $data['activation_code'] = $activation_code;
        $data['status'] = 'pending_activation';
//        $data['credit'] = config('general.welcome_credit');
        $data['ip_address'] = request()->ip();
//        return $data;
        try {
            $user = AppUser::where(['mobile_number' => $request->input('mobile_number')])->first();

            if (!$user) {
//                $data['credit'] = config('general.welcome_credit');
                $data['credit'] = Setting::where('key_id', 'welcome_credit')->first()->value;

                $user = AppUser::create($data);
            } else {
                $user->update($data);

            }

            $message_whatsapp = ' كود التفعيل الخاص بك هو ' . $activation_code . '
اهلا  بك في تطبيق سوق 😀                        ';
            $response = $this->whatsapp($str, $message_whatsapp);
            /*$userdata = [
                'user_id' => $user->id,
                'token' => $token,
            ];*/
            //send email address
//            $emails = explode(',', config('general.emails'));
//            $this->sendEmail('emails.forget_pass', ['name' => $request->input('first_name') . ' ' . $request->input('last_name'), 'code' => $activation_code, 'mobile' => $request->input('mobile_number')], 'new user register', $emails);
//            return parent::success( $userdata);
            return ['value' => $user->id];

        } catch (JWTException $e) {
            return $this->outApiJson(false, 'could_not_create_token');
        } catch (\PDOException $ex) {
//            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function activateAccount(Request $request)
    {
        $user_id = $request->input('user_id');
        $user = AppUser::find($user_id);
//        return $user;
        if (empty($user)) {
            return $this->outApiJson(false, 'user_not_found');
        }
//        if (
//            empty($request->input('activation_code'))
//        ) {
//            //return $this->outApiJson(false, 'activation_code_missing');
//        }


        //check user inactive
//        if ($user->status == 'inactive') {
//            //return $this->outApiJson(false, 'user_inactive');
//        }

        // check device serial

//        if (empty($user->activation_code) || $user->status == 'active') {
//            //return $this->outApiJson(false, 'user_already_activated');
//        }
//        return $request->all();

        $activationCode = $request->activation_code1 .$request->activation_code2.$request->activation_code3.$request->activation_code4 ;
//        $activationCode = 1234;

//        return $activationCode;
        $code = intval($activationCode);
        if (!preg_match("/^[0-9]{4}$/", $code)) {
            return response()->json([
                'status' => 'error',
                'message' => 'رمز التفعيل غير صالح'
            ]);
        }



        if ($user->activation_code != $activationCode) {
//            return 'a';
            return response()->json([
                'status' => 'error',
                'message' => 'رمز التفعيل غير صالح'
            ]);
        }
//
        $user->activation_code = '';
        $user->status = 'active';
        try {
            if ( $user->save()) {
                $userdata = [
                    'user_id' =>  $user->id,
                    'mobile' =>  $user->mobile_number,
                    'first_name' =>  $user->first_name,
                    'last_name' =>  $user->last_name,
                    'address' =>  $user->address,
                    'avatar' => asset("assets/tmp/" .  $user->avatar),
                ];
                $credentials = ['mobile_number' => $user->mobile_number, 'password' => $user->mobile_number];
                $token = Auth::guard('user')->attempt($credentials);
                return response()->json([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'يوجد خطا'
                ]);
            }
        } catch (\PDOException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'يوجد خطا'
            ]);
        }



    }
    public function user_logout(){
        Auth::guard('user')->logout();
        return redirect()->route('index');
    }
    public function whatsapp($phone , $bode){



        $params=array(
            'token' => 'yc39eosn1tisgezs',
            'to' => $phone,
            'body' =>$bode,

        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance42951/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
//        else {
//            echo $response;
//        }

    }
    public function ProfileUser() {
        $id = Auth::guard('user')->user()->id;
        //
        $ProductsUser = Clothes::where('user_id',$id)->get(['*']);
        $follow=Follow::where('follow',$id)->count();
        $followers=Follow::where('followers',$id)->count();

        return view('Front.LandingPage.profile')->with([
            'Products' => $ProductsUser,
            'follow' => $follow,
            'followers' => $followers
        ]);
    }

    public function EditPofile() {
        $id = Auth::guard('user')->user()->id;
        if ($id == null) {
            return redirect()->back();
        }

        $user = AppUser::find($id);
        return view('Front.LandingPage.EditProfile')->with('user',$user);

    }

    public function UpdateData(Request $request) {

        $id = Auth::guard('user')->user()->id;
        if ($id == null) {
            return redirect()->back();
        }

        $user = AppUser::find($id);
        if ($user == null){
            return redirect()->back();
        }

        $photo = $request->ImgUser;
        $FName = $request->FName;
        $SName = $request->SName;
        $email = $request->email;
        $phone = $request->phone;
        $Whatsphone = $request->Whatsphone;
        $NoteUser = $request->NoteUser;

        if ($photo != null){
            $file = $request->file('ImgUser');
            $extension = $file->getClientOriginalExtension();
            $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
            $destinationPath = 'assets/tmp';
            $file->move($destinationPath, $fileName);
            $user->avatar = $fileName;
        }
        if ($FName != null){
            $user->first_name = $FName;
        }
        if ($SName != null){
            $user->last_name = $SName;
        }
        if ($email != null){
            $user->email = $email;
        }
        if ($phone != null){
            $user->mobile_number = $phone;
        }
//        if ($Whatsphone != null){
//            $user->whats_number = 1;
//        }
        if ($NoteUser != null){
            $user->note = $NoteUser;
        }

        $user->save();
        return redirect()->route('user_Profile');
    }
}
