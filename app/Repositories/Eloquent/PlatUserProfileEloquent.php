<?php
namespace App\Repositories\Eloquent;

use App\Models\PlatUserProfile;
use App\Repositories\Contracts\PlatUserProfileRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class PlatUserProfileEloquent extends BaseRepository implements PlatUserProfileRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        // TODO: Implement model() method.
        return PlatUserProfile::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
