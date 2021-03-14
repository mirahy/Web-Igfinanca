<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbPaymentType;

/**
 * Class TbPaymentTypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbPaymentTypeTransformer extends TransformerAbstract
{
    /**
     * Transform the TbPaymentType entity.
     *
     * @param \App\Entities\TbPaymentType $model
     *
     * @return array
     */
    public function transform(TbPaymentType $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
