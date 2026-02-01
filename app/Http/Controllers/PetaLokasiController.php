<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\MasterKecamatan;
use App\Models\Komoditas;

class PetaLokasiController extends Controller
{
    /**
     * Display peta interaktif pelaku usaha
     */
    public function index()
    {
        // Ambil data pembudidaya dengan koordinat lokasi usaha
        $pembudidayas = Pembudidaya::with(['ikan'])
            ->where(function($query) {
                // Prioritas lokasi usaha, fallback ke lokasi rumah
                $query->whereNotNull('latitude_usaha')
                      ->whereNotNull('longitude_usaha')
                      ->orWhere(function($q) {
                          $q->whereNotNull('latitude')
                            ->whereNotNull('longitude');
                      });
            })
            ->get()
            ->map(function ($item) {
                // Gunakan lokasi usaha jika ada, fallback ke lokasi rumah
                $lat = $item->latitude_usaha ?? $item->latitude;
                $lng = $item->longitude_usaha ?? $item->longitude;
                
                // Skip jika tidak ada koordinat sama sekali
                if (!$lat || !$lng) {
                    return null;
                }
                
                // Gabungkan jenis ikan menjadi string komoditas
                $komoditas = $item->ikan->pluck('jenis_ikan')->filter()->implode(', ') ?: '-';
                
                // Hitung total luas kolam untuk pembudidaya ini
                $totalLuasKolam = \DB::table('pembudidaya_kolams')
                    ->where('id_pembudidaya', $item->id_pembudidaya)
                    ->get()
                    ->sum(function($kolam) {
                        return is_numeric($kolam->ukuran) ? (float)$kolam->ukuran : 0;
                    });
                
                return [
                    'id' => $item->id_pembudidaya,
                    'nama' => $item->nama_lengkap,
                    'nama_usaha' => $item->nama_usaha,
                    'nik' => $item->nik_pembudidaya,
                    'kecamatan_id' => $item->kecamatan_usaha ?? $item->id_kecamatan,
                    'kecamatan' => [
                        'nama' => optional(\App\Models\MasterKecamatan::find($item->kecamatan_usaha ?? $item->id_kecamatan))->nama_kecamatan,
                    ],
                    'desa' => [
                        'nama' => optional(\App\Models\MasterDesa::find($item->desa_usaha ?? $item->id_desa))->nama_desa,
                    ],
                    'alamat' => $item->alamat_lengkap_usaha ?? $item->alamat,
                    'komoditas' => $komoditas,
                    'jenis_kegiatan' => $item->jenis_budidaya,
                    'skala_usaha' => $item->skala_usaha,
                    'status_usaha' => $item->status_usaha,
                    'kontak' => $item->kontak,
                    'latitude' => (float) $lat,
                    'longitude' => (float) $lng,
                    'type' => 'pembudidaya',
                    'lokasi_type' => $item->latitude_usaha ? 'usaha' : 'rumah',
                    'luas_kolam' => $totalLuasKolam
                ];
            })
            ->filter(); // Remove null values

        // Ambil data pengolah dengan koordinat
        $pengolahs = Pengolah::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_pengolah,
                    'nama' => $item->nama_lengkap,
                    'nama_usaha' => $item->nama_usaha,
                    'nik' => $item->nik_pengolah,
                    'kecamatan_id' => $item->id_kecamatan_usaha ?? $item->id_kecamatan,
                    'kecamatan' => [
                        'nama' => optional(\App\Models\MasterKecamatan::find($item->id_kecamatan_usaha ?? $item->id_kecamatan))->nama_kecamatan,
                    ],
                    'desa' => [
                        'nama' => optional(\App\Models\MasterDesa::find($item->id_desa_usaha ?? $item->id_desa))->nama_desa,
                    ],
                    'alamat' => $item->alamat_usaha ?? $item->alamat,
                    'komoditas' => $item->komoditas ?? '-',
                    'jenis_kegiatan' => $item->jenis_kegiatan_usaha,
                    'skala_usaha' => $item->skala_usaha,
                    'status_usaha' => $item->status_usaha,
                    'kontak' => $item->kontak,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'type' => 'pengolah',
                    'lokasi_type' => 'usaha'
                ];
            });

        // Ambil data pemasar dengan koordinat
        $pemasars = Pemasar::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_pemasar,
                    'nama' => $item->nama_lengkap,
                    'nama_usaha' => $item->nama_usaha,
                    'nik' => $item->nik_pemasar,
                    'kecamatan_id' => $item->id_kecamatan_usaha ?? $item->id_kecamatan,
                    'kecamatan' => [
                        'nama' => optional(\App\Models\MasterKecamatan::find($item->id_kecamatan_usaha ?? $item->id_kecamatan))->nama_kecamatan,
                    ],
                    'desa' => [
                        'nama' => optional(\App\Models\MasterDesa::find($item->id_desa_usaha ?? $item->id_desa))->nama_desa,
                    ],
                    'alamat' => $item->alamat_usaha ?? $item->alamat,
                    'komoditas' => $item->komoditas ?? '-',
                    'jenis_kegiatan' => $item->jenis_kegiatan_usaha,
                    'skala_usaha' => $item->skala_usaha,
                    'status_usaha' => $item->status_usaha,
                    'kontak' => $item->kontak,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'type' => 'pemasar',
                    'lokasi_type' => 'usaha',
                    'luas_lahan' => (float) ($item->luas_lahan ?? 0)
                ];
            });

        // Gabungkan semua data pelaku usaha
        $allData = $pembudidayas->concat($pengolahs)->concat($pemasars);

        // Hitung statistik
        $totalLokasi = $allData->count();
        
        // Hitung luas lahan investasi dari pemasar
        $luasLahanInvestasi = Pemasar::sum('luas_lahan') ?? 0;
        
        // Hitung luas kolam dari pembudidaya (menggunakan ukuran kolam, konversi string ke float)
        $kolams = \DB::table('pembudidaya_kolams')->get();
        $luasKolam = $kolams->sum(function($kolam) {
            // Coba konversi ukuran ke float, jika tidak bisa return 0
            return is_numeric($kolam->ukuran) ? (float)$kolam->ukuran : 0;
        });
        
        // Total gabungan
        $totalGabungan = $luasLahanInvestasi + $luasKolam;

        // Ambil daftar kecamatan untuk filter
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();
        
        // Ambil daftar komoditas untuk filter
        $komoditas = Komoditas::orderBy('nama_komoditas')->get();

        return view('pages.peta-lokasi.index', compact('allData', 'kecamatans', 'komoditas', 'totalLokasi', 'luasLahanInvestasi', 'luasKolam', 'totalGabungan'));
    }
}
