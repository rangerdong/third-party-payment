<?php

namespace App\Repositories\Eloquent;

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
}
