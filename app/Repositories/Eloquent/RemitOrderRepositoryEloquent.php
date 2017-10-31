<?php

namespace App\Repositories\Eloquent;

use App\Lib\Status\RemitStatus;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RemitOrderRepository;
use App\Models\RemitOrder;

/**
 * Class RemiteOrderRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class RemitOrderRepositoryEloquent extends BaseRepository implements RemitOrderRepository
{
    protected $assetAccount;

    public function __construct(\Illuminate\Container\Container $app, AssetCountEloquent $assetCountEloquent)
    {
        parent::__construct($app);
        $this->assetAccount = $assetCountEloquent;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RemitOrder::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function auditPass($id)
    {
        $order = $this->model->find($id);
        $batch_no = $order->batch_no;
        $this->model->where('batch_no', $batch_no)->update([
            'status' => RemitStatus::PENDING_REMIT
        ]);
        return true;
    }

    public function auditRefuse($id, $reason='银行卡信息不符')
    {
        $order = $this->model->find($id);
        $order->ad_remark = $reason;
        $order->status = RemitStatus::REFUSE_REVIEW;
        $order->save();
        return true;
    }

    //已打款
    public function remitted($id, $is_handle=false)
    {
        $order = $this->model->find($id);
        $order->status = RemitStatus::REMIT_SUCCESS;
        if ($is_handle) $order->disposal = 1;
        $this->assetAccount->operateAsset($order->uid, 'withdraw', $order->ac_money);
        $order->save();
        return true;
    }

    public function remit($id)
    {
        $order = $this->model->find($id);
        $order->status = RemitStatus::REMITTING;
        $order->save();
        return true;
    }
}
