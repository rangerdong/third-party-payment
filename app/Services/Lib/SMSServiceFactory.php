<?php
namespace App\Services\Lib;


class SMSServiceFactory
{
    public function getSMSService($identify)
    {
        $instance = null;
        switch ($identify) {
            case 'winic':
                $instance =  new WinicSMSService(); break;
        }
        if ($instance == null) {
            throw new \Exception('短信接口有误');
        }
        return $instance;

    }
}
