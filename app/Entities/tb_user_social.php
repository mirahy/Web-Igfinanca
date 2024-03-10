<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DateTimeInterface;

class tb_user_social extends Model
{
    use SoftDeletes;
    use Notifiable;
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public     $timestamps   = true;
    protected  $table        = 'tb_user_social';
    protected  $fillable     = ['user_id', 'social_network', 'social_id', 'social_email', 'social_avatar'];
    protected  $hidden       = [];
    //Alterando nome do evento 
    protected static $logName                      = 'tb_user_social';
    //vevntos que acionan o log
    protected static $recordEvents                 = ['created', 'updated', 'deleted'];
    //Atributos que sera registrada a alteração
    protected static $logAttributes                = ['user_id', 'social_network', 'social_id', 'social_email', 'social_avatar'];
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
