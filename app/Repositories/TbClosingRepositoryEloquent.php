<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TbClosingRepository;
use App\Entities\TbClosing;
use App\Validators\TbClosingValidator;

/**
 * Class TbClosingRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TbClosingRepositoryEloquent extends BaseRepository implements TbClosingRepository
{
    public function selectBoxList_month(string $month = 'Month_Year', string $chave = 'id'){

        return $this->model->all(['id', 'month', 'year', 'status'])->whereIn('status', [1,2])->pluck($month, $chave);
    }

    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TbClosing::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TbClosingValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
