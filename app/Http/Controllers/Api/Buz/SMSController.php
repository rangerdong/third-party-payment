<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Lib\Code;
use App\Lib\SMSCode;
use App\Services\ApiResponseService;
use App\Services\Lib\SMSServiceFactory;
use App\Services\SMSService;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    protected $smsService;

    public function __construct(SMSServiceFactory $SMSServiceFactory)
    {
        $this->smsService = $SMSServiceFactory->getSMSService(config('sms.driver'));
    }

    public function sendSms(Request $request, SMSService $SMSService)
    {
        $type = $request->input('type', 1);
        $to = $request->input('to');
        $content = SMSCode::getErrorMsg($type);
        switch ($type) {
            case SMSCode::REGISTER:
                $code = random_int(1000, 9999);
                $SMSService->setCacheCode($to, $code);
                $content = sprintf($content, $code);
                break;
        }
        echo $SMSService->getCache($to);
        exit();
        if ($this->smsService->sendSMS($to, $content)) {
            return ApiResponseService::success(Code::SUCCESS);
        } else {
            return ApiResponseService::showError(Code::FATAL_ERROR);
        }
    }

}
