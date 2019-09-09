<?php

namespace App\Presenters;

use App\Transformers\TbTypeLaunchTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbTypeLaunchPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbTypeLaunchPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbTypeLaunchTransformer();
    }
}
