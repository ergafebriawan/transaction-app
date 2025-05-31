<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TransaksiPemasukan;
use App\Models\TransaksiPengeluaran;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPemasukan = TransaksiPemasukan::sum('jumlah');
        $totalPengeluaran = TransaksiPengeluaran::sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        return [
            Stat::make('Saldo Saat Ini', 'Rp ' . number_format($saldo, 0, ',', '.'))
                ->description('Total uang yang tersedia')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color($saldo >= 0 ? 'success' : 'danger'), // Warna hijau jika positif, merah jika negatif

            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukan, 0, ',', '.'))
                ->description('Total uang masuk')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaran, 0, ',', '.'))
                ->description('Total uang keluar')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
