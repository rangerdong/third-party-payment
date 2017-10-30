<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\PlatUserBankRepository;
use App\Models\PlatUserBank;

/**
 * Class PlatUserBankRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class PlatUserBankRepositoryEloquent extends BaseRepository implements PlatUserBankRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlatUserBank::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
