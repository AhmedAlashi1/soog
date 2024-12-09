<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Predis\Response\ServerException as ClusterException;
use Predis\Connection\ConnectionException as ClusterConnectionException;

class BaseController extends Controller
{
    public function __construct()
    {


    }

}
