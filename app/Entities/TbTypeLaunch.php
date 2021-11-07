<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Activitylog\Traits\LogsActivity;


class TbTypeLaunch extends Model implements Transformable
{
    use TransformableTrait;
    use LogsActivity;

    
    public     $timestamps   = true;
    protected  $table        = 'tb_type_launch';
    protected  $fillable     = ['id','name','value','descripion', 'created_at', 'updated_at'];
    //Alterando nome do evento 
    protected static $logName                      = 'TbTypeLaunch';
    //vevntos que acionan o log
    protected static $recordEvents                 = ['created', 'updated', 'deleted'];
    //Atributos que sera registrada a alteração
    protected static $logAttributes                = ['id','name','value','descripion'];
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
}
