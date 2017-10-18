<?php
namespace App\Repositories\Eloquent;

use App\Models\RechargeOrder;
use App\Repositories\Contracts\RechargeOrderRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class RechargeOrderRepositoryEloquent extends BaseRepository implements RechargeOrderRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return RechargeOrder::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function store($data)
    {
        $this->model->create($data);
    }

}
