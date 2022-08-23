<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

/**
 * Class TbCaixa.
 *
 * @package namespace App\Entities;
 */
class TbCaixa extends Model implements Transformable
{
    use TransformableTrait;
    use LogsActivity;

     public     $timestamps   = true;
     protected  $table        = 'tb_caixa';
     protected  $fillable     = ['id','name','description'];
     //Alterando nome do evento 
    protected static $logName                      = 'TbCaixa';
    //vevntos que acionan o log
    protected static $recordEvents                 = ['created', 'updated', 'deleted'];
    //Atributos que sera registrada a alteração
    protected static $logAttributes                = ['id','name','description'];
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

}