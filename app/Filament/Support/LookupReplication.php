<?php

namespace App\Filament\Support;

use App\Services\ReplicaDbService;
use Illuminate\Database\Eloquent\Model;

/**
 * Replicação matriz→filiais para os Resources "lookup" do Filament
 * (TbPaymentType, TbCaixa, TbTypeLaunch, TbBase, TbOperation): gravam
 * primeiro na matriz (comportamento padrão do painel) e, depois, replicam
 * a mudança pra todas as filiais via App\Services\ReplicaDbService,
 * rastreando o registro correspondente em cada filial pela coluna id_mtz
 * (não pela chave primária local, que é um autoincremento independente
 * por banco).
 */
class LookupReplication
{
    public static function afterCreate(Model $record, array $data, string $repositoryClass): void
    {
        app(ReplicaDbService::class)->createWithMtzTracking($data, app($repositoryClass), $record->id);
    }

    public static function afterUpdate(Model $record, array $data, string $repositoryClass): void
    {
        app(ReplicaDbService::class)->updateWithMtzTracking($data, $record->id, app($repositoryClass));
    }

    public static function beforeDelete(Model $record, string $repositoryClass): void
    {
        app(ReplicaDbService::class)->deleteWithMtzTracking($record->id, app($repositoryClass));
    }
}
