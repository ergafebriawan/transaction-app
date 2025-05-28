<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiPengeluaran extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function masterPengeluaran()
    {
        // Pastikan MasterPemasukan::class diimport dengan benar di bagian atas file
        return $this->belongsTo(MasterPengeluaran::class);
    }

    public function user()
    {
        // Pastikan User::class diimport dengan benar di bagian atas file
        return $this->belongsTo(User::class);
    }
}
