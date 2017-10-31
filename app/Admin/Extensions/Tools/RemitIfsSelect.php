<?php
namespace App\Admin\Extensions\Tools;

use App\Models\SettlementIf;
use Encore\Admin\Grid\Tools\AbstractTool;

class RemitIfsSelect extends AbstractTool
{

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        // TODO: Implement render() method.
        $ifs = SettlementIf::where('status', 1)->get();
        return view('admin.tools.settleif', compact('ifs'));
    }

    public function __toString()
    {
        return $this->render();
    }
}
