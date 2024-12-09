<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use \Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Config;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException as TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as TokenInvalidException;
use App\Helpers\Functions;
use Auth;
use App\Repositories\AppUsersRepository;

class AuthController extends ApiController
{
    use Functions;
    protected $repo;

    /**
     * AuthController constructor.
     * @param Request $request
     * @param AppUsersRepository $repo
     */
    public function __construct(Request $request, AppUsersRepository $repo)
    {
        parent::__construct($request);
        $this->repo = $repo;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(Request $request)
    {
            if (empty($request->input('mobile_number'))) {
                return $this->outApiJson(false, 'data_required');
            }
        try {
            // verify the credentials and create a token for the user
                if (!$token = JWTAuth::attempt(['mobile_number' => $request->input('mobile_number'), 'password' => $request->input('mobile_number')])) {
                    return $this->outApiJson(false, 'invalid_credentials');
                }
                $user = Auth::user();
        } catch (JWTException $e) {
            // something went wrong
            return $this->outApiJson(false,'could_not_create_token');
        }

        $userdata = [
            'user_id' => $user->id,
            'token' => $token,
            'mobile' => $user->mobile_number,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'address' => $user->address,
            'avatar' => asset("assets/tmp/".$user->avatar),
        ];
        return $this->outApiJson(true,'success',$userdata);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        if (isset($request->token)) {
            return $this->outApiJson(false,'invalid_token');
        }
        try {
            if (!$token = JWTAuth::parseToken()->refresh()) {
                return $this->outApiJson(false,'user_not_found');
            }
        } catch (TokenExpiredException $e) {
            return $this->outApiJson(false,'token_expired');
        } catch (TokenInvalidException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (JWTException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (InvalidClaimException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (PayloadException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (TokenBlacklistedException $e) {
            return $this->outApiJson(false,'invalid_token');
        }

        if (!$user = JWTAuth::setToken($token)->authenticate()) {
            return $this->outApiJson(false,'user_not_found');
        }
        $userdata = [
            'token' => $token,
        ];
        return $this->outApiJson(true,'success',$userdata);
        // the token is valid and we have found the user via the sub claim
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        if (empty($request->input('email'))) {
            return $this->outApiJson(false,'data_required');
        }
        // get user email id
        $user = $this->repo->findWhere(['email' => $request->input('email')])->first();
        if (!$user) {
            return $this->outApiJson(false,'user_not_found');
        }
        try {
            $code = rand(11111, 99999);
            $this->sendEmail('emails.forget_pass', ['name'=>$user->full_name,'email'=>$request->input('email'),'code'=>$code], 'forget password', [$user->email]);
            $user->password_code=$code;
            $user->save();
            return $this->outApiJson(true,'success');
        } catch (\PDOException $e) {
            // something went wrong
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        if (
        empty($request->input('password_code'))
        ) {
            return $this->outApiJson(false,'activation_code_missing');
        }
        $user = $this->repo->findWhere(['password_code' => $request->input('password_code')])->first();
        if(!$user){
            return $this->outApiJson(false,'activation_code_invalid');
        }
        $activationCode = $request->input('password_code');
        $code = intval($activationCode);
        if (!preg_match("/^[0-9]{5}$/", $code)) {
            return $this->outApiJson(false,'activation_code_invalid');
        }

        if ($user->password_code != $activationCode) {
            return $this->outApiJson(false,'activation_code_wrong');
        }
        $user->password_code = '';
        try {
            if ($user->save()) {
                $token=JWTAuth::fromUser($user);
                $userdata = [
                    'user_id' => $user->id,
                    'token' => $token,
                ];
                return $this->outApiJson(true,'success',$userdata);
            } else {
                return $this->outApiJson(false,'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePass(Request $request)
    {
        if (
        empty($request->input('password'))
        ) {
            return $this->outApiJson(false,'data_required');
        }

        $this->user->password = \Hash::make($request->input('password'));
        try {
            if ($this->user->save()) {
                return $this->outApiJson(true,'success');
            } else {
                return $this->outApiJson(false,'update_error');
            }
        } catch (\PDOException $ex) {
            return $this->outApiJson(false,'pdo_exception');
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = auth('api')->user();

            if (empty($user)){

                return $this->outApiJson(false, 'user_not_found');
            }
            /*if ($this->user->send_notification == 1){
                $this->user->old_token = $this->user->device_token;
                $this->user->send_notification = 0;
                $this->user->device_token = 'logout';
                $this->user->save();
            }else{
                $this->user->send_notification = 1;
                $this->user->device_token = $this->user->old_token;
                $this->user->save();
            }*/
            $user->device_token = 'logout';
            $user->save();
            //JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            // something went wrong
            return $this->outApiJson(false,'invalid_token');
        }
        return $this->outApiJson(true,'success');
    }

}
