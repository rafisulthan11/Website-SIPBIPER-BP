<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemasarPemasaran extends Model
{
    use HasFactory;

    protected $table = 'pemasars_pemasaran';

    protected $primaryKey = 'id_pemasar_pemasaran';

    protected $fillable = [
        'id_pemasar',
        'section_index',
        'kapasitas_terpasang',
        'hasil_produksi_kg',
        'hasil_produksi_rp',
        'bulan_produksi',
        'distribusi_pemasaran',
        'komoditas',
        'asal_ikan',
        'jumlah_volume',
        'harga_beli',
        'harga_jual',
    ];

    public function pemasar()
    {
        return $this->belongsTo(Pemasar::class, 'id_pemasar', 'id_pemasar');
    }
}
