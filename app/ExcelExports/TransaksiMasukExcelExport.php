<?php

namespace App\ExcelExports;

use App\Models\TransaksiPemasukan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Import WithHeadings
use Maatwebsite\Excel\Concerns\WithMapping; // Import WithMapping

class TransaksiMasukExcelExport implements FromCollection, WithHeadings, WithMapping
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
        $query = TransaksiPemasukan::query()->with(['user', 'masterPemasukan']);

        // Terapkan filter yang sama dengan yang ada di tabel Filament
        if (!empty($this->filters['tanggal']['from_date'])) {
            $query->whereDate('tanggal_transaksi', '>=', $this->filters['tanggal']['from_date']);
        }
        if (!empty($this->filters['tanggal']['to_date'])) {
            $query->whereDate('tanggal_transaksi', '<=', $this->filters['tanggal']['to_date']);
        }
        if (!empty($this->filters['master_pemasukan_id'])) {
            $query->where('master_pemasukan_id', $this->filters['master_pemasukan_id']);
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
            'Jenis Pemasukan',
            'Jumlah',
            'Catatan',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    /**
     * @var TransaksiMasuk $transaksiMasuk
     */
    public function map($transaksiMasuk): array
    {
        return [
            $transaksiMasuk->tanggal_transaksi,
            $transaksiMasuk->user->name ?? '-', // Ambil nama user, atau '-' jika null
            $transaksiMasuk->masterPemasukan->nama_pemasukan ?? '-', // Ambil jenis pemasukan
            $transaksiMasuk->jumlah,
            $transaksiMasuk->catatan,
            $transaksiMasuk->created_at,
            $transaksiMasuk->updated_at,
        ];
    }
}