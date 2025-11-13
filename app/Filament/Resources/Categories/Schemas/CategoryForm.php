<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                    ->label('Nome')
                    ->required(),])->columnSpanFull(),
                Section::make([
                    Textarea::make('description')
                        ->label('Descricao')
                ])->columnSpanFull()
            ]);
    }
}
