<?php

namespace App\Presenters;

use App\Transformers\TbPaymentTypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TbPaymentTypePresenter.
 *
 * @package namespace App\Presenters;
 */
class TbPaymentTypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TbPaymentTypeTransformer();
    }
}
