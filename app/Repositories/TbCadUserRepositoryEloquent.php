<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\tb_cad_userRepository;
use App\Entities\TbCadUser;
use App\Validators\TbCadUserValidator;

/**
 * Class TbCadUserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbCadUserRepositoryEloquent extends BaseRepository implements TbCadUserRepository
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
        return TbCadUser::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbCadUserValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
