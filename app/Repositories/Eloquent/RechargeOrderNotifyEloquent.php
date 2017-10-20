<?php
namespace App\Repositories\Eloquent;

use App\Models\RechargeOrder;
use App\Models\RechargeOrderNotify;
use App\Repositories\Contracts\RechargeOrderNotifyRepository;
use Carbon\Carbon;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class RechargeOrderNotifyEloquent extends BaseRepository implements RechargeOrderNotifyRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return RechargeOrderNotify::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function createByOrder(RechargeOrder $rechargeOrder, $params):RechargeOrderNotify
    {
        $notify = $this->model->where('order_id', $rechargeOrder->id)->first();
        if ( ! $notify) {
            $this->model->order_id = $rechargeOrder->id;
            $this->model->notify_time = 0;
            $this->model->notify_url = $rechargeOrder->order_data['callback_url'];
            $this->model->notify_body = http_build_query($params);
            $this->model->status = 0;
            $this->model->save();
            return $this->model;
        }
        return $notify;
    }
}
