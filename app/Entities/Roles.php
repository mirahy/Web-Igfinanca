<?php

namespace App\Entities;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Roles extends Role
{

    use LogsActivity;

    protected $fillable = ['name', 'guard_name'];

    //eventos que acionan o log
    protected static $recordEvents  = ['created', 'updated', 'deleted'];

    // Função para registara log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")
            ->useLogName('Role')
            ->logOnly(['name', 'guard_name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }


    /**
     * The Permissions that belong to the user.
     */
    public function permisionsToany(): BelongsToMany
    {
        return $this->belongsToMany(RoleHasPermissions::class);
    }


    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y, H:i:s');
    }
}
