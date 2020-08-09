<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbCaixa;

/**
 * Class TbCaixaTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbCaixaTransformer extends TransformerAbstract
{
    /**
     * Transform the TbCaixa entity.
     *
     * @param \App\Entities\TbCaixa $model
     *
     * @return array
     */
    public function transform(TbCaixa $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
