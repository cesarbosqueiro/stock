<?php

namespace App\Filament\Resources\UnitMeasurements;

use App\Filament\Clusters\Configuracoes;

use App\Filament\Resources\UnitMeasurements\Pages\CreateUnitMeasurement;
use App\Filament\Resources\UnitMeasurements\Pages\EditUnitMeasurement;
use App\Filament\Resources\UnitMeasurements\Pages\ListUnitMeasurements;
use App\Filament\Resources\UnitMeasurements\Schemas\UnitMeasurementForm;
use App\Filament\Resources\UnitMeasurements\Tables\UnitMeasurementsTable;
use App\Models\UnitMeasurement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UnitMeasurementResource extends Resource
{
    protected static ?string $model = UnitMeasurement::class;

    protected static ?string $cluster = Configuracoes::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?string $modelLabel = 'Unidade de medida';

    public static function form(Schema $schema): Schema
    {
        return UnitMeasurementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitMeasurementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnitMeasurements::route('/'),
            'create' => CreateUnitMeasurement::route('/create'),
            'edit' => EditUnitMeasurement::route('/{record}/edit'),
        ];
    }
}
