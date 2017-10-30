<?php
namespace App\Admin\Extensions\Actions;

use Encore\Admin\Admin;

class RemitOrderAction
{
    protected $id;
    protected $status;

    public function __construct($id, $status)
    {
        $this->id = $id;
        $this->status = $status;
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
    if(type == 'refuse') {
        swal({ 
          title: "输入拒绝理由！", 
          text: reason,
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
                reason: reason
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

    public function render()
    {
        Admin::script($this->script());
        $pass = "<a class='label btn-sm btn-success grid-remit-operate' data-type='pass' data-id='{$this->id}'>通过</a>";
        $refuse = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='refuse' data-id='{$this->id}'>拒绝</a>";
        $remit = "<a class='label btn-sm btn-success grid-remit-operate' data-type='remit' data-id='{$this->id}'>已打款</a>";
        $revoke = "<a class='label btn-sm btn-warning grid-remit-operate' data-type='revoke' data-id='{$this->id}'>撤销</a>";
        $return = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='revoke' data-id='{$this->id}'>退回</a>";
        $fail = "<a class='label btn-sm btn-danger grid-remit-operate' data-type='revoke' data-id='{$this->id}'>打款失败</a>";
        $html = '';
        switch ($this->status) {
            case 0:
                $html .= $pass . $refuse; break;
            case 1:
                $html .= $remit . $revoke . $return;break;
            case 2:
                $html .= $remit . $fail; break;
            case 3:
                $html .= ''; break;
            case 4:
                $html .= $remit; break;
            case 5:
                break;
            case 6:
                break;
            case 7:
                $html .= $pass; break;
            default:
                break;
        }
        return $html;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->render();
    }

}
