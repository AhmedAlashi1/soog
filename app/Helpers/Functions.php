<?php

namespace App\Helpers;

use App\Models\Notifications;
use Carbon\Carbon;
use GuzzleHttp\Exception\ConnectException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use Intervention\Image\ImageManagerStatic as Image;
use FCM;

/**
 * Class Helpers
 * @package App\Helpers
 */
trait Functions
{

    public function sendEmail($view, $data, $subject, $emails)
    {
        $app = \App::getInstance();
        $app['swift.transport'] = $app->share(function ($app) {
            return new \Illuminate\Mail\TransportManager($app);
        });

        $mailer = new \Swift_Mailer($app['swift.transport']->driver());
        \Mail::setSwiftMailer($mailer);
        \Mail::send($view, $data, function ($message) use ($subject, $emails) {
            $message->to($emails)->subject($subject)->from(env('APP_EMAIL'), env('APP_NAME'));
        });
    }

    public function convertNumbers($number)
    {
        $easternArabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $westernArabic = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        return str_replace($easternArabic, $westernArabic, $number);
    }

    public function statusCodes($status = false)
    {
        $array = [
            'invalid_email' => 101,
            'email_exists' => 102,
            'mobile_exists' => 103,
            'username_exists' => 104,
            'mobile_invalid' => 105,
            'create_error' => 203,
            'activation_code_missing' => 106,
            'activation_code_invalid' => 107,
            'user_inactive' => 108,
            'user_already_activated' => 109,
            'activation_code_wrong' => 110,
            'allow_extention_error' => 111,
            'exceed_activition_code' => 112,
            'order_not_found' => 113,
            'not_found' => 127,
            'order_accepted' => 114,
            'order_in_landery' => 115,
            'order_car_finish' => 116,
            'order_complete' => 117,
            'promo_invalid' => 118,
            'promo_user_exist' => 119,
            'already_rate' => 120,
            'fav_exists' => 121,
            'fav_id_not_exists' => 122,
            'outOfStock' => 123,
            'promo_not_found' => 124,
            'promo_not_valid' => 125,
            'product_not_available_limit' => 126,
            'product_not_available' => 167,
            'json_error' => 167,
            'success' => 200,
            'data_required' => 201,
            'update_error' => 204,
            'pdo_exception' => 205,
            'invalid_token' => -1,
            'token_blacklisted' => -2,
            'token_expired' => -3,
            'invalid_credentials' => -4,
            'could_not_create_token' => -5,
            'user_not_found' => -6,
            'error' => 400,
            'follow_exists' => -7,
            'cat_not_found' => -8,
        ];
        if ($status) {
            return $array[$status];
        }
        return $array;
    }

    public function getSettingsGroups($groups)
    {
        if (!is_array($groups)) {
            $groups = explode(',', $groups);
        }
        $setting_manager = new \App\Repositories\SettingRepository(app(), \Illuminate\Support\Collection::make());
        $settings = $setting_manager->findBySetGroup($groups, ['key_id', 'value']);
        return $settings;
    }

