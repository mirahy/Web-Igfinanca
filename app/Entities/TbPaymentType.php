<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class TbPaymentType extends Model implements Transformable
{
    use TransformableTrait;

  
    public     $timestamps   = true;
    protected  $table        = 'tb_payment_type';
    protected  $fillable     = ['id','name','descripion', 'created_at', 'updated_at'];
    

}
