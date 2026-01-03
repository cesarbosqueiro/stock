<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                        ->label('Nome')
                        ->required(),
                    Textarea::make('description')
                        ->label('Descricao')
                        ->columnSpanFull(),
                ]),
                Section::make([TextInput::make('code')
                    ->label('Codigo')
                    ->required(),
                    Select::make('category_id')
                        ->label('Categoria')
                        ->relationship('category', 'name')
                        ->required(),
                    Toggle::make('is_active')
                        ->label('Ativo')
                        ->required(),
                ]),
                Section::make([
                    Select::make('unit_measurement_id')
                        ->label('Unidade de medida')
                        ->relationship('unitMeasurement', 'name')
                        ->required(),
                    TextInput::make('minimum_stock')
                        ->label('Minimo em estoque')
                        ->required()
                        ->numeric()
                        ->default(0),
                ]),
            ]);
    }
}
