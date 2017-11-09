<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Jobs\DoRegisterEmail;
use App\Lib\Code;
use App\Models\PlatUser;
use App\Models\PlatUserTmp;
use App\Models\RechargeGroup;
use App\Models\SettleGroup;
use App\Repositories\Contracts\PlatUserRepository;
use App\Services\ApiResponseService;
use App\Validators\Buz\AuthValidator;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Prettus\Validator\Exceptions\ValidatorException;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AuthController extends Controller
{
    use DispatchesJobs;
    protected $authValidator;

    public function __construct(AuthValidator $authValidator)
    {
        $this->authValidator = $authValidator;
    }

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
            $tmpUser = PlatUserTmp::create($tmp);
        } else {
            $tmpUser->update($tmp);
        }
        $this->dispatch(new DoRegisterEmail($tmpUser));
        return ApiResponseService::success(Code::SUCCESS);
    }

    public function doRegister(Request $request)
    {
        $data = $request->all();
        try {
            $this->authValidator->with($data)->passesOrFail('register');
            $userTmp = PlatUserTmp::where('token', $data['token'])->first();
            if ( ! $userTmp) {
                return ApiResponseService::showError(Code::REGISTER_TOKEN_INVALID);
            }
            //获取默认分组
            $defaultRechargeGroup = RechargeGroup::byDefault()->byBuz()->first();
            $defaultSettleGroup = SettleGroup::byDefault()->byBuz()->first();
            $userInfo = $data;
            array_forget($userInfo, 'token');
            $userInfo['username'] = $userTmp['username'];
            $userInfo['phone'] = $userTmp['phone'];
            $userInfo['recharge_gid'] = $defaultRechargeGroup ? $defaultRechargeGroup->id : 0;
            $userInfo['settle_gid'] = $defaultSettleGroup ? $defaultSettleGroup->id : 0;
            if (PlatUser::create($userInfo)) {
                $userTmp->delete();
            }
            return ApiResponseService::success(Code::SUCCESS);
        } catch (ValidatorException $exception) {
            return ApiResponseService::showError(Code::HTTP_REQUEST_PARAM_ERROR);
        } catch (\Exception $exception) {
            return ApiResponseService::showError(Code::FATAL_ERROR, $exception->getMessage());
        }
    }

    public function getAuthUser(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return ApiResponseService::returnData(compact('user'));
    }
}
