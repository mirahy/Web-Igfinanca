<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TbClosing.
 *
 * @package namespace App\Entities;
 */
class TbClosing extends Model implements Transformable
{
    use TransformableTrait;

     public     $timestamps   = true;
     protected  $table        = 'tb_closing';
     protected  $fillable     = ['id','month','year', 'status'];


     public function Launch(){
        return $this->belongsTo(TbLaunch::class, 'id', 'idtb_closing');
    
     }


     //retorno "mes/ano"
     protected $appends = ['MonthYear'];
     public function getMonthYearAttribute()
     {
      return $this->month . '/' . $this->year;
     }

}
