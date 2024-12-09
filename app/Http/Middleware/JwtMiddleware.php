<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException as TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException as TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException as TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException as JWTException;
use Tymon\JWTAuth\Exceptions\InvalidClaimException as InvalidClaimException;
use Tymon\JWTAuth\Exceptions\PayloadException as PayloadException;
use Config;
use App\Helpers\Functions;

class JwtMiddleware
{
    use Functions;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isset($request->token)) {
            return $this->outApiJson(false,'invalid_token');
        }
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->outApiJson(false,'user_not_found');
            }
        } catch (TokenExpiredException $e) {
            return $this->outApiJson(false,'token_expired');
        } catch (TokenInvalidException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (JWTException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (InvalidClaimException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (PayloadException $e) {
            return $this->outApiJson(false,'invalid_token');
        } catch (TokenBlacklistedException $e) {
            return $this->outApiJson(false,'invalid_token');
        }
        $request->user = $user;
        // the token is valid and we have found the user via the sub claim
        return $next($request);
    }

}
