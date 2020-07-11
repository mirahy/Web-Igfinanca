<?php

namespace App\Presenters;

use App\Transformers\TbTypeLauncTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbTypeLauncPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbTypeLauncPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbTypeLauncTransformer();
    }
}
