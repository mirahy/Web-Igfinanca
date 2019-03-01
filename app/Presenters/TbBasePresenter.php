<?php

namespace App\Presenters;

use App\Transformers\TbBaseTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbBasePresenter.
 *
 * @package namespace App\Presenters;
 */
class TbBasePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbBaseTransformer();
    }
}
