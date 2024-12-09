<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\BlockUser;
use App\Models\Clothes;
use App\Models\Order;
use App\Models\ReportUser;
use App\Models\Follow;
use App\Models\Governorates;
use App\Models\Cities;
use App\Models\Country;
use App\Models\Setting;
use App\Models\SmsGate;
use App\Models\SmsLog;
use App\Repositories\AppUsersRepository;
use Illuminate\Http\Request;
use App\Helpers\SmsGateways;
use App\Helpers\Functions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use phpseclib3\Crypt\Hash;
use App\Http\Controllers\ApiController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Twilio;
use Laravel\Socialite\Facades\Socialite;
//use Laravel\Socialite\Facades\
//use Socialite;

class UserController extends ApiController
{
    use Functions;

    private $repo;
    private $gates;

    /**
     * UserController constructor.
     * @param Request $request
     * @param AppUsersRepository $repo
     * @param \App\Repositories\SmsGatesRepository $gates
     */
    public function __construct(Request $request, AppUsersRepository $repo, \App\Repositories\SmsGatesRepository $gates)
    {
//        parent::__construct($request);

        $this->repo = $repo;
        $this->gates = $gates;
    }
    public function redirectToGoogle()
    {
        //Socialite
//        return Socialite::driver('google')->redirect();
        return Socialite::driver('apple')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {


//            $user = Socialite::driver('google')->user();
//            $user= Socialite::driver('google')->stateless();
            $user = Socialite::driver('google')->stateless()->user();

//            return $user;

            $finduser = AppUser::where('google_id', $user->id)->first();

            if($finduser){
//                return $finduser;
                $credentials=['email' => $finduser->email, 'password' => $finduser->email];
                $token = auth('api')->attempt($credentials);
//                $token= Auth::login($finduser);
                return $token;
//                return redirect()->intended('dashboard');

            }else{
                $newUser = AppUser::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => \Illuminate\Support\Facades\Hash::make($user->email)
                ]);
//                return 'b';
                $credentials=['email' => $newUser->email, 'password' => $newUser->email];

                $token = auth('api')->attempt($credentials);
//                Auth::login($newUser);
//
                return $token;
//                return redirect()->intended('dashboard');
            }

        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function register(Request $request)
    {
//        return 'a';
        // check data required
        if ($request->type == 1){
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
//                $activation_code = rand(1111, 9999);
            }

            if ($request->input('mobile_number')[0] == '0' and $request->input('mobile_number')[1] == '0'){
                $str = ltrim($request->input('mobile_number'),$request->input('mobile_number')[1]);
            }else{
                $str=$request->input('mobile_number');
            }

        }

        $data = [];

