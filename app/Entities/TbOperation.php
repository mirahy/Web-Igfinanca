<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DateTimeInterface;


class TbOperation extends Model implements Transformable
{
    use TransformableTrait;
    use LogsActivity;


    public     $timestamps   = true;
    protected  $table        = 'tb_operation';
    protected  $fillable     = ['id', 'name', 'description'];
    //Alterando nome do evento 
    protected static $logName                      = 'TbOperation';
    //vevntos que acionan o log
    protected static $recordEvents                 = ['created', 'updated', 'deleted'];
    //Atributos que sera registrada a alteração
    protected static $logAttributes                = ['id', 'name', 'description'];
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

    // /**
    //  * Prepare a date for array / JSON serialization.
    //  *
    //  * @param  \DateTimeInterface  $date
    //  * @return string
    //  */
    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }
}
