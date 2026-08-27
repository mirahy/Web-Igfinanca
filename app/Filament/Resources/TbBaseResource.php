<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TbBaseResource\Pages;
use App\Filament\Resources\TbBaseResource\RelationManagers;
use App\Entities\TbBase;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbBaseRepository;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TbBaseResource extends Resource
{
    protected static ?string $model = TbBase::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Estrutura Organizacional';

    protected static ?string $navigationLabel = 'Bases';

    protected static ?string $modelLabel = 'Base';

    protected static ?string $pluralModelLabel = 'Bases';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados da Base')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('sigla')
                            ->label('Sigla')
                            ->required(),
                        Forms\Components\Textarea::make('descripion')
                            ->label('Descrição')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sigla')
                    ->label('Sigla')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Excluído em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            $records->each(fn (Model $record) => LookupReplication::beforeDelete($record, TbBaseRepository::class));
                        }),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTbBases::route('/'),
            'create' => Pages\CreateTbBase::route('/create'),
            'edit' => Pages\EditTbBase::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
