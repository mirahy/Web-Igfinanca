<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbCadUser;

/**
 * Class TbCadUserTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbCadUserTransformer extends TransformerAbstract
{
    /**
     * Transform the TbCadUser entity.
     *
     * @param \App\Entities\TbCadUser $model
     *
     * @return array
     */
    public function transform(TbCadUser $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
