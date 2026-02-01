<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKecamatan extends Model
{
    protected $table = 'master_kecamatans';
    protected $primaryKey = 'id_kecamatan';
    
    protected $fillable = [
        'nama_kecamatan',
        'kode_kecamatan',
    ];

    public function desas()
    {
        return $this->hasMany(MasterDesa::class, 'id_kecamatan', 'id_kecamatan');
    }
}
