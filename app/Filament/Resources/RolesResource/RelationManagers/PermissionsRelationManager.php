<?php

namespace App\Filament\Resources\RolesResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Reproduz, via Filament, a atribuição de permissões a uma role que hoje é
 * feita no fluxo legado (resources/views/user/roles.blade.php +
 * RoleService::store/update, via $role->syncPermissions()). Usa
 * attach/detach sobre permissões já existentes — não cria/edita/apaga a
 * Permission em si (isso é papel do PermissionsResource).
 *
 * Limitação conhecida: RoleService também propaga a role para as bases das
 * filiais via App\Services\ReplicaDbService, o que este RelationManager
 * ainda não reproduz. Ver plano de reconciliação antes de desativar a tela
 * legada de roles.
 */
class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $title = 'Permissões';

    protected static ?string $modelLabel = 'Permissão';

    protected static ?string $pluralModelLabel = 'Permissões';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('guard_name')
                    ->label('Guard')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Atribuir Permissão')
                    ->recordSelectSearchColumns(['name'])
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Remover selecionadas'),
                ]),
            ]);
    }
}
