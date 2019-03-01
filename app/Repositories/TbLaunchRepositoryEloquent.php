<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\tb_launchRepository;
use App\Entities\TbLaunch;
use App\Validators\TbLaunchValidator;

/**
 * Class TbLaunchRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbLaunchRepositoryEloquent extends BaseRepository implements TbLaunchRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbLaunch::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbLaunchValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
