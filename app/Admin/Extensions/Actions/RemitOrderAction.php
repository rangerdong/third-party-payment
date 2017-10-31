<?php
namespace App\Admin\Extensions\Actions;

use App\Lib\Status\RemitStatus;
use Encore\Admin\Admin;

class RemitOrderAction
{
//    protected $id;
//    protected $status;

    public function __construct()
    {
//        $this->id = $id;
//        $this->status = $status;
    }

    public function script()
    {
        $url = route('api.order.remit.operate');
        $token = csrf_token();
        return <<<script
$('.grid-remit-operate').on('click', function() {
    var type = $(this).data('type')
    var id = $(this).data('id')
    var reason = '账户信息不正确'
    var settleif = $('#settle_if').val()
    if(type == 'refuse') {
        swal({ 
          title: "输入拒绝理由！", 
          text: '',
          type: "input", 
          showCancelButton: true, 
          closeOnConfirm: false, 
          animation: "slide-from-top", 
          inputPlaceholder: "拒绝理由" 
        },
        function(inputValue){ 
          if (inputValue === false) return false; 
          
          if (inputValue === "") { 
            swal.showInputError("你需要输入拒绝理由！");
            return false 
          } 
          reason = inputValue;
          postAudit();
        });
    } else if (type == 'remit') {
        if (settleif == 0) {
            swal('请选择接口厂商', '', 'error');
        } else {
            postAudit();
        }
    } else {
        postAudit();
    }
    
    function postAudit() {
    $.ajax({
        type: 'post',
        url: '{$url}',
        data: {
                _token:'{$token}',
                audit: type,
                id: id,
                reason: reason,
                settle: settleif
            },
        success: function (data) {
        $.pjax.reload('#pjax-container');
            if (typeof data === 'object') {
                if (data.code == 0) {
                    swal(data.message, '', 'success');
                } else {
                    swal(data.message, '', 'error');
                }
            }
        }
    });
    }
});
script;
    }

    public function render($id, $status)
    {
        Admin::script($this->script());
        return $this->getItemActions($id, $status);
    }

    public function getBatchActions($id, $status)
    {
        $pass = "<a class='label btn-sm btn-success grid-remit-operate' data-type='pass' data-id='{$id}'>通过</a>";
        $refuse = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='refuse' data-id='{$id}'>拒绝</a>";
        $html = '';
        switch ($status) {
            case RemitStatus::PENDING_REVIEW:
                $html .= $pass . $refuse; break;
            case RemitStatus::REFUSE_REVIEW:
                $html .= $pass; break;
            default:
                break;
        }
        return $html;
    }

    public function getItemActions($id, $status)
    {
//        $pass = "<a class='label btn-sm btn-success grid-remit-operate' data-type='pass' data-id='{$this->id}'>通过</a>";
        $remit = "<a class='label btn-sm btn-info grid-remit-operate' data-type='remit' data-id='{$id}'>打款</a>";
        $remitted = "<a class='label btn-sm btn-success grid-remit-operate' data-type='remitted' data-id='{$id}'>已打款</a>";
        $revoke = "<a class='label btn-sm btn-warning grid-remit-operate' data-type='revoke' data-id='{$id}'>撤销</a>";
        $return = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='return' data-id='{$id}'>退回</a>";
//        $fail = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='fail' data-id='{$id}'>打款失败</a>";
        $html = '';
        switch ($status) {
//            case RemitStatus::PENDING_REVIEW:
//                $html .= $pass . $refuse; break;
            case RemitStatus::PENDING_REMIT:
                $html .= $remit . $remitted . $revoke . $return;break;
            case RemitStatus::REMITTING:
                $html .= $remitted; break;
            case RemitStatus::REMIT_SUCCESS:
                $html .= ''; break;
            case RemitStatus::REMIT_FAILED:
                $html .= $remitted; break;
//            case RemitStatus::REVIEW_FAILED:
//                $html .= $pass; break;
            default:
                break;
        }
        return $html;
    }

//    public function __toString()
//    {
//        // TODO: Implement __toString() method.
//        return $this->render();
//    }

}
