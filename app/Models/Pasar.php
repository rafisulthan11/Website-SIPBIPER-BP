<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasar extends Model
{
    protected $table = 'pasar';
    protected $primaryKey = 'id_pasar';
    
    protected $fillable = [
        'nama_pasar',
        'id_kecamatan',
        'id_desa',
        'kecamatan',
        'desa',
        'alamat',
        'latitude',
        'longitude',
        'status'
    ];

    /**
     * Get the kecamatan that owns the pasar.
     */
    public function kecamatanRelation()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Get the desa that owns the pasar.
     */
    public function desaRelation()
    {
        return $this->belongsTo(MasterDesa::class, 'id_desa', 'id_desa');
    }
}
