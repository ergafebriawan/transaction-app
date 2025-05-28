<?php

namespace App\Filament\Pages;

use App\Models\TransaksiPemasukan;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
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
    protected static ?int $navigationSort = 3;
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
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal_transaksi', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal_transaksi', '<=', $date),
                            );
                    }),
                // Filter berdasarkan jenis pemasukan (dari MasterPemasukan)
                SelectFilter::make('master_pemasukan_id')
                    ->relationship('masterPemasukan', 'nama_pemasukan') // Relasi ke MasterPemasukan, tampilkan 'nama_pemasukan'
                    ->label('Filter Jenis Pemasukan')
                    ->preload() // Memuat semua opsi di awal
                    ->searchable(), // Opsi filter bisa dicari
            ])
            ->actions([
                // Kamu bisa menambahkan aksi per baris di sini jika diperlukan,
                // contoh: Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Kamu bisa menambahkan aksi massal di sini jika diperlukan,
                // contoh: Tables\Actions\BulkActionGroup::make([ Tables\Actions\DeleteBulkAction::make() ]),
            ]);
    }
}
