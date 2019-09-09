<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbTypeLaunch;

/**
 * Class TbTypeLaunchTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbTypeLaunchTransformer extends TransformerAbstract
{
    /**
     * Transform the TbTypeLaunch entity.
     *
     * @param \App\Entities\TbTypeLaunch $model
     *
     * @return array
     */
    public function transform(TbTypeLaunch $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
