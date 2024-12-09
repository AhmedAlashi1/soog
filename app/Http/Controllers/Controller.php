<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\MessageBag;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function success($message,$status = 200)
    {
        return response()->json(['status' => 'true'  ,'code' => 200  ,'message'=>'Success', 'data' => $message],$status)->header('Content-type','application/json');
    }
    public static function error($message , $status = 200)
    {
        $messageCount = 1;
        if (is_array($message))
            $messageCount = sizeof($messageCount);
        elseif ($message instanceof Controller){
            $messageCount =$message->count();
        }
        if ($message instanceof MessageBag)
            $message= $message->first();
        return response()->json(['status' => false,'code' => 201   , 'message' => $message],$status)->header('Content-type','application/json');

    }
}
