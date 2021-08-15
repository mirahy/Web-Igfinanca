<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TbCaixa.
 *
 * @package namespace App\Entities;
 */
class TbCaixa extends Model implements Transformable
{
    use TransformableTrait;

     public     $timestamps   = true;
     protected  $table        = 'tb_caixa';
     protected  $fillable     = ['id','name','description'];

}