<?php
namespace App\Admin\Extensions\Actions;

use Encore\Admin\Admin;

class RechargeResupply
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        $url = route('api.order.recharge.resupply');
        $script = <<<script
$('a.grid-resupply').on('click', function () {
    var id = $(this).data('id');
    console.log(id);
    layer.load(2);
    $.ajax({
        type: 'post',
        url: '{$url}',
        data: {
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
        return $script;
    }

    public function render()
    {
        Admin::script($this->script());
        return "<a class='grid-resupply' data-toggle='tooltip' data-placement='left' title='补单' href='javascript:void(0);' data-id='{$this->id}'><i class='fa fa-comments-o'></i></a>";
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->render();
    }
}
