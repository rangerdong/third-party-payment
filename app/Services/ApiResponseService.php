<?php

namespace App\Services;

use  App\Lib\Code;
use Illuminate\Database\Eloquent\Collection;

class ApiResponseService
{
	public static function returnData($data,$msg = 'done' ){
		return $data?ApiResponseService::success(Code::SUCCESS,$msg,$data):ApiResponseService::showError(Code::NOTFOUND,'not info');
	}


    public static function success($code = 0, $message = 'success', $data=[])
	{
		$result = [
			'data' 		=> $data,
			'code' 		=> $code,
			'message' 	=> $message	
		];
		return response()->json($result);
	}



	public static function showError($code, $message='') {
        if ($message == '') {
            $message = Code::getErrorMsg($code);
        }
		$message = $message ? $message : Code::getErrorMsg($code);
		$result = [
			'code' 		=> $code,
			'message' 	=> $message
		];
		return response()->json($result);
	}
}


