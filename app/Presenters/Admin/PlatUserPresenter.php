<?php
namespace App\Presenters\Admin;

use App\Models\PlatUserProfile;

class PlatUserPresenter
{
    public static function showStatus($status):string
    {
        switch ($status) {
            case -1:
                return "<span class='label label-default'>禁用</span>";break;
            case 0:
                return "<span class='label label-warning'>未审核</span>";break;
            case 1:
                return "<span class='label label-success'>已审核</span>"; break;
            case 2:
                return "<span class='label label-danger'>审核拒绝</span>"; break;
            default:break;
        }
    }

    public static function showProfile($profile_id)
    {

    }
}

