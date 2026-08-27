<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbOperationRepository;
use App\Entities\TbOperation;
use App\Validators\TbOperationValidator;

/**
 * Class TbOperationRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbOperationRepositoryEloquent extends BaseRepository implements TbOperationRepository
{
    public function selectBoxList(string $descrição = 'name', string $chave = 'id')
    {
        return $this->model->pluck($descrição, $chave);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbOperation::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return TbOperationValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
