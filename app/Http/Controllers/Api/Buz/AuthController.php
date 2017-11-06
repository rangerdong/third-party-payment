<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Models\PlatUser;
use App\Models\PlatUserTmp;
use App\Services\ApiResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
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
        $user = PlatUser::where('username', $tmp['username'])->first();
        if ($user) return ApiResponseService::showError(Code::REGISTER_USER_EXISTS);
        $expired_at = time() + 5 * 60;
        $token = md5($expired_at . $tmp['username'] . $tmp['phone']);
        $tmp += compact('token', 'expired_at');
        if (! $tmpUser = PlatUserTmp::where('username', $tmp['username'])->first()) {
            PlatUserTmp::create($tmp);
        } else {
            PlatUserTmp::where('username', $tmp['username'])->update($tmp);
        }
        return ApiResponseService::success(Code::SUCCESS);
    }

    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return ApiResponseService::returnData(compact('user'));
    }
}
