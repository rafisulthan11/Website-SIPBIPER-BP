<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaInvestasi extends Model
{
    protected $primaryKey = 'id_investasi';

    protected $fillable = [
        'id_pembudidaya',
        'nilai_asset',
        'laba_ditanam',
        'sewa',
        'pinjaman',
        'modal_sendiri',
        'lahan_status',
        'luas_m2',
        'nilai_bangunan',
        'bangunan',
        'sertifikat',
    ];

    protected $casts = [
        'pinjaman' => 'boolean',
        'lahan_status' => 'array',
    ];

    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
