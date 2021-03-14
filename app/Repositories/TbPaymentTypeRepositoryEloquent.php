<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbPaymentTypeRepository;
use App\Entities\TbPaymentType;
use App\Validators\TbPaymentTypeValidator;

/**
 * Class TbPaymentTypeRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbPaymentTypeRepositoryEloquent extends BaseRepository implements TbPaymentTypeRepository
{
    public function selectBoxList(string $descrição = 'name', string $chave = 'id'){

        return $this->model->pluck($descrição, $chave);
    }
    
    public function model()
    {
        return TbPaymentType::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbPaymentTypeValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
