<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Saldo', 'Rp 10.500.000,00')
                ->description('total saldo')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Pengeluaran', '21%')
                ->description('7% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Pemasukan', '79%')
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
