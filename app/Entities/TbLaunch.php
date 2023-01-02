<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class TbLaunch extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;
    use LogsActivity;
    

   
     public     $timestamps   = true;
     protected  $table        = 'tb_launch';
     protected  $fillable     = ['id','id_user','id_filial','id_mtz', 'description', 'value','operation_date','idtb_operation',
                                 'idtb_type_launch', 'idtb_payment_type', 'idtb_caixa','idtb_base', 'idtb_closing', 'status',
                                 'created_at', 'updated_at'];
      //eventos que acionan o log
      protected static $recordEvents                 = ['created', 'updated', 'deleted'];
      
      // Função para registara log
      public function getActivitylogOptions(): LogOptions
      {
         return LogOptions::defaults()
         ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}")
         ->useLogName('TbLaunch')
         ->logOnly(['id','user.name','id_mtz', 'description', 'value','operation_date',
                     'operation.name', 'type_launch.name', 'payment_type.name', 'caixa.name',
                     'base.name', 'closing.MonthYear', 'status'])
         ->dontLogIfAttributesChangedOnly(['password', 'rememberToken', 'token_access', 'updated_at'])
         ->logOnlyDirty()
         ->dontSubmitEmptyLogs();

      }

     public function user(){
       return $this->belongsTo(TbCadUser::class, 'id_user', 'id');

     }

     public function type_launch(){
       return $this->belongsTo(TbTypeLaunch::class, 'idtb_type_launch', 'id');
        
    }

    public function operation(){
       return $this->belongsTo(TbOperation::class, 'idtb_operation', 'id');
   
    }

    public function base(){
       return $this->belongsTo(TbBase::class, 'idtb_base', 'id');
   
    }

    public function caixa(){
      return $this->belongsTo(TbCaixa::class, 'idtb_caixa', 'id');
  
   }

   public function closing(){
      return $this->belongsTo(TbClosing::class, 'idtb_closing', 'id');
  
   }

   public function payment_type(){
      return $this->belongsTo(TbPaymentType::class, 'idtb_payment_type', 'id');
  
   }

   //data formatada d-m-Y
   protected $appends = ['dataOperation'];
   public function getDataOperationAttribute()
   {
       return date('d-m-Y', strtotime($this->attributes['operation_date']));
   }

}