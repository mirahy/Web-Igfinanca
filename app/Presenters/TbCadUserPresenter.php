<?php

namespace App\Presenters;

use App\Transformers\TbCadUserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbCadUserPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbCadUserPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbCadUserTransformer();
    }
}
