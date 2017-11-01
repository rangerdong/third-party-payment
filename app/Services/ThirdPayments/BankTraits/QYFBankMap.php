<?php
namespace App\Services\ThirdPayments\BankTraits;

use App\Lib\BankMap;

trait QYFBankMap
{
    private $bankMap = [
        BankMap::GONGSHANG       =>  'ICBC-NET',
        BankMap::JIANSHE         =>  'CCB-NET',
        BankMap::CHINABANK       =>  'BOC-NET',
        BankMap::ZHAOSHANG       =>  'CMBCHINA-NET',
        BankMap::ZHONGXIN        =>  'ECITIC-NET',
        BankMap::XINGYE          =>  'CIB-NET',
        BankMap::GUANGDA         =>  'CEB-NET',
        BankMap::NONGYE          =>  'ABC-NET',
        BankMap::YOUZHEN         =>  'POST-NET',
        BankMap::SHENFA          =>  'SDB-NET',
        BankMap::GUANGFA         =>  'GDB-NET',
        BankMap::MINSHENG        =>  'CMBC-NET',
        BankMap::JIAOTONG        =>  'BOCO-NET',
        BankMap::ZHESHANG        =>  'CZ-NET',
        BankMap::SHANGHAI        =>  'SHB-NET',
        BankMap::NINGBO          =>  'NBCB-NET',
        BankMap::PUFA            =>  'SPDB-NET',
        BankMap::NANJING         =>  'NJCB-NET',
        BankMap::PINGAN          =>  'PINGANBANK',
    ];

    public function getBank($bankCode)
    {
        if (array_key_exists($bankCode, $this->bankMap)) return $this->bankMap[$bankCode];
        else return null;
    }
}
