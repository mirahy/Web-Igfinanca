<?php

namespace App\Presenters;

use App\Transformers\TbCaixaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbCaixaPresenter.
 *
 * @package namespace App\Presenters;
 */
class TbCaixaPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbCaixaTransformer();
    }
}
