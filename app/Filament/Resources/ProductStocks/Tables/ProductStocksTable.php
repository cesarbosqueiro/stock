<?php

namespace App\Filament\Resources\ProductStocks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.code')
                    ->label('Codigo')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedHashtag)
                    ->iconColor('gray')
                    ->copyable()
                    ->copyMessage('Codigo copiado!')
                    ->toggleable(),

                TextColumn::make('product.name')
                    ->label('Produto')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCube)
                    ->iconColor('primary')
                    ->weight('semibold')
                    ->wrap(),

                TextColumn::make('product.category.name')
                    ->label('Categoria')
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::OutlinedTag)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->label('Quantidade')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->sortable()
                    ->icon(Heroicon::OutlinedScale)
                    ->iconColor('success')
                    ->weight('bold')
                    ->color(fn ($record): string =>
                    $record->quantity < $record->product->minimum_stock ? 'danger' : 'success'
                    ),

                TextColumn::make('product.unit_measurement.abbreviation')
                    ->label('Unidade')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('product.minimum_stock')
                    ->label('Estoque Min.')
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->sortable()
                    ->icon(Heroicon::OutlinedExclamationTriangle)
                    ->iconColor('warning')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record): string =>
                    $record->quantity < $record->product->minimum_stock ? 'Baixo' : 'Normal'
                    )
                    ->color(fn ($record): string =>
                    $record->quantity < $record->product->minimum_stock ? 'danger' : 'success'
                    )
                    ->icon(fn ($record): Heroicon =>
                    $record->quantity < $record->product->minimum_stock
                        ? Heroicon::OutlinedExclamationCircle
                        : Heroicon::OutlinedCheckCircle
                    ),

                TextColumn::make('updated_at')
                    ->label('Ultima Atualizacao')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon(Heroicon::OutlinedClock)
                    ->since()
                    ->description(fn ($record): string => $record->updated_at->format('d/m/Y H:i'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('product.category_id')
                    ->label('Categoria')
                    ->relationship('product.category', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                TernaryFilter::make('low_stock')
                    ->label('Estoque Baixo')
                    ->placeholder('Todos os produtos')
                    ->trueLabel('Apenas estoque baixo')
                    ->falseLabel('Apenas estoque normal')
                    ->queries(
                        true: fn ($query) => $query->whereHas('product', function ($q) {
                            $q->whereRaw('product_stocks.quantity < products.minimum_stock');
                        }),
                        false: fn ($query) => $query->whereHas('product', function ($q) {
                            $q->whereRaw('product_stocks.quantity >= products.minimum_stock');
                        }),
                    ),

                SelectFilter::make('product.unit_measurement_id')
                    ->label('Unidade de Medida')
                    ->relationship('product.unitMeasurement', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
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
            ->defaultSort('product.name', 'asc')
            ->striped()
            ->emptyStateHeading('Nenhum estoque cadastrado')
            ->emptyStateDescription('Os estoques sao criados automaticamente ao registrar movimentacoes.')
            ->emptyStateIcon(Heroicon::OutlinedInboxStack);
    }
}
