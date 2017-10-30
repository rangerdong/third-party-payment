<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\RemitOrderDetailRepository;
use App\Models\RemitOrderDetail;

/**
 * Class RemiteOrderDetailRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class RemitOrderDetailRepositoryEloquent extends BaseRepository implements RemitOrderDetailRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RemitOrderDetail::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
