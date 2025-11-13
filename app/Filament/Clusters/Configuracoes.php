<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class Configuracoes extends Cluster
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    protected static ?string $title = 'Configurações';

    protected static ?int $navigationSort = 2;
}
