<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
//        $payload = JWTFactory::make($credentials);
//        $token = JWTAuth::encode($payload);
//        dd($token);
        $token = null;
        try {
            if ( ! $token = JWTAuth::attempt($credentials)) {
                return ApiResponseService::showError(Code::LOGIN_INVALID);
            }
        } catch (JWTException $jwt) {
            return ApiResponseService::showError(Code::JWT_ERROR);
        }
        return ApiResponseService::returnData(compact('token'));
    }


    public function register(Request $request)
    {
        $tmp = $request->only('username', 'phone');
    }

    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return ApiResponseService::returnData(compact('user'));
    }
}