        if ($request->input('type') == 1){
            $data['mobile_number'] =$request->input('mobile_number');
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('mobile_number'));
            $data['activation_code'] = $activation_code;
            $data['status'] = 'pending_activation';


        }elseif ($request->input('type') == 2){
            $data['email'] =$request->input('email');
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->input('email'));
            $data['status'] = 'pending_activation';

        }

        $data['device_token'] = $request->input('device_token');
        if ($request->input('first_name')){
            $data['first_name'] = $request->input('first_name');
        }
        $data['last_name'] = $request->input('last_name');
        $data['city_id'] = $request->input('city_id');
        $data['region_id'] = $request->input('region_id');
        $data['country_id'] = $request->input('country_id');

        $data['ip_address'] = request()->ip();

        try {
            if ($request->type == 1){
                $user = AppUser::where(['mobile_number' => $request->input('mobile_number')])->first();
            }elseif ($request->type == 2){
                $user = AppUser::where(['email' => $request->input('email')])->first();
            }

            if (!$user) {
//                $data['credit'] = Setting::where('key_id','welcome_credit')->first()->value;

                $user = AppUser::create($data);
            } else {
                 $user->update($data);

            }
            if ($request->type == 1){

                $credentials=['mobile_number' => $request->input('mobile_number'), 'password' => $request->input('mobile_number'),'disabled'=>0];
            }elseif ($request->type == 2) {
                $credentials=['email' => $request->input('email'), 'password' => $request->input('email') ,'disabled'=>0];

            }

                $token = auth('api')->attempt($credentials);




//            $message = 'your activation code is ' . $activation_code;
            if ($request->type == 1) {
                $message_whatsapp = ' كود التفعيل الخاص بك هو ' . $activation_code . '
اهلا  بك في تطبيق سوق 😀                        ';
                $response = $this->whatsapp($str, $message_whatsapp);
            }

//                $gate = SmsGate::where('sort_order', '>', 0)
//                    ->orderBy('sort_order', 'asc')
//                    ->first();
//            if (!$gate) {
//                $gate = SmsGate::where('sort_order', '>', 0)
//                    ->orderBy('sort_order', 'asc')
//                    ->first();;
//            }
//            $message_sms= 'your+activation+code+is+'.$activation_code;
//            $response = $this->send_whatsapp($str, $activation_code);
//            $sms = $this->sms($str, $message_sms);
//            return $response;
//            SmsLog::create([
//                'numbers' =>  $request->input('mobile_number'),
//                'sender' => '96590070045',
//                'message' => $message,
//                'status' =>  'success',
//                'response' => $response,
//                'gate_message' => 'success send message	',
//                'gate_id' => $gate->id,
//                'gateway' => 'whatsapp',
//            ]);
//            $test=SmsGateways::send($gate, $message, $request->input('mobile_number'));
//            return $response;
            $userdata = [
                'user_id' => $user->id,
                'token' => $token,
            ];
            //send email address
//            $emails = explode(',', config('general.emails'));
//            $this->sendEmail('emails.forget_pass', ['name' => $request->input('first_name') . ' ' . $request->input('last_name'), 'code' => $activation_code, 'mobile' => $request->input('mobile_number')], 'new user register', $emails);
//            return parent::success( $userdata);
            return $this->outApiJson(true, 'success', $userdata);

        } catch (JWTException $e) {
            return $this->outApiJson(false, 'could_not_create_token');
        } catch (\PDOException $ex) {
//            dd($ex);
            return $this->outApiJson(false, 'pdo_exception');
        }
    }
    public function tokenFcm(Request $request){
        $user = auth('api')->user();
        if ($user->status != 'active') {
            return $this->outApiJson(false, 'user_inactive');
        }

        $data['device_token'] = $request->input('device_token');
        $user->update($data);
        $userdata = [
            'user_id' =>$user->id,
            'device_token' => $data['device_token'],
        ];
        return $this->outApiJson(true, 'success', $userdata);

    }
    public function activateAccount(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)){
            return $this->outApiJson(false, 'user_not_found');
        }
        if (
            empty($request->input('activation_code'))
        ) {
            return $this->outApiJson(false, 'activation_code_missing');
        }


        //check user inactive
        if ($user->status == 'inactive') {
            return $this->outApiJson(false, 'user_inactive');
        }

        // check device serial

        if (empty($user->activation_code) || $user->status == 'active') {
            return $this->outApiJson(false, 'user_already_activated');
        }

        $activationCode = $request->input('activation_code');
        $code = intval($activationCode);
        if (!preg_match("/^[0-9]{4}$/", $code)) {
            return $this->outApiJson(false, 'activation_code_invalid');
        }

        $activation_code=Setting::where('key_id','activation_code')->first();

        if ($activationCode == $activation_code->value){

            $user->activation_code = '';
            $user->status = 'active';
            $user->save();
            $userdata = [
                'user_id' =>  $user->id,
                'mobile' =>  $user->mobile_number,
                'first_name' =>  $user->first_name,
                'last_name' =>  $user->last_name,
                'address' =>  $user->address,
                'avatar' => asset("assets/tmp/" .  $user->avatar),
            ];
            return $this->outApiJson(true, 'success', $userdata);
        }

        if ($user->activation_code != $activationCode) {
            return $this->outApiJson(false, 'activation_code_wrong');
        }

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
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }

    public function resendActivation(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

//        $mesage=Twilio::message('+201090730088', 'test');
//        dd($mesage);
//        check user inactive
        if ( $user->status == 'inactive') {
            return $this->outApiJson(false, 'user_inactive');
        }

        if (empty( $user->activation_code) ||  $user->status == 'active') {
            return $this->outApiJson(false, 'user_already_activated');
        }
        $activation_code=Setting::where('key_id','activation_code')->first();
        // check user max resend count
        if ( $user->resend_code_count >= $activation_code->value) {
            return $this->outApiJson(false, 'exceed_activition_code');
        }
         $user->status = 'pending_activation';
         $user->resend_code_count =  $user->resend_code_count + 1;
        try {
            if ( $user->save()) {
                $message = 'your activation code is ' .  $user->activation_code;

//                $gate = $this->gates->getNextGate(0);
//                $gate = SmsGate::where('sort_order', '>', 0)
//                    ->orderBy('sort_order', 'asc')
//                    ->first();
//                if (!$gate) {
//                    $gate = SmsGate::where('sort_order', '>', 0)
//                        ->orderBy('sort_order', 'asc')
//                        ->first();
//                }
//                SmsGateways::send($gate, $message,  $user->mobile_number);
                $userdata = [
                    'resend_code_count' =>  $user->resend_code_count,
                ];
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false, 'pdo_exception');
        }
    }
    public function updateInfo(Request $request)
    {

        $user = auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $rules =
//            [
//                'email' => ['required', 'string', 'email',
//                    Rule::unique('app_users'),
//                ]];
            [
            'email' => "required|email|unique:app_users,email,".$user->id,
//                'email' => 'required|email|unique:users,email,'.$this->user->id,
        ];

        $validation = Validator::make($request->all(),$rules);

        if($validation->failed()){

            return $this->outApiJson(false, 'update_error',$validation->errors());

        }
         $user->first_name = $request->input('first_name');
         $user->email = $request->input('email');
         if ( $request->input('mobile_number') ){
         $user->mobile_number = $request->input('mobile_number');

         }
         $user->note = $request->input('note');
         $user->whats_number = $request->input('whats_number');


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['gif', 'jpg', 'jpeg', 'png'])) {
                return $this->outApiJson(false, 'allow_extention_error');
            }
            $destinationPath = 'assets/tmp';
            $fileName = md5($file->getClientOriginalName()) . '-' . rand(9999, 9999999) .
                '-' . rand(9999, 9999999) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
             $user->avatar = $fileName;
        }
