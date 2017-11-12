<?php

namespace App\Exceptions;

use App\Lib\Code;
use App\Lib\GatewayCode;
use App\Services\ApiResponseService;
use App\Services\GatewayResponseService;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof RechargeGatewayException) {
            Log::info($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($request->getHost() == config('app.website.GATEWAY_DOMAIN')) {
            return GatewayResponseService::codeError(GatewayCode::SYSTEM_ERROR, $exception->getMessage());
        }
        if ($request->getHost() == config('app.website.BUZ_DOMAIN')) {
            if ($exception instanceof ValidatorException) {
                return ApiResponseService::showError(Code::HTTP_REQUEST_PARAM_ERROR, $exception->getMessageBag()->messages());
            } elseif ($exception instanceof Exception) {
                $debug = config('app.debug');
                return ApiResponseService::showError(Code::FATAL_ERROR, $debug ? $exception->getMessage() : '');
            }
        }
        return parent::render($request, $exception);
    }
}
