<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
//use App\Models\Ads;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function index($id) {
        if ($id == null || empty($id)) {
            return view('Front.LandingPage.main');
        }else {
            return view('Front.LandingPage.chat');
        }
    }
}
