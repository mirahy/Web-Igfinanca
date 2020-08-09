<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class TbOperation extends Model implements Transformable
{
    use TransformableTrait;

   
     public     $timestamps   = true;
     protected  $table        = 'tb_operation';
     protected  $fillable     = ['id','name','description'];
     

    

}