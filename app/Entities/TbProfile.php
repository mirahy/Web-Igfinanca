<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TbProfile.
 *
 * @package namespace App\Entities;
 */
class TbProfile extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public     $timestamps   = true;
     protected  $table        = 'tb_profile';
     protected $fillable = ['id', 'name','description'];

}
