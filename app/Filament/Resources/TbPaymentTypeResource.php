<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TbPaymentTypeResource\Pages;
use App\Filament\Resources\TbPaymentTypeResource\RelationManagers;
use App\Entities\TbPaymentType;
use App\Filament\Support\LookupReplication;
use App\Repositories\TbPaymentTypeRepository;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TbPaymentTypeResource extends Resource
{
    protected static ?string $model = TbPaymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Configurações Financeiras';

    protected static ?string $navigationLabel = 'Tipos de Pagamento';

    protected static ?string $modelLabel = 'Tipo de Pagamento';

    protected static ?string $pluralModelLabel = 'Tipos de Pagamento';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Tipo de Pagamento')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            $records->each(fn (Model $record) => LookupReplication::beforeDelete($record, TbPaymentTypeRepository::class));
                        }),
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
            'index' => Pages\ListTbPaymentTypes::route('/'),
            'create' => Pages\CreateTbPaymentType::route('/create'),
            'edit' => Pages\EditTbPaymentType::route('/{record}/edit'),
        ];
    }
}
