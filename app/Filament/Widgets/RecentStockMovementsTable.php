<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentStockMovementsTable extends BaseWidget
{
    protected static ?string $heading = 'Últimas movimentações';

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder|Relation|null
    {
        return StockMovement::query()->with(['product', 'user'])->latest('movement_date');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('movement_date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable(),
            TextColumn::make('type')
                ->label('Tipo')
                ->formatStateUsing(function ($state) {
                    return match ($state) {
                        StockMovement::TYPE_ENTRY => 'Entrada',
                        StockMovement::TYPE_EXIT => 'Saída',
                        StockMovement::TYPE_ADJUSTMENT => 'Ajuste',
                        default => $state,
                    };
                })
                ->badge()
                ->colors([
                    'success' => StockMovement::TYPE_ENTRY,
                    'danger' => StockMovement::TYPE_EXIT,
                    'warning' => StockMovement::TYPE_ADJUSTMENT,
                ]),
            TextColumn::make('product.name')
                ->label('Produto')
                ->searchable(),
            TextColumn::make('quantity')
                ->label('Qtd.')
                ->numeric(2)
                ->alignRight(),
            TextColumn::make('unit_price')
                ->label('Preço unit.')
                ->money('BRL')
                ->alignRight(),
            TextColumn::make('total_value')
                ->label('Total')
                ->money('BRL')
                ->alignRight(),
            TextColumn::make('reference')
                ->label('Ref.'),
            TextColumn::make('user.name')
                ->label('Usuário'),
        ];
    }

    protected function getTableActions(): array
    {
        return [];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return true;
    }
}
