<?php
namespace App\Services;

use App\Lib\SMSCode;
use Illuminate\Support\Facades\Cache;

class SMSService
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = config('sms.cache_prefix');
    }

    protected function getKey($mobile)
    {
        return $this->prefix . $mobile;
    }

    /**
     * @param $to
     * @param $type
     *
     * @return string
     */
    public function getContent($to, $type)
    {
        $content = SMSCode::getErrorMsg($type);
        switch ($type) {
            case SMSCode::REGISTER:
                $code = random_int(1000, 9999);
                $this->setCacheCode($to, $code);
                $content = sprintf($content, $code);
                break;
        }
        return $content;
    }

    public function setCacheCode($mobile, $code)
    {
        Cache::put($this->getKey($mobile), $code, config('sms.lifetime'));
    }

    public function getCache($mobile)
    {
        return Cache::get($this->getKey($mobile));
    }

    public function verifySMSCode($code, $mobile)
    {
        $sms = $this->getCache($mobile);
        if ($sms) {
            if ($sms == $code) {
                Cache::forget($this->getKey($mobile));
                return true;
            }
        }
        return false;
    }
}
