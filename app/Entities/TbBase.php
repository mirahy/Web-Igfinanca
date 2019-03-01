<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TbBase.
 *
 * @package namespace App\Entities;
 */
class TbBase extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public     $timestamps   = true;
     protected  $table        = 'tb_base';
     protected $fillable = ['name','descripion'];

}
