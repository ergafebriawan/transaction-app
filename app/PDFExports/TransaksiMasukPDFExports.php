<?php

namespace App\PDFExports;

use App\Models\TransaksiPemasukan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class TransaksiMasukPDFExports
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function generatePdf()
    {
        // Ambil data transaksi dengan filter yang diterapkan
        $query = TransaksiPemasukan::query()->with(['user', 'masterPemasukan']);

        if (isset($this->filters['tanggal']['from_date']) && !empty($this->filters['tanggal']['from_date'])) {
            $query->whereDate('tanggal_transaksi', '>=', $this->filters['tanggal']['from_date']);
        }
        if (isset($this->filters['tanggal']['to_date']) && !empty($this->filters['tanggal']['to_date'])) {
            $query->whereDate('tanggal_transaksi', '<=', $this->filters['tanggal']['to_date']);
        }
        if (isset($this->filters['master_pemasukan_id']) && !empty($this->filters['master_pemasukan_id'])) {
            $query->where('master_pemasukan_id', $this->filters['master_pemasukan_id']);
        }

        $transaksiMasukData = $query->get();

        // Load view Blade dan kirim data ke sana
        $pdf = Pdf::loadView('pdf.laporan-pemasukan', [
            'transaksiMasukData' => $transaksiMasukData,
            'filters' => $this->filters, // Kirim filter juga jika ingin ditampillkan di PDF
        ]);

        return $pdf;
    }
}