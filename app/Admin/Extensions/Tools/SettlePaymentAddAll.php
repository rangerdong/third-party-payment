<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class SettlePaymentAddAll extends AbstractTool
{
    protected function script()
    {
        $url = route('api.payment.settle.addall');
        $token = csrf_token();
        return <<<eot
$("button.payment-add-all").on('click', function() {
$(this).attr('disabled', true);
$.ajax({
            method: 'post',
            url: '{$url}',
            data: {
                _token:'{$token}'
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
//        swal({
//  title: '添加中!',
//  text: 'I will close in 5 seconds.',
//  timer: 5000,
//  onOpen: function () {
//    
//    swal.enableLoading()
//  }
//})
        
});
eot;

    }


    /**
     * {@inheritdoc}
     */
    public function render()
    {
        Admin::script($this->script());
        // TODO: Implement render() method.
        return <<<eot
<button class="btn btn-success payment-add-all">添加全部</button>
eot;
    }
}
