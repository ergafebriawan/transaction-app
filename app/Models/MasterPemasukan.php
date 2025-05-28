<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterPemasukan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function masterPemasukan()
    {
        // Pastikan MasterPemasukan::class diimport dengan benar di bagian atas file
        return $this->belongsTo(MasterPemasukan::class);
    }
}
