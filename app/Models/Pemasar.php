<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasar extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pemasar';

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
        'nik_pemasar',
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
        'nama_usaha',
        'nama_kelompok',
        'npwp_usaha',
        'alamat_usaha',
        'telp_usaha',
        'email_usaha',
        'skala_usaha',
        'status_usaha',
        'tahun_mulai_usaha',
        'aset_pribadi',
        'kontak',
        'latitude',
        'longitude',
        'id_kecamatan_usaha',
        'id_desa_usaha',
        // Izin Usaha
        'nib',
        'npwp_izin',
        'kusuka',
        'pengesahan_menkumham',
        'tdu_php',
        'sppl',
        'siup_perdagangan',
        'akta_pendiri_usaha',
        'imb',
        'siup_perikanan',
        'ukl_upl',
        'amdal',
        // Investasi - Modal Tetap
        'investasi_tanah',
        'investasi_gedung',
        'investasi_mesin_peralatan',
        'investasi_kendaraan',
        'investasi_lain_lain',
        'investasi_sub_jumlah',
        // Investasi - Modal Kerja
        'modal_kerja_1_bulan',
        'modal_kerja_sub_jumlah',
        // Sumber Pembiayaan
        'modal_sendiri',
        'laba_ditanam',
        'modal_pinjam',
        // Sertifikat Lahan
        'sertifikat_lahan',
        'luas_lahan',
        'nilai_lahan',
        // Sertifikat Bangunan
        'sertifikat_bangunan',
        'luas_bangunan',
        'nilai_bangunan',
        // Kapasitas dan Produksi
        'mesin_peralatan',
        // Tenaga Kerja - WNI
        'wni_laki_tetap',
        'wni_laki_tidak_tetap',
        'wni_laki_keluarga',
        'wni_perempuan_tetap',
        'wni_perempuan_tidak_tetap',
        'wni_perempuan_keluarga',
        // Tenaga Kerja - WNA
        'wna_laki_tetap',
        'wna_laki_tidak_tetap',
        'wna_laki_keluarga',
        'wna_perempuan_tetap',
        'wna_perempuan_tidak_tetap',
        'wna_perempuan_keluarga',
        // Lampiran
        'foto_ktp',
        'foto_sertifikat',
        'foto_cpib_cbib',
        'foto_unit_usaha',
        'foto_npwp',
        'foto_izin_usaha',
        'foto_produk',
        'foto_sertifikat_pirt',
        'foto_sertifikat_halal',
    ];

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

    public function pemasaran()
    {
        return $this->hasMany(PemasarPemasaran::class, 'id_pemasar', 'id_pemasar');
    }

    public function getKomoditasAttribute()
    {
        if (! $this->relationLoaded('pemasaran')) {
            $this->loadMissing('pemasaran');
        }

        $komoditas = $this->pemasaran
            ->pluck('komoditas')
            ->filter()
            ->unique()
            ->values();

        return $komoditas->isNotEmpty() ? $komoditas->implode(', ') : '-';
    }

    public function getPemasaranSectionsAttribute()
    {
        if (! $this->relationLoaded('pemasaran')) {
            $this->loadMissing('pemasaran');
        }

        return $this->pemasaran
            ->groupBy(function ($row) {
                return $row->section_index ?? 0;
            })
            ->values();
    }

    public function getTotalVolumePemasaranAttribute()
    {
        if (! $this->relationLoaded('pemasaran')) {
            $this->loadMissing('pemasaran');
        }

        return $this->pemasaranSections->sum(function ($sectionRows) {
            $firstRow = $sectionRows->first();
            return (float) ($firstRow->hasil_produksi_kg ?? 0);
        });
    }

    public function getTotalNilaiPemasaranAttribute()
    {
        if (! $this->relationLoaded('pemasaran')) {
            $this->loadMissing('pemasaran');
        }

        return $this->pemasaranSections->sum(function ($sectionRows) {
            $firstRow = $sectionRows->first();
            return (float) ($firstRow->hasil_produksi_rp ?? 0);
        });
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
