<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\WithdrawOrderRepository;
use App\Models\WithdrawOrder;
use App\Validators\WithdrawOrderValidator;

/**
 * Class WithdrawOrderRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class WithdrawOrderRepositoryEloquent extends BaseRepository implements WithdrawOrderRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return WithdrawOrder::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
