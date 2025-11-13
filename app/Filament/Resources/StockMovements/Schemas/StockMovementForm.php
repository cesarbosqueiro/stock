<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informacoes do produto')
                    ->schema([
                    Select::make('product_id')
                        ->label('Produto')
                        ->relationship(
                            name: 'product',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn ($query) => $query->where('is_active', true)
                        )
                        ->searchable()
                        ->preload()
                        ->required()
                        ->columnSpanFull(),
                    Select::make('type')
                        ->label('Tipo de Movimentação')
                        ->options([
                            'entry' => 'Entrada',
                            'exit' => 'Saída',
                            'adjustment' => 'Ajuste',
                        ])
                        ->required()
                        ->native(false),]),
                Section::make('Informacoes unitarias')
                    ->schema([
                    TextInput::make('quantity')
                        ->label('Quantidade')
                        ->required()
                        ->numeric()
                        ->minValue(0.01)
                        ->step(0.01)
                        ->live(onBlur: true),
                    TextInput::make('unit_price')
                        ->prefix('R$')
                        ->label('Valor por unidade')
                        ->numeric()
                    ]),
                Section::make('Informações Adicionais')
                    ->schema([
                        TextInput::make('reference')
                            ->label('Referência')
                            ->placeholder('Ex: NF-001, OS-123')
                            ->maxLength(255),

                        Textarea::make('notes')
                            ->label('Observações')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                Section::make('Informacoes fixas')
                    ->schema([
                    DatePicker::make('movement_date')
                        ->label('Data da Movimentacao')
                        ->default(now())
                        ->required()
                        ->disabled()
                        ->dehydrated(),
                    Select::make('user_id')
                        ->label('Usuario')
                        ->relationship('user', 'name')
                        ->default(auth()->id())
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                    TextInput::make('total_value')
                        ->label('Valor total')
                        ->prefix('R$')
                        ->disabled()
                        ->afterStateUpdated(fn ($state, Get $get, Set $set) =>
                        self::calculateTotal($get, $set)
                        )
                        ->numeric(),
                    ]),
            ]);
    }

    protected static function calculateTotal(Get $get, Set $set): void
    {
        $quantity = floatval($get('quantity') ?? 0);
        $unitPrice = floatval($get('unit_price') ?? 0);

        if ($quantity && $unitPrice) {
            $total = $quantity * $unitPrice;
            $set('total_value', number_format($total, 2, '.', ''));
        }
    }
}
