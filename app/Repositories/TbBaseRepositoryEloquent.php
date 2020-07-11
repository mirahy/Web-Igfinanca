<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\tb_baseRepository;
use App\Entities\TbBase;
use App\Validators\TbBaseValidator;

/**
 * Class TbBaseRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbBaseRepositoryEloquent extends BaseRepository implements TbBaseRepository
{
    public function selectBoxList(string $descrição = 'name', string $chave = 'idtb_base'){

        return $this->model->pluck($descrição, $chave);
    }
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbBase::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbBaseValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
