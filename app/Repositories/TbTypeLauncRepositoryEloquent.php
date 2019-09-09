<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbTypeLauncRepository;
use App\Entities\TbTypeLaunc;
use App\Validators\TbTypeLauncValidator;

/**
 * Class TbTypeLauncRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbTypeLauncRepositoryEloquent extends BaseRepository implements TbTypeLauncRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbTypeLaunc::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbTypeLauncValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
