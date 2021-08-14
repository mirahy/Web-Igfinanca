<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Services\TbLaunchService;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TbClosing.
 *
 * @package namespace App\Entities;
 */
class TbClosing extends Model implements Transformable
{
   use SoftDeletes;
   use TransformableTrait;


     public     $timestamps   = true;
     protected  $table        = 'tb_closing';
     protected  $fillable     = ['id','month','year', 'status'];


     public function Launch(){
        return $this->hasMany(TbLaunch::class, 'id', 'idtb_closing');
    
     }



     
     protected $appends = ['MonthYear', 'InitPeriod', 'FinalPeriod'];

     //retorno "mes/ano"
     public function getMonthYearAttribute()
     {
      return $this->month . '/' . $this->year;
     }

      //retorno inicio periodo
      public function getInitPeriodAttribute()
      {
         $month = $this->month;
         $month = TbLaunchService::number_month($month);
         $year  = $this->year;
         $data = $year.'-'.$month;
         $data = new \DateTime($data);
       return $data->format('Y-m-d');
      }

      //retorno final periodo
      public function getFinalPeriodAttribute()
      {
         $month = $this->month;
         $month = TbLaunchService::number_month($month);
         $year  = $this->year;
         $data = $year.'-'.$month;
         $data = new \DateTime($data);    
       return $data->format('Y-m-t');
      }

}
