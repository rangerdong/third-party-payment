<?php
namespace App\Presenters\Admin;

use App\Lib\Status\RemitStatus;

class ToPayOrderPresenter
{
    public function itemScript()
    {
        $script = <<<script
$('button.collapse').on('click', function() {
    var id = $(this).data('id');
    console.log(id)
    $('button.expand[data-id='+id+']').show();
    $(this).hide();
    $('.item-table[data-id='+id+']').show();
});

$('button.expand').on('click', function() {
    var id = $(this).data('id');
    $('button.collapse[data-id='+id+']').show();
    $(this).hide();
    $('.item-table[data-id='+id+']').hide();
});
script;
        return $script;
    }

    public function batchStatus($item)
    {
        switch ($item->status) {
            case RemitStatus::PENDING_REVIEW:
                return "<span class='label label-warning'>未审核</span>"; break;
            case RemitStatus::REFUSE_REVIEW:
                return "<span class='label label-danger'>审核拒绝</span>"; break;
            case RemitStatus::PENDING_REMIT:
                return "<span class='label label-warning'>待打款</span>"; break;
            case RemitStatus::REMITTING:
                return "<span class='label label-info'>打款中</span>"; break;
            case RemitStatus::REMIT_SUCCESS:
                return "<span class='label label-success'>已打款</span>"; break;
            case RemitStatus::REMIT_FAILED:
                return "<span class='label label-danger'>打款失败</span>"; break;
            case RemitStatus::REMIT_CANCER:
                return "<span class='label label-primary'>已撤销</span>"; break;
            case RemitStatus::REMIT_RETURN:
                return "<span class='label label-default'>已退回</span>"; break;
        }
    }
}
