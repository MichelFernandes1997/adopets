<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTValidity  extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if(!$user)
            {
                return response()->json(['status' => 'Unauthenticated user!'], 401);
            }

            $token = JWTAuth::getToken();

            try{
                $token = JWTAuth::refresh($token);

                return $next($request);
            }catch(TokenInvalidException $e){
                if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                    return response()->json(['status' => 'Token is Invalid'], 401);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid'], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired'], 401);
            } else {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }
    }
}
