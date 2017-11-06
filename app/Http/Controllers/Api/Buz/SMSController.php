<?php
namespace App\Http\Controllers\Api\Buz;

use App\Http\Controllers\Controller;
use App\Services\Lib\SMSServiceFactory;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    protected $smsService;

    public function __construct(SMSServiceFactory $SMSServiceFactory)
    {
        $this->smsService = $SMSServiceFactory->getSMSService(config('sms.driver'));
    }

    public function sendSms(Request $request)
    {
        $type = $request->input('type', 1);
        $to = $request->input('to');
        $content = '11111 test';
        $this->smsService->sendSMS($to, $content);
        return 'success';
    }
}
