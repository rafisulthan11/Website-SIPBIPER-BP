<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;

class GrafikController extends Controller
{
    public function pelakuUsaha()
    {
        // Hitung jumlah pelaku usaha per kategori
        $pembudidayaCount = Pembudidaya::count();
        $pengolahCount = Pengolah::count();
        $pemasarCount = Pemasar::count();
        
        $data = [
            'pembudidaya' => $pembudidayaCount,
            'pengolah' => $pengolahCount,
            'pemasar' => $pemasarCount,
            'total' => $pembudidayaCount + $pengolahCount + $pemasarCount
        ];
        
        // Data per kecamatan
        $kecamatanData = \DB::table('master_kecamatans')
            ->select('master_kecamatans.nama_kecamatan', \DB::raw('
                (SELECT COUNT(*) FROM pembudidayas WHERE pembudidayas.id_kecamatan = master_kecamatans.id_kecamatan) +
                (SELECT COUNT(*) FROM pengolahs WHERE pengolahs.id_kecamatan = master_kecamatans.id_kecamatan) +
                (SELECT COUNT(*) FROM pemasars WHERE pemasars.id_kecamatan = master_kecamatans.id_kecamatan) as total
            '))
            ->orderBy('master_kecamatans.nama_kecamatan')
            ->get();
        
        // Data komoditas teratas dari pembudidaya (berdasarkan jenis_ikan dan jumlah)
        $komoditasData = \DB::table('pembudidaya_ikans')
            ->select('jenis_ikan', \DB::raw('SUM(jumlah) as total_jumlah'))
            ->whereNotNull('jumlah')
            ->groupBy('jenis_ikan')
            ->orderByDesc('total_jumlah')
            ->limit(5)
            ->get();
        
        return view('pages.grafik.pelaku-usaha', compact('data', 'kecamatanData', 'komoditasData'));
    }

    public function hargaIkanSegar(Request $request)
    {
        $tahun = $request->input('tahun');
        
        // Query data harga ikan segar per bulan
        $query = HargaIkanSegar::selectRaw('MONTH(tanggal_input) as bulan, AVG(harga_konsumen) as rata_harga')
            ->whereNotNull('harga_konsumen')
            ->groupBy('bulan')
            ->orderBy('bulan');
        
        if ($tahun) {
            $query->whereYear('tanggal_input', $tahun);
        }
        
        $hargaPerBulan = $query->get();
        
        // Inisialisasi array untuk 12 bulan
        $dataBulan = array_fill(1, 12, 0);
        
        // Isi data yang ada
        foreach ($hargaPerBulan as $item) {
            $dataBulan[$item->bulan] = round($item->rata_harga, 0);
        }
        
        // Get available years
        $years = HargaIkanSegar::selectRaw('YEAR(tanggal_input) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        return view('pages.grafik.harga-ikan-segar', compact('dataBulan', 'years', 'tahun'));
    }

    public function pendataanWilayah(Request $request)
    {
        $tahun = $request->input('tahun');
        
        // Query jumlah pendataan per bulan (gabungan pembudidaya, pengolah, pemasar)
        $queryPembudidaya = Pembudidaya::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan');
        
        $queryPengolah = Pengolah::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan');
        
        $queryPemasar = Pemasar::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan');
        
        if ($tahun) {
            $queryPembudidaya->whereYear('created_at', $tahun);
            $queryPengolah->whereYear('created_at', $tahun);
            $queryPemasar->whereYear('created_at', $tahun);
        }
        
        $dataPembudidaya = $queryPembudidaya->get();
        $dataPengolah = $queryPengolah->get();
        $dataPemasar = $queryPemasar->get();
        
        // Inisialisasi array untuk 12 bulan
        $pembudidayaPerBulan = array_fill(1, 12, 0);
        $pengolahPerBulan = array_fill(1, 12, 0);
        $pemasarPerBulan = array_fill(1, 12, 0);
        
        // Isi data yang ada
        foreach ($dataPembudidaya as $item) {
            $pembudidayaPerBulan[$item->bulan] = $item->jumlah;
        }
        foreach ($dataPengolah as $item) {
            $pengolahPerBulan[$item->bulan] = $item->jumlah;
        }
        foreach ($dataPemasar as $item) {
            $pemasarPerBulan[$item->bulan] = $item->jumlah;
        }
        
        // Get available years from all tables
        $yearsPembudidaya = Pembudidaya::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->pluck('tahun');
        $yearsPengolah = Pengolah::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->pluck('tahun');
        $yearsPemasar = Pemasar::selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->pluck('tahun');
        
        $years = $yearsPembudidaya->merge($yearsPengolah)
            ->merge($yearsPemasar)
            ->unique()
            ->sort()
            ->values()
            ->reverse()
            ->toArray();
        
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        return view('pages.grafik.pendataan-wilayah', compact(
            'pembudidayaPerBulan', 
            'pengolahPerBulan', 
            'pemasarPerBulan', 
            'years', 
            'tahun'
        ));
    }
}
