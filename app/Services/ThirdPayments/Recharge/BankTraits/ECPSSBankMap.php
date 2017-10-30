<?php
namespace App\Services\ThirdPayments\Recharge\BankTraits;

use App\Lib\BankMap;

trait ECPSSBankMap
{
    private $bankMap = [
        BankMap::GONGSHANG  => 'ICBC',
        BankMap::NONGYE     => 'ABC',
        BankMap::CHINABANK  => 'BOCSH',
        BankMap::JIANSHE    => 'CCB',
        BankMap::ZHAOSHANG  => 'CMB',
        BankMap::PUFA       => 'SPDB',
        BankMap::GUANGFA    => 'GDB',
        BankMap::JIAOTONG   => 'BOCOM',
        BankMap::YOUZHEN    => 'PSBC',
        BankMap::ZHONGXIN   => 'CNCB',
        BankMap::MINSHENG   => 'CMBC',
        BankMap::GUANGDA    => 'CEB',
        BankMap::HUAXIA     => 'HXB',
        BankMap::XINGYE     => 'CIB',
        BankMap::SHANGHAI   => 'BOS',
        BankMap::PINGAN     => 'PAB',
        BankMap::BEIJING    => 'BCCB'
    ];

    public function getBank($bankCode)
    {
        if (array_key_exists($bankCode, $this->bankMap)) {
            return $this->bankMap[$bankCode];
        } else {
            return 'OTHERS';
        }
    }
}
