<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Jobs\DoRegisterEmail;
use App\Lib\Code;
use App\Models\PlatUser;
use App\Models\PlatUserTmp;
use App\Services\ApiResponseService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    use DispatchesJobs;

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
            $userTmp = PlatUserTmp::create($tmp);
        } else {
            $userTmp = PlatUserTmp::where('username', $tmp['username'])->update($tmp);
        }
        $this->dispatch(new DoRegisterEmail($userTmp));
        return ApiResponseService::success(Code::SUCCESS);
    }

    public function doRegister(Request $request)
    {
        $data = $request->all();
        $userTmp = PlatUserTmp::where('token', $data['token'])
            ->where('username', $data['username'])
            ->first();
        if ($userTmp) {
            
        }
    }

    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return ApiResponseService::returnData(compact('user'));
    }
}
