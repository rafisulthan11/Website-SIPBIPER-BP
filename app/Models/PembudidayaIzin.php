<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaIzin extends Model
{
    protected $primaryKey = 'id_izin';

    protected $fillable = [
        'id_pembudidaya',
        'nib',
        'npwp',
        'kusuka',
        'pengesahan_menkumham',
        'cbib',
        'skai',
        'surat_ijin_pembudidayaan_ikan',
        'akta_pendirian_usaha',
        'imb',
        'sup_perikanan',
        'sup_perdagangan',
    ];

    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
