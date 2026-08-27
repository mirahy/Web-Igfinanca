<?php

namespace App\Filament\Resources;

use App\Entities\TbLaunch;
use App\Filament\Resources\TbLaunchResource\Pages;
use App\Filament\Support\ResolvesFilialConnection;
use App\Http\Controllers\ConnectDbController;
use App\Services\TbLaunchService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource único para Entrada e Saída (ao contrário das duas telas
 * separadas do sistema legado) — o Select de Operação é obrigatório e sem
 * valor padrão, forçando quem lança a escolher explicitamente a cada vez.
 *
 * Os Selects de Caixa/Tipo de Pagamento/Operação/Tipo de Lançamento listam
 * as cópias replicadas (id_mtz) da filial ativa (Fase E.0.5), não as da
 * matriz — a conexão já está na filial quando o form é montado, então
 * relationship() resolve isso sozinho, sem código extra.
 *
 * O CRUD delega para App\Services\TbLaunchService::store()/update()/delete()
 * (já existente, já testado em produção) em vez de reimplementar a
 * validação de negócio (bloqueio de período fechado, replicação
 * matriz<->filial via id_filial/id_mtz).
 */
class TbLaunchResource extends Resource
{
    protected static ?string $model = TbLaunch::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Configurações Financeiras';

    protected static ?string $navigationLabel = 'Lançamentos';

    protected static ?string $modelLabel = 'Lançamento';

    protected static ?string $pluralModelLabel = 'Lançamentos';

    protected static ?bool $canApproveCache = null;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do Lançamento')
                    ->schema([
                        Forms\Components\Select::make('id_user')
                            ->label('Lançado por')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('idtb_operation')
                            ->label('Operação')
                            ->relationship('operation', 'name')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('idtb_type_launch')
                            ->label('Tipo de Lançamento')
                            ->relationship('type_launch', 'name')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('idtb_payment_type')
                            ->label('Tipo de Pagamento')
                            ->relationship('payment_type', 'name')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('idtb_caixa')
                            ->label('Caixa')
                            ->relationship('caixa', 'name')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('idtb_closing')
                            ->label('Mês de Referência')
                            ->relationship(
                                name: 'closing',
                                titleAttribute: 'id',
                                modifyQueryUsing: fn (Builder $query) => $query->whereIn('status', [1, 2]),
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->MonthYear)
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('operation_date')
                            ->label('Data da Coleta')
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('value')
                            ->label('Valor')
                            ->numeric()
                            ->prefix('R$')
                            ->required(),
                        Forms\Components\Textarea::make('description')
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
                Tables\Columns\TextColumn::make('operation_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('operation.name')
                    ->label('Operação')
                    ->badge()
                    ->color(fn (TbLaunch $record) => $record->idtb_operation == 1 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('value')
                    ->label('Valor')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_launch.name')
                    ->label('Tipo de Lançamento'),
                Tables\Columns\TextColumn::make('payment_type.name')
                    ->label('Tipo de Pagamento'),
                Tables\Columns\TextColumn::make('caixa.name')
                    ->label('Caixa'),
                Tables\Columns\TextColumn::make('closing.MonthYear')
                    ->label('Mês de Referência'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Lançado por')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Situação')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Aprovado',
                        2 => 'Reprovado',
                        default => 'Pendente',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'success',
                        2 => 'danger',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('operation_date', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('aprovar')
                    ->label('Aprovar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (TbLaunch $record): bool => $record->status != 1 && static::userCanApprove())
                    ->action(fn (TbLaunch $record) => static::approve($record, 1)),
                Tables\Actions\Action::make('reprovar')
                    ->label('Reprovar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (TbLaunch $record): bool => $record->status != 2 && static::userCanApprove())
                    ->action(fn (TbLaunch $record) => static::approve($record, 2)),
                Tables\Actions\DeleteAction::make()
                    ->action(function (TbLaunch $record) {
                        ResolvesFilialConnection::assertConnected();

                        app(TbLaunchService::class)->delete($record->id);

                        ResolvesFilialConnection::assertConnected();

                        Notification::make()
                            ->title('Lançamento excluído')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }

    /**
     * A permission launch-approves só existe na matriz — Roles/Permissions
     * ficam fora da replicação (Fase E.0.5) de propósito, pra manter o
     * controle de acesso centralizado. Checar contra a filial ativa sempre
     * daria "false" (tabelas existem lá, mas vazias).
     */
    protected static function userCanApprove(): bool
    {
        if (static::$canApproveCache === null) {
            app(ConnectDbController::class)->connectMatriz();
            static::$canApproveCache = auth()->user()?->can('launch-approves') ?? false;
            ResolvesFilialConnection::assertConnected();
        }

        return static::$canApproveCache;
    }

    protected static function approve(TbLaunch $record, int $status): void
    {
        ResolvesFilialConnection::assertConnected();

        $result = app(TbLaunchService::class)->aprov_id([
            'id' => $record->id,
            'status' => $status,
        ]);

        ResolvesFilialConnection::assertConnected();

        if (! $result['success']) {
            Notification::make()
                ->title('Não foi possível atualizar a aprovação')
                ->body(is_array($result['messages']) ? implode(' ', $result['messages']) : $result['messages'])
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title($status === 1 ? 'Lançamento aprovado' : 'Lançamento reprovado')
            ->success()
            ->send();
    }

    public static function getEloquentQuery(): Builder
    {
        ResolvesFilialConnection::assertConnected();

        return parent::getEloquentQuery();
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
            'index' => Pages\ListTbLaunches::route('/'),
            'create' => Pages\CreateTbLaunch::route('/create'),
            'edit' => Pages\EditTbLaunch::route('/{record}/edit'),
        ];
    }
}
