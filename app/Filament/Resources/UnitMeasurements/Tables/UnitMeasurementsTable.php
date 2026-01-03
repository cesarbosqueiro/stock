<?php

namespace App\Filament\Resources\UnitMeasurements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitMeasurementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Unidade de Medida')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedCube)
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('abbreviation')
                    ->label('Abreviação')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedScale)
                    ->copyable()
                    ->copyMessage('Abreviação copiada!')
                    ->weight('semibold')
                    ->toggleable(), // permite ocultar se quiser

                // apenas se quiser um visual booleano (comente se o campo não existir)
                IconColumn::make('is_base')
                    ->label('Padrão')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->description(fn ($record): string => $record->updated_at?->format('d/m/Y H:i') ?? '')
                    ->icon(Heroicon::OutlinedClock)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // sem filtros por enquanto; se quiser filtro por "padrão" ou por texto eu adiciono
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
            ->emptyStateHeading('Nenhuma unidade cadastrada')
            ->emptyStateDescription('Crie uma nova unidade de medida para usá-la nos produtos.')
            ->emptyStateIcon(Heroicon::OutlinedScale);
    }
}
