<?php

namespace App\ExcelExports;

use App\Models\TransaksiPengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Import WithHeadings
use Maatwebsite\Excel\Concerns\WithMapping; // Import WithMapping

class TransaksiKeluarExcelExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = TransaksiPengeluaran::query()->with(['user', 'masterPengeluaran']);

        // Terapkan filter yang sama dengan yang ada di tabel Filament
        if (!empty($this->filters['tanggal']['from_date'])) {
            $query->whereDate('tanggal_transaksi', '>=', $this->filters['tanggal']['from_date']);
        }
        if (!empty($this->filters['tanggal']['to_date'])) {
            $query->whereDate('tanggal_transaksi', '<=', $this->filters['tanggal']['to_date']);
        }
        if (!empty($this->filters['master_pengeluaran_id'])) {
            $query->where('master_pengeluaran_id', $this->filters['master_pengeluaran_id']);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Dibuat Oleh',
            'Jenis Pengeluaran',
            'Jumlah',
            'Catatan',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    /**
     * @var TransaksiMasuk $transaksiMasuk
     */
    public function map($transaksiKeluar): array
    {
        return [
            $transaksiKeluar->tanggal_transaksi,
            $transaksiKeluar->user->name ?? '-',
            $transaksiKeluar->masterPengeluaran->nama_pengeluaran ?? '-',
            $transaksiKeluar->jumlah,
            $transaksiKeluar->catatan,
            $transaksiKeluar->created_at,
            $transaksiKeluar->updated_at,
        ];
    }
}