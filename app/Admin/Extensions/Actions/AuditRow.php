<?php
namespace App\Admin\Extensions\Actions;

use Encore\Admin\Admin;

class AuditRow
{
    protected $id;
    protected $pass;
    protected $refuse;
    protected $href;
    public function __construct($href, $id, $pass=true, $refuse=true)
    {
        $this->id = $id;
        $this->pass = $pass;
        $this->refuse = $refuse;
        $this->href = $href;
    }

    protected function script()
    {
        $url = $this->href;
        $token = csrf_token();
        return <<<script
$('a.grid-audit').on('click', function () {
    var type = $(this).attr('data-type');
    var id = $(this).data('id');
    var reason = '';
    if(type == 'refuse') {
        swal({ 
          title: "输入拒绝理由！", 
          text: "",
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

    protected function render()
    {
        Admin::script($this->script());
        $pass = "<a class='grid-audit' href='javascript:void(0);' data-id='{$this->id}' data-type='pass'><i class='fa fa-check-circle-o'></i> </a>";
        $refuse = "<a class='grid-audit' href='javascript:void(0);' data-id='{$this->id}' data-type='refuse'><i class='fa fa-times-circle-o'></i> </a>";
        $str = '';
        if ($this->pass) {
            $str .= $pass;
        }
        if ($this->refuse) {
            $str .= $refuse;
        }
        return $str;

    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->render();
    }
}
