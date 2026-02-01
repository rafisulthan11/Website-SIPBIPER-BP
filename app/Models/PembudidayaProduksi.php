<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaProduksi extends Model
{
    protected $table = 'pembudidaya_produksis';
    protected $primaryKey = 'id_produksi';
    
    protected $fillable = [
        'id_pembudidaya',
        'total_luas_kolam',
        'total_produksi',
        'satuan_produksi',
        'harga_per_satuan',
    ];
    
    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
