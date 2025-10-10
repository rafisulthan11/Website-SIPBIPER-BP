<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembudidaya extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pembudidaya';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik_pembudidaya',
        'nama_lengkap',
        'jenis_kelamin',     
        'tempat_lahir',     
        'tanggal_lahir',
        'pendidikan_terakhir',
        'no_npwp',
        'email',
        'status_perkawinan',
        'jumlah_tanggungan',
        'alamat',
        'id_kecamatan',
        'id_desa',
        'jenis_kegiatan_usaha',
        'jenis_budidaya',
        'nama_usaha',
        'npwp_usaha',
        'alamat_usaha',
        'telp_usaha',
        'email_usaha',
        'skala_usaha',
        'status_usaha',
        'tahun_mulai_usaha',
        'kontak',
        'latitude',
        'longitude',
    ];

    // Definisikan relasi ke Kecamatan dan Desa
    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function desa()
    {
        return $this->belongsTo(MasterDesa::class, 'id_desa', 'id_desa');
    }

    public function investasi()
    {
        return $this->hasOne(PembudidayaInvestasi::class, 'id_pembudidaya', 'id_pembudidaya');
    }

    public function izin()
    {
        return $this->hasOne(PembudidayaIzin::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}