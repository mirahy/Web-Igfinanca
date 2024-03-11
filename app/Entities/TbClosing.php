<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Services\TbLaunchService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DateTimeInterface;

/**
 * Class TbClosing.
 *
 * @package namespace App\Entities;
 */
class TbClosing extends Model implements Transformable
{
   use SoftDeletes;
   use TransformableTrait;
   use LogsActivity;


   public     $timestamps   = true;
   protected  $table        = 'tb_closing';
   protected  $fillable     = ['id', 'month', 'year', 'status', 'period_valid'];
   //Alterando nome do evento 
   protected static $logName                      = 'TbClosing';
   //vevntos que acionan o log
   protected static $recordEvents                 = ['created', 'updated', 'deleted'];
   //Atributos que sera registrada a alteração
   protected static $logAttributes                = ['id', 'month', 'year', 'status', 'period_valid'];
   //Atributo que sera ignorado a alteração        
   protected static $ignoreChangedAttributes      = [];
   //Registrando log apenas de atributos alterados
   protected static $logOnlyDirty                 = true;
   //impedir registro de log vazio ao alterar atributos não listados no 'logAttributes'
   protected static $submitEmptyLogs              = false;

   //função para descrição do log
   public function getDescriptionForEvent(string $eventName): string
   {
      return "This model has been {$eventName}";
   }

   public function getActivitylogOptions(): LogOptions
   {
      return LogOptions::defaults();
   }


   public function Launch()
   {
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
      $data = $year . '-' . $month;
      $data = new \DateTime($data);
      return $data->format('Y-m-d');
   }

   //retorno final periodo
   public function getFinalPeriodAttribute()
   {
      $month = $this->month;
      $month = TbLaunchService::number_month($month);
      $year  = $this->year;
      $data = $year . '-' . $month;
      $data = new \DateTime($data);
      return $data->format('Y-m-t');
   }

   /**
    * Prepare a date for array / JSON serialization.
    *
    * @param  \DateTimeInterface  $date
    * @return string
    */
   protected function serializeDate(DateTimeInterface $date)
   {
      return $date->format('Y-m-d H:i:s');
   }
}
