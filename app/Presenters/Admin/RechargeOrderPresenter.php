<?php
namespace App\Presenters\Admin;

class RechargeOrderPresenter
{
    public static function showStatus($status):string
    {
        switch ($status) {
            case 0:
                return "<span class='label label-warning'>未完成</span>";break;
            case 1:
                return "<span class='label label-success'>已完成</span>"; break;
            case -1:
                return "<span class='label label-danger'>已失败</span>"; break;
            default:break;
        }
    }
}
