<?php

namespace App\Filament\Resources\UnitMeasurements\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitMeasurementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('abbreviation')
                    ->required(),
            ]);
    }
}
