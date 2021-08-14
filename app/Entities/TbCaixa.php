<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbCaixa.
 *
 * @package namespace App\Entities;
 */
class TbCaixa extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

     public     $timestamps   = true;
     protected  $table        = 'tb_caixa';
     protected  $fillable     = ['id','name','description'];

}
