<?php
namespace App\Services;

use App\Lib\GatewayCode;

class GatewayResponseService
{
    public static function success()
    {
        $result = [
            'status' => 0,
            'messages' => 'success',

        ];
        return response()->json($result);
    }


    /**
     * @param array $messageBag
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * ex:
     * $messageBag = ['field' => 'ERR_CODE'];
     *
     * return:
     * {
     *   'status' => 500,
     *   'messages' => [
     *     'mch_code' => {
     *       'err_code' => 'MCH_CODE_NOT_EXISTS',
     *       'err_msg': '商户编码不存在'
     *     }
     *
     *   ]
     * }
     *
     *
     */
    public static function fieldError($messageBag=[])
    {
        $result = [
            'status' => 500,
            'messages' => self::getFieldError($messageBag)
        ];
        return response()->json($result);
    }

    /**
     * @param $code
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * $code =
     *
     *
     * return:
     * {
     *   'status' => 500,
     *   'messages' => {
     *       'err_code' => 'MCH_CODE_NOT_EXISTS',
     *       'err_msg': '商户编码不存在'
     *   }
     * }
     */
    public static function codeError($code)
    {
        $result = [
            'status' => 500,
            'messages' => [
                'err_code' => $code,
                'err_msg' => GatewayCode::getErrorMsg($code)
            ]
        ];
        return response()->json($result);
    }

    public static function getFieldError($messageBar=[])
    {
        $returnData = [];
        if (is_array($messageBar)) {
            foreach ($messageBar as $key => $bar) {
                $bar = is_array($bar) ? $bar[0] : $bar;
                $returnData[$key]['err_code'] = $bar;
                $returnData[$key]['err_msg'] = GatewayCode::getErrorMsg($bar);
            }
        }
        return $returnData;
    }
}