    public function checkMobileValid($mobile, $country)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($mobile, strtoupper($country), null, true);
            $isValid = $phoneUtil->isValidNumber($phoneNumber);
            $isMobile = $phoneUtil->getNumberType($phoneNumber);
            $phoneNumberCarrier = \libphonenumber\PhoneNumberToCarrierMapper::getInstance()->getNameForNumber($phoneNumber, 'AR');
            if (!$isValid || $isMobile !== 1) {
                return false;
            }
            return str_replace('+', '', $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164));
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    public function checkNumberValid($number, $country)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($number, strtoupper($country), null, true);
            $isValid = $phoneUtil->isValidNumber($phoneNumber);
            $numberType = $phoneUtil->getNumberType($phoneNumber);
            $phoneNumberCarrier = \libphonenumber\PhoneNumberToCarrierMapper::getInstance()->getNameForNumber($phoneNumber, 'AR');
            if (!$isValid) {
                return false;
            }
            $resultNumber = str_replace('+', '', $phoneUtil->format($phoneNumber, \libphonenumber\PhoneNumberFormat::E164));

            return ['number' => $resultNumber, 'type' => $numberType];
        } catch (\libphonenumber\NumberParseException $e) {
            return false;
        }
    }

    public function outApiJson($status, $statusCode, $data = null, $responseStatus = 200)
    {

        $outData = [];
        $outData['status'] = $status;
        $outData['code'] = $this->statusCodes($statusCode);
        $outData['message'] = trans('api.'.$statusCode);
        if ($data) {
            $outData['data'] = $data;
        }
//        else{
//            $outData['data'] = [];
//        }
        return response()->json($outData, $responseStatus);
    }

    public function pushFire($reference, $data)
    {

        $serviceAccount = ServiceAccount::fromJsonFile(storage_path('app/public/') . '/nase3-72b6b-firebase-adminsdk-88yy7-d9f9191fe4.json');
        $apiKey = 'AIzaSyCOxH7bZoanwSUGYW8Ab13deQeUaFwPe74';
        $firebase = (new Factory)
            ->withServiceAccountAndApiKey($serviceAccount, $apiKey)
            ->create();
        $db = $firebase->getDatabase();

        $postRef = $db->getReference($reference)
            ->push($data);
        return $postRef->getKey();
    }

    public function getDataChat($date)
    {
        $last_activity = new Carbon($date);
        $day = $last_activity->format('D');
        $a = $last_activity->format('A');
        switch ($day) {
            case 'Sun':
                $dd = 'الاحد';
                break;
            case 'Mon':
                $dd = 'الاثنين';
                break;
            case 'Tue':
                $dd = 'الثلاثاء';
                break;
            case 'Wed':
                $dd = 'الاربعاء';
                break;
            case 'Thu':
                $dd = 'الخميس';
                break;
            case 'Fri':
                $dd = 'الجمعة';
                break;
            case 'Sat':
                $dd = 'السبت';
                break;
        }
        if ($a == 'AM') {
            $aa = 'ص';
        } else {
            $aa = 'م';
        }
        return ['day' => $dd, 'date' => $last_activity->format('Y/m/d'), 'time' => $last_activity->format('h:i') . ' ' . $aa];
    }

    /**
     * @param $message
     * @param $device_token
     * @param null $user_id
     * @param null $order
     * @param null $product
     * @param null $url
     * @param string $title
     * @param null $cat
     * @return false|int
     */
    public function sendPush($message, $device_token,$user_id=null,$order=null,$product=null,$url=null,$title='تغيير حالة الطلب',$cat=null)
    {

//        try {
//            $subject = 'تغيير حالة الطلب';
//            $client = new \GuzzleHttp\Client([
//                'base_uri' => 'https://onesignal.com',
//                'verify' => false,
//                'headers' => ['Content-Type' => 'application/json; charset=utf-8', 'Authorization' => 'Basic NjE3NjI3OTgtODhlYy00NzdmLTgxZjQtOTgxNzcxMWNlZGFj'],
//            ]);
//            $data = ['app_id' => 'b2cace5d-7445-47e3-94ca-b3401449c6c3', 'include_player_ids' => $device_token, 'data' => ['subject' => $subject, 'message' => $message], 'contents' => ['en' => $message], 'headings' => ['en' => $subject]];
//            $response = $client->post('api/v1/notifications', ['json' => $data]);
//            return true;
//        }catch (ConnectException $e){
//            return false;
//        }
        if (is_array($user_id)){
            foreach ($user_id as $row){
                Notifications::create([
                    'user_id' => $row,
                    'status' => 0,
                    'title' => $title,
                    'message' => $message,
                    'order_id' => $order,
                    'product_id' => $product,
                    'url' => $url,
                    'cat_id' => $cat,
                ]);
            }
        }else{
            Notifications::create([
                'user_id' => $user_id,
                'status' => 0,
                'title' => $title,
                'message' => $message,
                'order_id' => $order,
                'product_id' => $product,
                'url' => $url,
                'cat_id' => $cat,
            ]);
        }
        $subject = $title;
        try {
            $optionBuiler = new OptionsBuilder();
            $optionBuiler->setTimeToLive(60 * 60 * 24);
            $notificationBuilder = new PayloadNotificationBuilder($subject);
            $notificationBuilder->setBody($message)
                ->setSound('default')->setBadge(1);
            $dataBuilder = new PayloadDataBuilder();
            $dataPush =[
                'subject'=>$subject,
                'message'=>$message
            ];
            if ($url){
                $dataPush['url']=$url;
            }
            if ($product){
                $dataPush['product_id']=$product;
            }
            if ($order){
                $dataPush['order_id']=$order;
            }if ($cat){
                $dataPush['cat_id']=$cat;
            }
            $dataBuilder->addData($dataPush);
            $option = $optionBuiler->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();
            $token = $device_token;
            $downstreamResponse = \FCM::sendTo($token, $option, $notification, $data);
            if ($downstreamResponse->numberSuccess() > 0) {
                $status = 1;
            } else {
                $status = 0;
            }
            return $status;
        }catch (ConnectException $ex){
            return false;
        }
    }

    /**
     * @param $destinationPath
     * @param $fileName
     */
    public function createThumb($destinationPath, $fileName)
    {
        foreach ($this->thumeSize() as $key => $val) {
//            Image::make($destinationPath . '/' . $fileName)->resize($val['width'], $val['height'])->save($destinationPath  . '/' . $fileName);
            Image::make($destinationPath . '/' . $fileName)->resize($val['width'], $val['height'])->save($destinationPath . '/' . $key . '/' . $fileName);
        }

    }

    /**
     * @return array
     */
    public function thumeSize()
    {
        $size = [
            //            'thumb' => ['width' => 195, 'height' => 197]
            'thumb' => ['width' => 312, 'height' => 250]
        ];
        return $size;
    }

    public function getCountryFromIP($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}
