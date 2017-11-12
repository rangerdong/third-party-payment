<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Api\BuzBaseController;
use App\Lib\Code;
use App\Repositories\Eloquent\PlatUserBankRepositoryEloquent;
use App\Repositories\Eloquent\PlatUserProfileEloquent;
use App\Services\ApiResponseService;
use App\Validators\Buz\ProfileValidator;
use App\Validators\Buz\RemitBankValidator;
use Illuminate\Http\Request;

class ProfileController extends BuzBaseController
{
    public function authentication(Request $request,
                                   ProfileValidator $profileValidator,
                                   PlatUserProfileEloquent $platUserProfileEloquent)
    {
            $data = $request->all(array_keys($profileValidator->getRules('authorize')));
            $profileValidator->with($data)->passesOrFail('authorize');
            $uid = $this->getUserFromJWT()->id;
            $data += compact('uid');
            $platUserProfileEloquent->updateOrCreate($data);
            return ApiResponseService::success(Code::SUCCESS);
    }

    public function remitBank(Request $request,
                              RemitBankValidator $bankValidator,
                              PlatUserBankRepositoryEloquent $bankEloquent)
    {
        $data = $request->all(array_keys($bankValidator->getRules('create')));
        $bankValidator->with($data)->passesOrFail('create');
        $uid = $this->getUserFromJWT()->id;
        $data += compact('uid');
        $bankEloquent->create($data);
        return ApiResponseService::success(Code::SUCCESS);
    }

}
