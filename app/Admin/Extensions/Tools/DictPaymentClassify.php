<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class DictPaymentClassify extends AbstractTool
{

    protected function script()
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
            0 => '支付通道' ,
            1 => '网银通道',
            2 => '结算通道'
        ];
        return view('admin.tools.payment-classify', compact('options'));
    }
}
