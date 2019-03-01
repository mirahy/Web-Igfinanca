<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbBase;

/**
 * Class TbBaseTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbBaseTransformer extends TransformerAbstract
{
    /**
     * Transform the TbBase entity.
     *
     * @param \App\Entities\TbBase $model
     *
     * @return array
     */
    public function transform(TbBase $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
