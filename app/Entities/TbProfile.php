<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DateTimeInterface;

/**
 * Class TbProfile.
 *
 * @package namespace App\Entities;
 */
class TbProfile extends Model implements Transformable
{
    use SoftDeletes;
    use TransformableTrait;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public     $timestamps   = true;
    protected  $table        = 'tb_profile';
    protected $fillable = ['id', 'name', 'description'];
    //Alterando nome do evento 
    protected static $logName                      = 'TbProfile';
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
