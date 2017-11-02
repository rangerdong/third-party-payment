<?php
namespace App\Admin\Extensions\Tools;

use App\Lib\Status\RechargeOrderStatus;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class RechargeOrderClassify extends AbstractTool
{

    public function script()
    {
        $url = Request::fullUrlWithQuery(['classify' => '_classify_']);
        return <<<script
$('input:radio.classify').change(function() {
  var url = "$url".replace('_classify_', $(this).val());
  $.pjax({container:'#pjax-container', url:url});
})
script;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        // TODO: Implement render() method.
        Admin::script($this->script());
        $options = [
            99 => '全部' ,
            RechargeOrderStatus::PENDING => '未完成',
            RechargeOrderStatus::SUCCESS => '支付成功'
        ];
        return view('admin.tools.classify', compact('options'));
    }
}
