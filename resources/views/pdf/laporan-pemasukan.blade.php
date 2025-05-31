{{-- resources/views/pdf/laporan-pemasukan.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemasukan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .filters {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
        }
        .filters p {
            margin: 0 0 5px 0;
        }
    </style>
</head>
<body>
    <h1>Laporan Pemasukan</h1>

    @if (!empty($filters))
        <div class="filters">
            <p><strong>Filter Yang Diterapkan:</strong></p>
            @if (!empty($filters['tanggal']['from_date']))
                <p>Dari Tanggal: {{ $filters['tanggal']['from_date'] }}</p>
            @endif
            @if (!empty($filters['tanggal']['to_date']))
                <p>Sampai Tanggal: {{ $filters['tanggal']['to_date'] }}</p>
            @endif
            @if (!empty($filters['master_pemasukan_id']))
                @php
                    $masterPemasukan = \App\Models\MasterPemasukan::find($filters['master_pemasukan_id']);
                @endphp
                @if ($masterPemasukan)
                    <p>Jenis Pemasukan: {{ $masterPemasukan->nama_pemasukan }}</p>
                @endif
            @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Tanggal Transaksi</th>
                <th>Dibuat Oleh</th>
                <th>Jenis Pemasukan</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksiMasukData as $transaksi)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td>{{ $transaksi->user->name ?? '-' }}</td>
                    <td>{{ $transaksi->masterPemasukan->nama_pemasukan ?? '-' }}</td>
                    <td>Rp {{ number_format($transaksi->jumlah, 2, ',', '.') }}</td>
                    <td>{{ $transaksi->catatan }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">Total Pemasukan:</td>
                <td style="font-weight: bold;">Rp {{ number_format($transaksiMasukData->sum('jumlah'), 2, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>