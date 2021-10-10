<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class TbLaunch extends Model implements Transformable
{
    use TransformableTrait;
    

   
     public     $timestamps   = true;
     protected  $table        = 'tb_launch';
     protected  $fillable     = ['id','id_user', 'description', 'value','operation_date','idtb_operation','status','idtb_type_launch', 'idtb_payment_type', 'idtb_caixa','idtb_base', 'idtb_closing', 'status', 'created_at', 'updated_at'];
     

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