<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbCaixaRepository;
use App\Entities\TbCaixa;
use App\Validators\TbCaixaValidator;

/**
 * Class TbCaixaRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbCaixaRepositoryEloquent extends BaseRepository implements TbCaixaRepository
{
    public function selectBoxList(string $descrição = 'name', string $chave = 'id'){

        return $this->model->pluck($descrição, $chave);
    }
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbCaixa::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbCaixaValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
