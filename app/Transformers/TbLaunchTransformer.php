<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\TbLaunch;

/**
 * Class TbLaunchTransformer.
 *
 * @package namespace App\Transformers;
 */
class TbLaunchTransformer extends TransformerAbstract
{
    /**
     * Transform the TbLaunch entity.
     *
     * @param \App\Entities\TbLaunch $model
     *
     * @return array
     */
    public function transform(TbLaunch $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
