<?php

namespace App\Presenters;

use App\Transformers\TbLaunchTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbLaunchPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbLaunchPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbLaunchTransformer();
    }
}