//        try {
            if ( $user->save()) {
//                $user;
                $userdata = [
                    'user_id' =>  $user->id,
                    'mobile' =>  $user->mobile_number,
                    'first_name' =>  $user->first_name,
                    'email' =>  $user->email,
                    'note' =>  $user->note,
                    'whats_number' =>  $user->whats_number,
//                    'address' =>  $user->address,
                    'avatar' => asset("assets/tmp/" .  $user->avatar),
                ];
                return $this->outApiJson(true, 'success', $userdata);
            } else {
                return $this->outApiJson(false, 'update_error');
            }
//        } catch (\PDOException $ex) {
//            return $this->outApiJson(false, 'pdo_exception');
//        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $users=null;
        try{
            $users = auth('api')->user();
        }catch (JWTException $e) {

        }

//        $user = auth('api')->user();
        $user = AppUser::find($request->id);
        $length = ($request->input('count')) ? $request->input('count') : 10;

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }
//        if ( $user->status != 'active') {
//            return $this->outApiJson(false, 'user_inactive');
//        }
        $title = 'title_'.$request->header('lang');
        $region = null;

        if ( $user->addres) {
            if ( $user->addres->regionData) {
                $country_id=Governorates::where('id',$user->addres->city_id)->first();
                $region = [
                    'id' =>  $user->addres->regionData->id,
                    'title' =>  $user->addres->regionData->$title,
                    'delivery_cost' =>  $user->addres->regionData->delivery_cost,
                    'order_limit' =>  $user->addres->regionData->order_limit,
                    'country_id' => $country_id->country_id,

                ];
            }
        }
        $country=Country::where('id',$user->country_id)->where('status','1')->first();

        $follow=Follow::where('follow',$user->id)->count();
        $followers=Follow::where('followers',$user->id)->count();
        $clothes=Clothes::where('user_id',$user->id)->count();
        $clothes_user= Clothes::query();
        $clothes_user->where('type','1')->where('status','1')
            ->where('confirm','1')->where('user_id',$user->id)
            ->with('user','country','categories.parent')
            ->orderBy('id','desc');

        $clothes_users = $clothes_user->paginate(10);
        $ddd = [];
        $title='title_'.$request->header('lang');
        $note='note_'.$request->header('lang');
        foreach($clothes_users->items() as $k=>$row){
            $ddd[$k]['id'] = $row->id;
            $ddd[$k]['title'] = $row->$title;
            $ddd[$k]['note'] = $row->$note;
            $ddd[$k]['end_date'] = $row->end_date;
            $ddd[$k]['price'] = $row->price;
            $ddd[$k]['cat_id'] = $row->cat_id;
            $ddd[$k]['sup_cat_id'] = $row->categories->parent->id;
            $ddd[$k]['country'] = ($row->country) ? $row->country->$title : null;
            $ddd[$k]['coin_name'] =($row->country) ? $row->country->coin_name : null;


            $ddd[$k]['views'] = $row->views;
            $ddd[$k]['user_id'] = $row->user->id;
            $ddd[$k]['user_email'] = $row->user->email;
            $ddd[$k]['user_mobile_number'] = $row->user->mobile_number;
            $ddd[$k]['user_whats_number'] = $row->user->whats_number;

            $ddd[$k]['chat_icon'] = $row->chat == 1 ?true : false;
            $ddd[$k]['email_icon'] = $row->email == 1 ?true : false;
            $ddd[$k]['sms_icon'] = $row->sms == 1 ?true : false;
            $ddd[$k]['whatsApp_icon'] = $row->whatsApp == 1 ?true : false;
            $ddd[$k]['call_icon'] = $row->call == 1 ?true : false;


            if($users) {
                $ddd[$k]['fav'] = ($row->favorites->where('user_id', $users->id) ->count() > 0)?true: false;
            }
            $ddd[$k]['image']=url('/').'/assets/tmp/'.$row->image;
        }
        if($users) {
            $follw = Follow::where('follow', $user->id)->where('followers', $users->id)->first();
        }else{
            $follw = null;
        }
        $user_data = [
            'user_id' =>  $user->id,
            'mobile' =>  $user->mobile_number,
            'email' =>  $user->email,
            'first_name' =>  $user->first_name,
            'address' =>  $user->addres,
            'country_id' =>  $user->country_id,
            'avatar' => asset("assets/tmp/" .  $user->avatar),
            'region' => $region,
            'whats_number' => $user->whats_number,
            'note' => $user->note,
            'follow' => $follow,
            'followers' => $followers,
            'advertisements' => $clothes,

            'follows' => ($follw)?true: false,



        ];
        $data=[
            'user_data' => $user_data,
            'count_total' => $clothes_users->total(),
            'nextPageUrl' => $clothes_users->nextPageUrl(),
            'pages'=>ceil($clothes_users->total()/$length),
            'clothes'=>$ddd,

        ];
        return $this->outApiJson(true, 'success', $data);

    }


    public function addFollow(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }


        if(empty($request->input('user_id'))){
            return $this->outApiJson(false,'data_required');
        }

        $add=Follow::where(['follow'=> $user->id,'followers'=>$request->input('user_id')])->first();

        if($add){
            return $this->outApiJson(false,'follow_exists');
        }
