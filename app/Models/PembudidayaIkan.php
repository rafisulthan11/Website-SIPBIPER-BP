<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembudidayaIkan extends Model
{
    protected $table = 'pembudidaya_ikans';
    protected $primaryKey = 'id_ikan';
    
    protected $fillable = [
        'id_pembudidaya',
        'jenis_ikan',
        'jenis_indukan',
        'jumlah',
        'asal',
    ];
    
    public function pembudidaya()
    {
        return $this->belongsTo(Pembudidaya::class, 'id_pembudidaya', 'id_pembudidaya');
    }
}
