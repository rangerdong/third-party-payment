<?php
namespace App\Services\Gateway;

use Illuminate\Support\Facades\Log;

class RechargeLogService
{
    protected $today;

    public function __construct()
    {
        $this->today = date('Ymd');
    }

    /**
     * @param $level INFO DEBUG ERROR SUCCESS WARING
     * @param $message
     * @param array $data
     */
    public function third($level, $message, $data=[])
    {
        Log::useFiles(storage_path().'/logs/recharge/third-party/'.$this->today.'.log');
        Log::$level($message."\t [data]:".json_encode($data));
    }

    public function mch($level, $message, $data=[])
    {
        Log::useFiles(storage_path().'/logs/recharge/merchant/'.$this->today.'.log');
        Log::$level($message."\t [data]:".json_encode($data));
    }

    public function common($level, $message, $data=[])
    {
        Log::useFiles(storage_path().'/logs/recharge/common-'.$this->today.'.log');
        Log::$level($message."\t [data]:".json_encode($data));
    }
}
