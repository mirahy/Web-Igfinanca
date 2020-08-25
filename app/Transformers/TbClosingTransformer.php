<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbClosing;

/**
 * Class TbClosingTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbClosingTransformer extends TransformerAbstract
{
    /**
     * Transform the TbClosing entity.
     *
     * @param \App\Entities\TbClosing $model
     *
     * @return array
     */
    public function transform(TbClosing $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
