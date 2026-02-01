<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKecamatan;

class HapusKecamatanTidakSesuaiSeeder extends Seeder
{
    public function run(): void
    {
        // Kecamatan yang sesuai dengan gambar (31 kecamatan)
        $kecamatanValid = [
            'Ajung', 'Ambulu', 'Arjasa', 'Bangsalsari', 'Balung',
            'Gumukmas', 'Jelbuk', 'Jenggawah', 'Jombang', 'Kalisat',
            'Kaliwates', 'Kencong', 'Ledokombo', 'Mayang', 'Mumbulsari',
            'Panti', 'Pakusari', 'Patrang', 'Puger', 'Rambipuji',
            'Semboro', 'Silo', 'Sukorambi', 'Sukowono', 'Sumberbaru',
            'Sumberjambe', 'Sumbersari', 'Tanggul', 'Tempurejo',
            'Umbulsari', 'Wuluhan'
        ];

        // Cari kecamatan yang tidak sesuai
        $kecamatanTidakSesuai = MasterKecamatan::whereNotIn('nama_kecamatan', $kecamatanValid)->get();
        
        $deletedDesa = 0;
        $deletedKecamatan = 0;
        
        foreach ($kecamatanTidakSesuai as $kec) {
            echo "Menghapus kecamatan: " . $kec->nama_kecamatan . "\n";
            
            // Hapus desa-desa di kecamatan ini terlebih dahulu
            $countDesa = $kec->desas()->count();
            $kec->desas()->delete();
            $deletedDesa += $countDesa;
            
            // Hapus kecamatan
            $kec->delete();
            $deletedKecamatan++;
        }

        echo "\nKecamatan yang tidak sesuai berhasil dihapus!\n";
        echo "Jumlah desa dihapus: " . $deletedDesa . "\n";
        echo "Jumlah kecamatan dihapus: " . $deletedKecamatan . "\n";
        echo "Total kecamatan sekarang: " . MasterKecamatan::count() . "\n";
        echo "Total desa sekarang: " . \App\Models\MasterDesa::count() . "\n";
    }
}
