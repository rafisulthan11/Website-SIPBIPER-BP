<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengolah extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pengolah';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun_pendataan',
        'status',
        'verified_by',
        'verified_at',
        'catatan_perbaikan',
        'created_by',
        'updated_by',
        'nik_pengolah',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'no_npwp',
        'email',
        'status_perkawinan',
        'jumlah_tanggungan',
        'aset_pribadi',
        'alamat',
        'id_kecamatan',
        'id_desa',
        'jenis_kegiatan_usaha',
        'jenis_pengolahan',
        'nama_usaha',
        'nama_kelompok',
        'id_kecamatan_usaha',
        'id_desa_usaha',
        'npwp_usaha',
        'alamat_usaha',
        'telp_usaha',
        'email_usaha',
        'skala_usaha',
        'status_usaha',
        'tahun_mulai_usaha',
        'kontak',
        'komoditas',
        'latitude',
        'longitude',
        // Izin Usaha
        'nib',
        'kusuka',
        'pengesahan_menkumham',
        'tdu_php',
        'akta_pendirian_usaha',
        'imb',
        'siup_perikanan',
        'siup_perdagangan',
        'sppl',
        'ukl_upl',
        'amdal',
        // Data dan Lampiran
        'produksi_data',
        'tenaga_kerja_data',
        'foto_ktp',
        'foto_sertifikat',
        'foto_cpib_cbib',
        'foto_unit_usaha',
        'foto_kusuka',
        'foto_nib',
        'foto_sertifikat_pirt',
        'foto_sertifikat_halal',
    ];

    protected $casts = [
        'produksi_data' => 'array',
        'tenaga_kerja_data' => 'array',
    ];

    public function getKomoditasAttribute($value)
    {
        $komoditas = collect($this->produksi_data ?? [])
            ->pluck('komoditas')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($komoditas)) {
            return implode(', ', $komoditas);
        }

        return $value;
    }

    /**
     * Relasi ke Kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Relasi ke Desa
     */
    public function desa()
    {
        return $this->belongsTo(MasterDesa::class, 'id_desa', 'id_desa');
    }

    /**
     * Relasi ke Kecamatan Usaha
     */
    public function kecamatanUsaha()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan_usaha', 'id_kecamatan');
    }

    /**
     * Relasi ke Desa Usaha
     */
    public function desaUsaha()
    {
        return $this->belongsTo(MasterDesa::class, 'id_desa_usaha', 'id_desa');
    }

    public function produksi()
    {
        return $this->hasMany(PengolahProduksi::class, 'id_pengolah', 'id_pengolah');
    }

    public function tenagaKerja()
    {
        return $this->hasMany(PengolahTenagaKerja::class, 'id_pengolah', 'id_pengolah');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_user');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id_user');
    }
}
