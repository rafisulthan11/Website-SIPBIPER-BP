<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDesa extends Model
{
    protected $table = 'master_desas';
    protected $primaryKey = 'id_desa';
    
    protected $fillable = [
        'id_kecamatan',
        'nama_desa',
        'kode_desa',
    ];
}
