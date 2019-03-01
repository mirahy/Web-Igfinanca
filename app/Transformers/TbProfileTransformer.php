<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbProfile;

/**
 * Class TbProfileTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbProfileTransformer extends TransformerAbstract
{
    /**
     * Transform the TbProfile entity.
     *
     * @param \App\Entities\TbProfile $model
     *
     * @return array
     */
    public function transform(TbProfile $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
