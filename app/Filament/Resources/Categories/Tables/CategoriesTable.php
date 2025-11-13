<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::OutlinedTag)
                    ->iconColor('primary')
                    ->weight('semibold'),

                TextColumn::make('description')
                    ->label('Descricao')
                    ->searchable()
                    ->limit(50)
                    ->wrap()
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->placeholder('Sem descricao')
                    ->color('gray'),

                TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->badge()
                    ->color('success')
                    ->icon(Heroicon::OutlinedCube),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon(Heroicon::OutlinedClock),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon(Heroicon::OutlinedClock),
            ])
            ->filters([
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
            ->defaultSort('name', 'asc')
            ->striped()
            ->emptyStateHeading('Nenhuma categoria cadastrada')
            ->emptyStateDescription('Comece criando uma nova categoria de produtos.')
            ->emptyStateIcon(Heroicon::OutlinedFolderOpen);
    }
}
