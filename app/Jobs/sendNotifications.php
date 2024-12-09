<?php

namespace App\Jobs;

use App\Models\AppUser;
use App\Models\Categories;
use App\Models\Clothes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class sendNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $skip = 0;
        $take = 0;
        $count = AppUser::count();
        $c = round($count / 1000) + 1;
        $url = $this->data->url ?? "";
        $title =  $this->data->title ?? "";
        $body = $this->data->message ?? "";
        $cat_id = $this->data->cat_id ?? 0;
        $pro_id = $this->data->product_id ?? 0;
        $cat = Categories::where('id' , $cat_id)->first();
        $Clothes = Clothes::where('id' , $pro_id)->first();
        $catigore = $cat->title_ar ?? "";
        $prodect = $Clothes->title_ar ?? "";
        for ($r = 1; $r < $c; $r++) {
            $take = 1000;
            $device_token = AppUser::where('device_token', '!=', '')->skip($skip)
                ->take($take)->pluck('device_token')->toArray();
            $skip += $take;
            $dataa = [
                "registration_ids" => $device_token,
            "notification" => [
                "title" => $title,
                "body" => $catigore . ' ' . $prodect .' ' . $body,
                "url" => $url,
            ]
            ];
            $dataString = json_encode($dataa);
            $headers = [
                'Authorization:key=AAAAyNv4XM4:APA91bG2LLYhnWhlCeyruuWk2JANSzG2O8h1NpqD2zDv68Da5zTgQfc4UgPjdwEbK_JDOdkbf8uFpgWtnWHyzjq484P4_2ntc0vaqqLa_Hegu2Lhlxz6JQZCM2pU-nTFBy6WdLPDcAug',
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
        }
//        $data = [
//            "registration_ids" => ['fmLy_hMrQie9gF8LERez8r:APA91bGIKYp1rknxSLNHJS3KELkKPjxbSA3I024n07tYpIj6OFpeFpZl7QbzcwjN6BU_LlYIBBObSMvx8dB32LUFUsqikpgi1sokBDgLdIsow2Ro-goJQ_IwEFkMH5Ca2yTPCKR2AcZI'],
//            "notification" => [
//                "title" => 'test',
//                "body" => 'test',
//                "sound" => "default",
//            ]
//        ];
//        $dataa = [
//            "registration_ids" => ['fmLy_hMrQie9gF8LERez8r:APA91bGIKYp1rknxSLNHJS3KELkKPjxbSA3I024n07tYpIj6OFpeFpZl7QbzcwjN6BU_LlYIBBObSMvx8dB32LUFUsqikpgi1sokBDgLdIsow2Ro-goJQ_IwEFkMH5Ca2yTPCKR2AcZI'],
//            "notification" => [
//                "title" => $title,
//                "body" => $catigore . ' ' . $prodect .' ' . $body,
//                "url" => $url,
//            ]
//        ];
//        $dataString = json_encode($data);
//        $headers = [
//            'Authorization:key=AAAAyNv4XM4:APA91bG2LLYhnWhlCeyruuWk2JANSzG2O8h1NpqD2zDv68Da5zTgQfc4UgPjdwEbK_JDOdkbf8uFpgWtnWHyzjq484P4_2ntc0vaqqLa_Hegu2Lhlxz6JQZCM2pU-nTFBy6WdLPDcAug',
//            'Content-Type: application/json',
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
//        $response = curl_exec($ch);
    }
}
