<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class AdminActivation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->is_active==0) {
                Auth::logout();
                return redirect()->route('auth-login-form')->withErrors(trans('auth.email_active'));
            }
        }
        return $next($request);
    }

}
