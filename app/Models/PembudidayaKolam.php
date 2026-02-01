<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaKolam extends Model
{
    protected $table = 'pembudidaya_kolams';
    protected $primaryKey = 'id_kolam';
    
    protected $fillable = [
        'id_pembudidaya',
        'jenis_kolam',
        'ukuran',
        'jumlah',
        'komoditas',
    ];
    
    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
