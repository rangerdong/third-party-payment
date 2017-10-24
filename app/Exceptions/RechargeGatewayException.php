<?php

namespace App\Exceptions;

use App\Lib\Code;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Facades\RechargeLog;

class RechargeGatewayException extends Exception
{
    const THIRD_LOG = Code::RECHARGE_THIRD_LOG;
    const MCH_LOG = Code::RECHARGE_MCH_LOG;
    //
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        switch ($code) {
            case self::THIRD_LOG:
                RechargeLog::third('INFO', $this); break;
            case self::MCH_LOG:
                RechargeLog::mch('INFO', $this); break;
            default:
                RechargeLog::common('INFO', $this);
                break;
        }
    }

    public function __toString()
    {
        return "[error]:".$this->getMessage() ." \t [file]:" . $this->getFile() ."\t->" . $this->getLine() ."\n" ;
    }
}
