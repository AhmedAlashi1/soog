<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{

    protected $table = 'sms_log';
    protected $guarded = ['id'];

//    public function getCreatedAtAttribute($date)
//    {
//        $geoIp = \GeoIp::getLocation();
//        $dtz = new \DateTimeZone($geoIp['timezone']);
//        $time_in_sofia = new \DateTime('now', $dtz);
//        $offset = $dtz->getOffset( $time_in_sofia ) / 3600;
//        return $date.' (' . "GMT" . ($offset < 0 ? $offset : "+".$offset) . ')';
//    }

}
