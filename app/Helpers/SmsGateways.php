<?php

namespace App\Helpers;

use App\Models\SmsGate;
use App\Models\SmsLog;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use Twilio;
/**
 * Class Helpers
 * @package App\Helpers
 */
class SmsGateways
{

    public static function send( $gate, $message, $numbers)
    {
        /*try {
            $r = new \App\Repositories\Sms\Gateway($gate->gateway);
        } catch (Exception $ex) {
            self::insertSmsLog($numbers, $gate->sender, $message, $ex->getMessage(), false);
        }
        $send = $r->gateway->setUser($gate->username, $gate->password)
                ->setNumbers($numbers)
                ->setMessage($message)
                ->setSender($gate->sender)
                ->send();*/
                $response = '';
//        try{
//            $sub = substr($numbers, 0, 2);
//            $number = substr($numbers, 2);
//            if($sub == 00){
//                $numbers = $number;
//            }
//            $sms = Twilio::message('+'.$numbers, $message);
//            $gate_message =  'success send message';
//            $status = true;
//        } catch (Twilio\Exceptions\RestException $ex){
//            $gate_message = 'faild send message';
//            $status = false;
//        }
//        $client = new \GuzzleHttp\Client([
//            'base_uri' => 'http://www.kwtsms.com/'
//        ]);
        try {
            $sub = substr($numbers, 0, 2);
            $number = substr($numbers, 2);
            if($sub == 00){
                $numbers = $number;
            }
//            $response = $client->post('API/send/', ['form_params' => [
//                'username' => env('SMS_USER_NAME'),
//                'password' => env('SMS_PASSWORD'),
//                'sender' => env('SMS_SENDER'),
//                'message' => $message,
//                'mobile' => $numbers,
//                'lang' => 1,
//            ]
//            ]);
//
//            $jsonResponse = $response->getBody();
//
//            $result = explode(':',$jsonResponse->getContents());
//            if($result[0]=='OK'){
//                $gate_message =  'success send message';
//                $status = true;
//            }else{
//                $gate_message = $result[1];
//                $status = false;
//            }
        } catch (RequestException $ex) {
            $gate_message = 'faild send message';
            $status = false;
        } catch (ClientException $ex) {
            $gate_message = 'faild send message';
            $status = false;
        }
        self::insertSmsLog( $gate, $numbers, '96590070045', $message, '', $status, $gate_message);
        return $status;
    }

    public static function getBalance(\App\Models\SmsGate $gate)
    {
        try {
            $r = new \App\Repositories\Sms\Gateway($gate->gateway);
        } catch (Exception $ex) {

        }
        $balance = $r->gateway->setUser($gate->username, $gate->password)->getBalance();
        if ($balance['status']) {
            return ['status' => true, 'balance' => $balance['response']];
        }
        return ['status' => false, 'message' => $balance['message']];
    }

    public static function insertSmsLog( $gate, $numbers, $sender, $message, $response, $status, $gate_message)
    {

//        $smsRepo = new \App\Repositories\SmsLogRepository(app(), \Illuminate\Support\Collection::make());
        return SmsLog::create([
                    'numbers' => $numbers,
                    'sender' => $sender,
                    'message' => $message,
                    'status' => $status ? 'success' : 'failed',
                    'response' => $response,
                    'gate_message' => $gate_message,
                    'gate_id' => $gate->id,
                    'gateway' => 'kwtsms',
        ]);
    }

}
