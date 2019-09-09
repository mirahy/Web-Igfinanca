<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbTypeLaunchRepository;
use App\Entities\TbTypeLaunch;
use App\Validators\TbTypeLaunchValidator;

/**
 * Class TbTypeLaunchRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbTypeLaunchRepositoryEloquent extends BaseRepository implements TbTypeLaunchRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbTypeLaunch::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbTypeLaunchValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
