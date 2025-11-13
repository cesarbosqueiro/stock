<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movement_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon(Heroicon::OutlinedCalendar)
                    ->iconColor('primary'),

                TextColumn::make('product.name')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCube)
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'entry' => 'Entrada',
                        'exit' => 'Saida',
                        'adjustment' => 'Ajuste',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'entry' => 'success',
                        'exit' => 'danger',
                        'adjustment' => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): Heroicon => match ($state) {
                        'entry' => Heroicon::OutlinedArrowDownTray,
                        'exit' => Heroicon::OutlinedArrowUpTray,
                        'adjustment' => Heroicon::OutlinedAdjustmentsHorizontal,
                        default => Heroicon::OutlinedQuestionMarkCircle,
                    }),

                TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->sortable()
                    ->icon(Heroicon::OutlinedScale)
                    ->iconColor('info'),

                TextColumn::make('unit_price')
                    ->label('Preco Unitario')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_value')
                    ->label('Valor Total')
                    ->money('BRL', locale: 'pt_BR')
                    ->sortable()
                    ->icon(Heroicon::OutlinedCurrencyDollar)
                    ->iconColor('success')
                    ->weight('bold'),

                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedUser)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Registrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo de Movimentacao')
                    ->options([
                        'entry' => 'Entrada',
                        'exit' => 'Saida',
                        'adjustment' => 'Ajuste',
                    ])
                    ->multiple(),

                SelectFilter::make('product_id')
                    ->label('Produto')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon(Heroicon::OutlinedPencil),
                DeleteAction::make()
                    ->icon(Heroicon::OutlinedTrash),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('movement_date', 'desc')
            ->striped()
            ->emptyStateHeading('Nenhuma movimentacao registrada')
            ->emptyStateDescription('Comece criando uma nova movimentacao de estoque.')
            ->emptyStateIcon(Heroicon::OutlinedInboxStack);
    }
}
