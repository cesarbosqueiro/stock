<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Codigo')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedHashtag)
                    ->iconColor('gray')
                    ->copyable()
                    ->copyMessage('Codigo copiado!')
                    ->weight('semibold'),

                TextColumn::make('name')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCube)
                    ->iconColor('primary')
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::OutlinedTag),

                TextColumn::make('unitMeasurement.abbreviation')
                    ->label('Unidade')
                    ->badge()
                    ->color('gray')
                    ->icon(Heroicon::OutlinedScale),

                TextColumn::make('stock.quantity')
                    ->label('Estoque Atual')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->sortable()
                    ->icon(Heroicon::OutlinedCubeTransparent)
                    ->default(0)
                    ->color(function ($record): string {
                        $stock = $record->stock?->quantity ?? 0;
                        if ($stock == 0) {
                            return 'danger';
                        }
                        if ($stock < ($record->minimum_stock ?? 0)) {
                            return 'warning';
                        }

                        return 'success';
                    })
                    ->weight('bold'),

                TextColumn::make('minimum_stock')
                    ->label('Estoque Min.')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->sortable()
                    ->icon(Heroicon::OutlinedExclamationTriangle)
                    ->iconColor('warning')
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon(Heroicon::OutlinedClock)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since()
                    ->description(fn ($record): string => $record->updated_at->format('d/m/Y H:i'))
                    ->icon(Heroicon::OutlinedClock)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                SelectFilter::make('unit_measurement_id')
                    ->label('Unidade de Medida')
                    ->relationship('unitMeasurement', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('is_active')
                    ->label('Status do Produto')
                    ->placeholder('Todos os produtos')
                    ->trueLabel('Apenas ativos')
                    ->falseLabel('Apenas inativos'),

                TernaryFilter::make('low_stock')
                    ->label('Estoque')
                    ->placeholder('Todos')
                    ->trueLabel('Apenas estoque baixo')
                    ->falseLabel('Apenas estoque normal')
                    ->queries(
                        true: function ($query): void {
                            $query->whereHas('stock', function ($q): void {
                                $q->whereRaw('product_stocks.quantity < products.minimum_stock')
                                    ->orWhereRaw('product_stocks.quantity = 0');
                            });
                        },
                        false: function ($query): void {
                            $query->whereHas('stock', function ($q): void {
                                $q->whereRaw('product_stocks.quantity >= products.minimum_stock');
                            });
                        },
                    ),
            ])
            ->recordActions([
                ViewAction::make()
                    ->icon(Heroicon::OutlinedEye),
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
            ->defaultSort('name', 'asc')
            ->striped()
            ->emptyStateHeading('Nenhum produto cadastrado')
            ->emptyStateDescription('Comece criando um novo produto para gerenciar seu estoque.')
            ->emptyStateIcon(Heroicon::OutlinedCube);
    }
}
