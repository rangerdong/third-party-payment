<?php

namespace App\Http\Middleware;

use App\Lib\Code;
use App\Services\ApiResponseService;
use App\Services\SMSService;
use Closure;
use Illuminate\Support\Facades\Session;

class VerifySMSCode
{
    protected $smsService;

    public function __construct(SMSService $SMSService)
    {
        $this->smsService = $SMSService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! $request->has('sms_code')) return ApiResponseService::showError(Code::SMS_EMPTY);
        if ( ! $this->smsService->verifySMSCode($request->input('sms_code'), $request->input('phone'))) return ApiResponseService::showError(Code::SMS_INVALID);
        return $next($request);
    }
}
