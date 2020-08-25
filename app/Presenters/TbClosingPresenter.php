<?php

namespace App\Presenters;

use App\Transformers\TbClosingTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbClosingPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbClosingPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbClosingTransformer();
    }
}
