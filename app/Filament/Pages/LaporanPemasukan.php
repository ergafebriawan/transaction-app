<?php

namespace App\Filament\Pages;

use App\Models\MasterPemasukan;
use App\Models\TransaksiPemasukan;
use App\PDFExports\TransaksiMasukPDFExports;
use App\ExcelExports\TransaksiMasukExcelExport;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LaporanPemasukan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Pemasukan';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Laporan';
    protected static string $view = 'filament.pages.laporan-pemasukan';
    protected static ?string $title = 'Laporan Pemasukan';

    public function table(Table $table): Table
    {
        return $table
            ->query(TransaksiPemasukan::query()->with(['user', 'masterPemasukan'])) // Mengambil data dan eager load relasi
            ->columns([
                TextColumn::make('tanggal_transaksi')
                    ->date() // Format sebagai tanggal
                    ->sortable() // Bisa diurutkan
                    ->label('Tanggal Transaksi'), // Label kolom
                TextColumn::make('user.name') // Mengambil nama user dari relasi
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->searchable(), // Bisa dicari
                TextColumn::make('masterPemasukan.nama_pemasukan') // Mengambil nama jenis pemasukan dari relasi
                    ->label('Jenis Pemasukan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah')
                    ->numeric() // Format sebagai angka
                    ->prefix('Rp ') // Tambahkan prefix 'Rp '
                    ->sortable(),
                TextColumn::make('catatan')
                    ->limit(70) // Batasi panjang teks
                    ->tooltip(function (TextColumn $column): ?string { // Tampilkan tooltip jika teks panjang
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true) // Bisa disembunyikan/ditampilkan
                    ->label('Dibuat Pada'),
            ])
            ->filters([
                // Filter berdasarkan tanggal
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('from_date')
                            ->label('Dari Tanggal'),
                        DatePicker::make('to_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn(\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),
                SelectFilter::make('master_pemasukan_id')
                    ->relationship('masterPemasukan', 'nama_pemasukan') // Relasi ke MasterPemasukan, tampilkan 'nama_pemasukan'
                    ->label('Filter Jenis Pemasukan')
                    ->preload() // Memuat semua opsi di awal
                    ->searchable(), // Opsi filter bisa dicari
            ])
            ->actions([])
            ->bulkActions([
                // Kamu bisa menambahkan aksi massal di sini jika diperlukan,
                // contoh: Tables\Actions\BulkActionGroup::make([ Tables\Actions\DeleteBulkAction::make() ]),
            ])
            ->headerActions([
                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        DatePicker::make('excel_from_date')
                            ->label('Dari Tanggal')
                        ,
                        DatePicker::make('excel_to_date')
                            ->label('Sampai Tanggal')
                        ,
                        Select::make('excel_master_pemasukan_id')
                            ->label('Jenis Pemasukan')
                            ->options(
                                MasterPemasukan::pluck('nama_pemasukan', 'id')->toArray()
                            )
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function (array $data) {
                        // Kumpulkan data filter dari popup form
                        $filtersFromPopup = [
                            'tanggal' => [
                                'from_date' => $data['excel_from_date'],
                                'to_date' => $data['excel_to_date'],
                            ],
                            'master_pemasukan_id' => $data['excel_master_pemasukan_id'] ?? null, // Jika ada filter jenis pemasukan
                        ];

                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new TransaksiMasukExcelExport($filtersFromPopup), // Kirim filter dari popup
                            'laporan_pemasukan_' . now()->format('Ymd_His') . '.xlsx'
                        );
                    }),
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->form([
                        DatePicker::make('pdf_from_date')
                            ->label('Dari Tanggal'),
                        DatePicker::make('pdf_to_date')
                            ->label('Sampai Tanggal'),
                        Select::make('pdf_master_pemasukan_id')
                            ->label('Jenis Pemasukan')
                            ->options(
                                MasterPemasukan::pluck('nama_pemasukan', 'id')->toArray()
                            )
                            ->searchable()
                            ->preload()
                    ])
                    ->action(function (array $data) {
                        $filtersFromPopup = [
                            'tanggal' => [
                                'from_date' => $data['pdf_from_date'],
                                'to_date' => $data['pdf_to_date'],
                            ],
                            'master_pemasukan_id' => $data['pdf_master_pemasukan_id'] ?? null,
                        ];

                        // Panggil class PDF Export dan generate PDF
                        $pdfExport = new TransaksiMasukPDFExports($filtersFromPopup);
                        $pdf = $pdfExport->generatePdf();

                        // Download PDF
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'laporan_pemasukan_' . now()->format('Ymd_His') . '.pdf');
                    }),
            ]);
    }
}
