<?php

namespace App\Presenters;

use App\Transformers\TbProfileTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbProfilePresenter.
 *
 * @package namespace App\Presenters;
 */
class TbProfilePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbProfileTransformer();
    }
}
