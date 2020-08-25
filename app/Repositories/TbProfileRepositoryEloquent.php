<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\tb_profileRepository;
use App\Entities\TbProfile;
use App\Validators\TbProfileValidator;

/**
 * Class TbProfileRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbProfileRepositoryEloquent extends BaseRepository implements TbProfileRepository
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
        return TbProfile::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbProfileValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
