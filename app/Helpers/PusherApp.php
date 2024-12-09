<?php

namespace App\Helpers;

use Pusher\Pusher;

class PusherApp {
    public static function pushNotifications($data = array())
    {
        $options = array(
            'cluster' => 'mt1',
            'useTLS' => true
          );
      
          $pusher = new Pusher(
            env('PUSHER_KEY'),
            env('PUSHER_SECRET'),
            env('PUSHER_APP_ID'),
            $options
          );
          $pusher->trigger('orders', 'notifications', $data);
    }
}
