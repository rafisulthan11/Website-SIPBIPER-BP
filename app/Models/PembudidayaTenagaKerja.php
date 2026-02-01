<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaTenagaKerja extends Model
{
    protected $table = 'pembudidaya_tenaga_kerjas';
    protected $primaryKey = 'id_tk';
    
    protected $fillable = [
        'id_pembudidaya',
        'wni_laki_tetap',
        'wni_laki_tidak_tetap',
        'wni_laki_keluarga',
        'wni_perempuan_tetap',
        'wni_perempuan_tidak_tetap',
        'wni_perempuan_keluarga',
        'wna_laki_tetap',
        'wna_laki_tidak_tetap',
        'wna_laki_keluarga',
        'wna_perempuan_tetap',
        'wna_perempuan_tidak_tetap',
        'wna_perempuan_keluarga',
    ];
    
    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
