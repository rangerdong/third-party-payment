<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Api\BuzBaseController;
use App\Lib\Code;
use App\Services\ApiResponseService;
use App\Validators\Buz\ProfileValidator;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class ProfileController extends BuzBaseController
{
    public function authentication(Request $request,
                                   ProfileValidator $profileValidator)
    {
        try {
            $data = $request->all(array_keys($profileValidator->getRules('authorize')));
            $profileValidator->with($data)->passesOrFail('authorize');
            $data += [
                'uid' => $this->getUserFromJWT()->id,
            ];
            dd($data);
        } catch (ValidatorException $exception) {
            return ApiResponseService::showError(Code::HTTP_REQUEST_PARAM_ERROR, $exception->getMessageBag()->messages());
        }
    }

}
