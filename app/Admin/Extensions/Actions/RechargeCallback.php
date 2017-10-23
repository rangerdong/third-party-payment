<?php
namespace App\Admin\Extensions\Actions;

use Encore\Admin\Admin;

class RechargeCallback
{
    protected $order_id;

    public function __construct($id)
    {
        $this->order_id = $id;
    }

    protected function script()
    {
        $url = route('api.order.recharge.callback');
        $token = csrf_token();
        return <<<script
$('a.grid-callback').on('click', function () {
    var id = $(this).data('id');
    console.log(id);
    layer.load(2);
    $.ajax({
        type: 'post',
        url: '{$url}',
        data: {
                _token:'{$token}',
                id: id
        },
        success: function (data) {
            if (typeof data === 'object') {
                if (data.code == 0) {
                    var curlReq = data.data.content
                    layer.closeAll('loading');
                    layer.open({
                        type:1,
                        title:false,
                        closeBtn:true,
                        area: '500px',
                        shade:0.8,
                        moveType:1,
                        content: curlReq
                    });
                } else {
                    layer.msg(data.message);
                }
            }
        }
    });
});
script;
    }

    protected function render()
    {
        Admin::script($this->script());
        return "<a class='grid-callback' href='javascript:void(0);' data-id='{$this->order_id}'><i class='fa fa-commenting'></i> </a>";
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->render();
    }
}
