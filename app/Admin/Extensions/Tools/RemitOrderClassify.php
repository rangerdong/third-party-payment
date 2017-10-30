<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class RemitOrderClassify extends  AbstractTool
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
            0 => '全部' ,
            1 => '提现',
            2 => '代付'
        ];
        return view('admin.tools.classify', compact('options'));
    }
}
