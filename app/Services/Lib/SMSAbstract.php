<?php
namespace App\Services\Lib;

abstract class SMSAbstract
{
    abstract public function sendSMS($to, $content);
}
