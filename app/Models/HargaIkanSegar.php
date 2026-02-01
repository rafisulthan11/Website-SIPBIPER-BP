<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaIkanSegar extends Model
{
    use HasFactory;

    protected $table = 'harga_ikan_segars';
    protected $primaryKey = 'id_harga';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_kecamatan',
        'id_desa',
        'tanggal_input',
        'nama_pasar',
        'nama_pedagang',
        'asal_ikan',
        'jenis_ikan',
        'ukuran',
        'harga_produsen',
        'harga_konsumen',
        'satuan',
        'kuantitas_perminggu',
        'keterangan',
    ];

    /**
     * Get the kecamatan that owns the harga ikan segar.
     */
    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Get the desa that owns the harga ikan segar.
     */
    public function desa()
    {
        return $this->belongsTo(MasterDesa::class, 'id_desa', 'id_desa');
    }
}
