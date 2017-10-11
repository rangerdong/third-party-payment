<?php
namespace App\Presenters\Admin;

class PlatUserAppPresenter
{
    public static function editHeaderAndDesc($app)
    {
        return [
            'header' => "[".config('dictionary.user_apps.'.$app->classify)."] {$app->name}",
            'desc' => '所属用户:' . $app->platuser->username
        ];
    }

    public static function showStatus($status):string
    {
        switch ($status) {
            case 0:
                return "<span class='label label-warning'>未审核</span>";break;
            case 1:
                return "<span class='label label-success'>已审核</span>"; break;
            case 2:
                return "<span class='label label-danger'>审核拒绝</span>"; break;
            default:break;
        }
    }
}
