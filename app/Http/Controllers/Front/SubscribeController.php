<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class SubscribeController extends Controller
{
    public function index()
    {
        return view("Front.LandingPage.Subscribe");
    }
}