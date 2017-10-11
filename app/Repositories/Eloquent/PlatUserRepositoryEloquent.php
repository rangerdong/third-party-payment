<?php

namespace App\Repositories\Eloquent;

use App\Models\PlatUser;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\PlatUserRepository;

/**
 * Class ModelsPlatUserRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class PlatUserRepositoryEloquent extends BaseRepository implements PlatUserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PlatUser::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
