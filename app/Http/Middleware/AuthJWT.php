<?php

namespace App\Http\Middleware;

use App\Lib\Code;
use App\Services\ApiResponseService;
use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthJWT
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
            $user = JWTAuth::toUser($request->input('token'));
        } catch (\Exception $e) {
            if ($e instanceof TokenInvalidException){
                return ApiResponseService::showError(Code::JWT_INVALID);
            }else if ($e instanceof TokenExpiredException){
                return ApiResponseService::showError(Code::JWT_EXPIRED);
            }else{
                return ApiResponseService::showError(Code::FATAL_ERROR, $e->getMessage());
            }
        }
        return $next($request);
    }
}
