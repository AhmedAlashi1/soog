<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helpers;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {

        $requestArray = ['ip' => $request->ip(), 'fullUrl' => $request->fullUrl(), 'all' => $request->all(), 'header' => $request->header()];
        $isApi = starts_with($request->route()->getPrefix(), 'api');
        if (!$request->secure() && env('APP_ENV') === 'production') {
            if ($isApi) {
                Helpers::addApiLog('http', 0, $requestArray);
                exit;
            }
            $currentPath = \Route::getFacadeRoot()->current()->uri();
            return redirect()->secure($currentPath);
        }
        return $next($request);
    }

}
