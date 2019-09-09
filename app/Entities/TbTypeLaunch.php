<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class TbTypeLaunch extends Model implements Transformable
{
    use TransformableTrait;

    
    public     $timestamps   = true;
    protected  $table        = 'tb_type_launch';
    protected  $fillable     = ['id','name','value','descripion', 'created_at', 'updated_at'];

}