//        return $add;
        $data=[
            'followers'=>$request->input('user_id'),
            'follow'=> $user->id,
        ];

        try{

            $repose=Follow::create($data);

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'create_error');
        } catch (\PDOException $ex) {
            dd($ex);
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function deleteFollow(Request $request)
    {
        $user=auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }


        if(
            empty($request->input('user_id'))
        ){
            return $this->outApiJson(false,'data_required');
        }

        $add=Follow::where(['follow'=> $user->id,'followers'=>$request->input('user_id')])->first();

        if(!$add){
            return $this->outApiJson(false,'fav_id_not_exists');
        }

        try{
            $repose=Follow::where('followers', $request->input('user_id'))->where('follow', $user->id)->delete();

            if ($repose) {
                return $this->outApiJson(true,'success');
            }
            return $this->outApiJson(false,'pdo_exception');
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    public function getFollow(Request $request)
    {
        $user=auth('api')->user();

        if (empty($user)){

            return $this->outApiJson(false, 'user_not_found');
        }

        $length = ($request->input('count')) ? $request->input('count') : 6;
        $perPage = ($request->input('page')) ? $request->input('page') : 1;




        $where_obj = Follow::query();
        if ($request->input('follow')){

            $where_obj->where('follow',$request->input('follow'))->orderBy('id', 'DESC');

        }else{

            $where_obj->where('followers',$request->input('followers'))->orderBy('id', 'DESC');
        }

        $paginate= $where_obj->with('follows','follower')->paginate($length);

//        return $paginate->items();
        $d['data'] = [];
        $title='title_'.$request->header('lang');
//        return $paginate;
        foreach($paginate->items() as $k=>$row){

            $d['data'][]=[
                'follow_id'=>($row->follows)?$row->follows->id:'',
                'follow_name'=>($row->follows)?$row->follows->first_name:'',

                'followers_id'=>($row->follower) ? $row->follower->id : '',
                'followers_name'=>($row->follower) ? $row->follower->first_name : '',
            ];
        }

        $d['recordsTotal'] = $paginate->total();
        $d['recordsFiltered'] = $paginate->total();
        return $this->outApiJson(true,'success',['count_total' => $paginate->total(),'nextPageUrl' => $paginate->nextPageUrl(),'pages'=>ceil($paginate->total()/$length),'data'=>$d['data']]);
    }
        public function reportUser(Request $request){
            $user = auth('api')->user();

            if (empty($user)){

                return $this->outApiJson(false, 'user_not_found');
            }

            if(empty($request->input('user_id'))){
                return $this->outApiJson(false,'data_required');
            }

//            $add=Fav::where(['user_id'=> $user->id,'charity_id'=>$request->input('id')])->first();
//            if($add){
//                return $this->outApiJson(false,'fav_exists');
//            }
            $data=[
                'user_id'=>$request->input('user_id'),
                'customer_id'=> $user->id,
                'message'=> $request->message,
            ];

            try{

                $repose=ReportUser::create($data);

                if ($repose) {
                    return $this->outApiJson(true,'success');
                }
                return $this->outApiJson(false,'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false,'pdo_exception');
            }
        }

    public function BlockUser(Request $request)
    {
        $user = auth('api')->user();

        if (empty($user)) {

            return $this->outApiJson(false, 'user_not_found');
        }

        if (empty($request->input('user_id'))) {
            return $this->outApiJson(false, 'data_required');
        }

        $add = BlockUser::where(['customer_id' => $user->id, 'user_id' => $request->input('user_id')])->first();
        if ($add) {
            return $this->outApiJson(false, 'fav_exists');
        }
            $data = [
                'user_id' => $request->input('user_id'),
                'customer_id' => $user->id,

            ];

            try {

                $repose = BlockUser::create($data);

                if ($repose) {
                    return $this->outApiJson(true, 'success');
                }
                return $this->outApiJson(false, 'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false, 'pdo_exception');
            }
        }
        public function deleteUser(){
            $user = auth('api')->user();

            if (empty($user)) {

                return $this->outApiJson(false, 'user_not_found');
            }

            try {

                $repose = AppUser::where('id', $user->id)->delete();
                $order= Order::where('user_id', $user->id)->delete();
//                $repose->disabled = 1;
//                $repose->deleted_at = date('Y-m-d H:i:s');
//                $repose->save();
                if ($repose) {
                    return $this->outApiJson(true, 'success');
                }
                return $this->outApiJson(false, 'create_error');
            } catch (\PDOException $ex) {
                dd($ex);
                return $this->outApiJson(false, 'pdo_exception');
            }

        }



    public function send_whatsapp($tophone,$bode){

        $headers =[
            'Authorization:Bearer EAAFpph7QZBDcBANTvz4bKz3dgjmdracT5NGT1lWincvdyggZA8LcUMyYcywS8ZA4Qlmk7JiN6p7ZCiuKqoZA98FHVZAde05fFKrS3vgjH3Vk6ZB6gDf4C7AqZCZAJoTvzZCKT1Ft6vhyZAsZAjxAQPaeGRZAy0Urrw2KLhIW4UktpWqXNbzq6KP2uTALLP6EoSbbs12wZD',
            'Content-Type:application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v14.0/100342676323656/messages');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // $fields = json_encode(['messaging_product' => 'whatsapp', 'to' => $phone_code.$tophone, 'type' => 'text', 'text' => ['preview_url'=> false ,"body" => $bode]]);
        //        $fields = json_encode(['messaging_product' => 'whatsapp', 'to' => '972595604849', 'type' => 'template', 'template' => ['name'=> "hello_world" , "language" => ["code" => "en_US" ]]]);
        $fields = json_encode(['messaging_product' => 'whatsapp', 'to' => $tophone, 'type' => 'template',
            'template' => ['name'=> "otp" , "language" => ["code" => "en_US" ,   "policy" => "deterministic"],"components" => [[
                "type" => "body","parameters"=>[["type"=> "text","text"=>$bode ]]
            ]]]]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result ;

    }



    public function sms($mobile,$message){

        $username = env('SMS_USER_NAME');
        $password = env('SMS_PASSWORD');
        $sender = env('SMS_SENDER');
        $url = 'https://www.kwtsms.com/API/send/?username='.$username.'&password='.$password.'&sender='.$sender.'&mobile='.$mobile.'&lang=1&message='.$message;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Connection: Keep-Alive'
        ]);

        $result['content'] = curl_exec($ch);
        return $result;


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


}
