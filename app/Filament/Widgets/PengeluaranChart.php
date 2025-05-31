<?php

namespace App\Filament\Widgets;

use App\Models\TransaksiPengeluaran;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;


class PengeluaranChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Transaksi Pengeluaran';

    protected function getData(): array
    {
        $incomes = Trend::model(TransaksiPengeluaran::class)
            ->between(
                start: now()->subMonths(11)->startOfMonth(), // 12 bulan terakhir
                end: now()->endOfMonth(),
            )
            ->perMonth()
            ->sum('jumlah');
        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $incomes->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
                    'borderColor' => '#22C55E', // Warna hijau untuk pemasukan
                    'fill' => false,
                    'tension' => 0.1,
                ],
            ],
            'labels' => $incomes->map(fn (TrendValue $value) => $value->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value, index, ticks) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.dataset.label + ": Rp " + context.parsed.y.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
