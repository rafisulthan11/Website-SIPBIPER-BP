<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\PembudidayaProduksi;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;

class GrafikController extends Controller
{
    public function pelakuUsaha(Request $request)
    {
        $tahun = $request->input('tahun');
        
        // Query untuk hitung jumlah pelaku usaha per kategori
        $queryPembudidaya = Pembudidaya::query();
        $queryPengolah = Pengolah::query();
        $queryPemasar = Pemasar::query();
        
        if ($tahun) {
            $queryPembudidaya->whereYear('created_at', $tahun);
            $queryPengolah->whereYear('created_at', $tahun);
            $queryPemasar->whereYear('created_at', $tahun);
        }
        
        $pembudidayaCount = $queryPembudidaya->count();
        $pengolahCount = $queryPengolah->count();
        $pemasarCount = $queryPemasar->count();
        
        $data = [
            'pembudidaya' => $pembudidayaCount,
            'pengolah' => $pengolahCount,
            'pemasar' => $pemasarCount,
            'total' => $pembudidayaCount + $pengolahCount + $pemasarCount
        ];
        
        // Data per kecamatan (dipisah per tipe)
        $tahunFilter = $tahun ? "AND YEAR(pembudidayas.created_at) = $tahun" : '';
        $tahunFilterPengolah = $tahun ? "AND YEAR(pengolahs.created_at) = $tahun" : '';
        $tahunFilterPemasar = $tahun ? "AND YEAR(pemasars.created_at) = $tahun" : '';
        
        $kecamatanData = \DB::table('master_kecamatans')
            ->select(
                'master_kecamatans.nama_kecamatan',
                \DB::raw("(SELECT COUNT(*) FROM pembudidayas WHERE pembudidayas.id_kecamatan = master_kecamatans.id_kecamatan $tahunFilter) as pembudidaya"),
                \DB::raw("(SELECT COUNT(*) FROM pengolahs WHERE pengolahs.id_kecamatan = master_kecamatans.id_kecamatan $tahunFilterPengolah) as pengolah"),
                \DB::raw("(SELECT COUNT(*) FROM pemasars WHERE pemasars.id_kecamatan = master_kecamatans.id_kecamatan $tahunFilterPemasar) as pemasar")
            )
            ->orderBy('master_kecamatans.nama_kecamatan')
            ->get();
        
        // Data komoditas teratas dari pembudidaya (berdasarkan jenis_ikan dan jumlah)
        $komoditasQuery = \DB::table('pembudidaya_ikans')
            ->join('pembudidayas', 'pembudidaya_ikans.id_pembudidaya', '=', 'pembudidayas.id_pembudidaya')
            ->select('pembudidaya_ikans.jenis_ikan', \DB::raw('SUM(pembudidaya_ikans.jumlah) as total_jumlah'))
            ->whereNotNull('pembudidaya_ikans.jumlah');
        
        if ($tahun) {
            $komoditasQuery->whereYear('pembudidayas.created_at', $tahun);
        }
        
        $komoditasData = $komoditasQuery
            ->groupBy('pembudidaya_ikans.jenis_ikan')
            ->orderByDesc('total_jumlah')
            ->limit(5)
            ->get();
        
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
        
        return view('pages.grafik.pelaku-usaha', compact('data', 'kecamatanData', 'komoditasData', 'years', 'tahun'));
    }

    public function hargaIkanSegar(Request $request)
    {
        $tahun = $request->input('tahun');
        $jenisIkan = $request->input('jenis_ikan');
        $kecamatan = $request->input('kecamatan');
        $pasar = $request->input('pasar');
        
        // Base query with filters
        $baseQuery = HargaIkanSegar::whereNotNull('harga_konsumen');
        
        if ($tahun) {
            $baseQuery->whereYear('tanggal_input', $tahun);
        }
        if ($jenisIkan) {
            $baseQuery->where('jenis_ikan', $jenisIkan);
        }
        if ($kecamatan) {
            $baseQuery->where('id_kecamatan', $kecamatan);
        }
        if ($pasar) {
            $baseQuery->where('nama_pasar', $pasar);
        }
        
        // Query data harga ikan segar per bulan (Harga Konsumen)
        $queryKonsumen = clone $baseQuery;
        $hargaKonsumenPerBulan = $queryKonsumen
            ->selectRaw('MONTH(tanggal_input) as bulan, AVG(harga_konsumen) as rata_harga')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Query data harga ikan segar per bulan (Harga Produsen)
        $queryProdusen = clone $baseQuery;
        $hargaProdusenPerBulan = $queryProdusen
            ->whereNotNull('harga_produsen')
            ->selectRaw('MONTH(tanggal_input) as bulan, AVG(harga_produsen) as rata_harga')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Inisialisasi array untuk 12 bulan
        $dataBulanKonsumen = array_fill(1, 12, 0);
        $dataBulanProdusen = array_fill(1, 12, 0);
        
        // Isi data yang ada
        foreach ($hargaKonsumenPerBulan as $item) {
            $dataBulanKonsumen[$item->bulan] = round($item->rata_harga, 0);
        }
        
        foreach ($hargaProdusenPerBulan as $item) {
            $dataBulanProdusen[$item->bulan] = round($item->rata_harga, 0);
        }
        
        // Statistik ringkasan
        $queryStats = clone $baseQuery;
        $statistics = [
            'total_data' => $queryStats->count(),
            'rata_harga_konsumen' => round($queryStats->avg('harga_konsumen'), 0),
            'rata_harga_produsen' => round($queryStats->avg('harga_produsen'), 0),
            'harga_tertinggi' => round($queryStats->max('harga_konsumen'), 0),
            'harga_terendah' => round($queryStats->where('harga_konsumen', '>', 0)->min('harga_konsumen'), 0),
        ];
        
        // Hitung margin (selisih harga konsumen - produsen)
        if ($statistics['rata_harga_produsen'] > 0) {
            $statistics['margin'] = $statistics['rata_harga_konsumen'] - $statistics['rata_harga_produsen'];
            $statistics['margin_persen'] = round(($statistics['margin'] / $statistics['rata_harga_produsen']) * 100, 1);
        } else {
            $statistics['margin'] = 0;
            $statistics['margin_persen'] = 0;
        }
        
        // Data untuk filter dropdown
        $years = HargaIkanSegar::selectRaw('YEAR(tanggal_input) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();
        
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        // Get distinct jenis ikan
        $jenisIkanList = HargaIkanSegar::select('jenis_ikan')
            ->distinct()
            ->whereNotNull('jenis_ikan')
            ->where('jenis_ikan', '!=', '')
            ->orderBy('jenis_ikan')
            ->pluck('jenis_ikan')
            ->toArray();
        
        // Get kecamatan list
        $kecamatanList = \App\Models\MasterKecamatan::orderBy('nama_kecamatan')->get();
        
        // Get distinct pasar
        $pasarList = HargaIkanSegar::select('nama_pasar')
            ->distinct()
            ->whereNotNull('nama_pasar')
            ->where('nama_pasar', '!=', '')
            ->orderBy('nama_pasar')
            ->pluck('nama_pasar')
            ->toArray();
        
        return view('pages.grafik.harga-ikan-segar', compact(
            'dataBulanKonsumen', 
            'dataBulanProdusen', 
            'years', 
            'tahun', 
            'jenisIkanList', 
            'jenisIkan', 
            'kecamatanList', 
            'kecamatan', 
            'pasarList', 
            'pasar',
            'statistics'
        ));
    }

    public function produksiIkan(Request $request)
    {
        $tahun = $request->input('tahun');
        $kecamatan = $request->input('kecamatan');
        $komoditas = $request->input('komoditas');
        
        // Mapping nama bulan ke angka
        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];
        
        // PRODUKSI PEMBUDIDAYA
        // Base query untuk pembudidaya - gunakan kolom 'bulan' dengan nama bulan
        $queryPembudidaya = PembudidayaProduksi::selectRaw('bulan, SUM(total_produksi) as total')
            ->where('total_produksi', '>', 0) // Hanya ambil yang ada produksinya
            ->groupBy('bulan');
        
        // Filter by tahun via pembudidaya.tahun_pendataan karena field tahun di produksi kosong
        if ($tahun) {
            $queryPembudidaya->whereHas('pembudidaya', function($q) use ($tahun) {
                $q->where('tahun_pendataan', $tahun);
            });
        }
        
        // Filter by kecamatan (via pembudidaya relation)
        if ($kecamatan) {
            $queryPembudidaya->whereHas('pembudidaya', function($q) use ($kecamatan) {
                $q->where('id_kecamatan', $kecamatan);
            });
        }
        
        // Filter by komoditas (via pembudidaya->ikan)
        if ($komoditas) {
            $queryPembudidaya->whereHas('pembudidaya.ikan', function($q) use ($komoditas) {
                $q->where('jenis_ikan', $komoditas);
            });
        }
        
        $produksiPembudidayaPerBulan = $queryPembudidaya->get();
        
        // PRODUKSI PENGOLAH
        // Query pengolah dengan produksi_data
        $queryPengolah = Pengolah::whereNotNull('produksi_data');
        
        if ($tahun) {
            $queryPengolah->where('tahun_pendataan', $tahun);
        }
        
        if ($kecamatan) {
            $queryPengolah->where('id_kecamatan', $kecamatan);
        }
        
        if ($komoditas) {
            $queryPengolah->where('komoditas', 'LIKE', '%' . $komoditas . '%');
        }
        
        $pengolahData = $queryPengolah->get();
        
        // Inisialisasi array untuk 12 bulan
        $pembudidayaPerBulan = array_fill(1, 12, 0);
        $pengolahPerBulan = array_fill(1, 12, 0);
        
        // Isi data pembudidaya - konversi nama bulan ke angka
        foreach ($produksiPembudidayaPerBulan as $item) {
            if ($item->bulan && isset($bulanMap[$item->bulan])) {
                $bulanAngka = $bulanMap[$item->bulan];
                $pembudidayaPerBulan[$bulanAngka] = round($item->total, 2);
            }
        }
        
        // Hitung produksi pengolah dari JSON produksi_data
        // Struktur form pengolah menyimpan hasil produksi pada key `harga_produksi_qty`
        // dan bulan berupa nama bulan (contoh: Januari, Februari)
        foreach ($pengolahData as $pengolah) {
            if (!is_array($pengolah->produksi_data)) {
                continue;
            }

            foreach ($pengolah->produksi_data as $produksi) {
                if (!is_array($produksi)) {
                    continue;
                }

                $hasilProduksi = floatval($produksi['harga_produksi_qty'] ?? $produksi['hasil_produksi'] ?? 0);
                if ($hasilProduksi <= 0 || !isset($produksi['bulan_produksi'])) {
                    continue;
                }

                $bulanProduksi = $produksi['bulan_produksi'];
                if (!is_array($bulanProduksi)) {
                    $bulanProduksi = [$bulanProduksi];
                }

                $bulanValid = [];
                foreach ($bulanProduksi as $bulan) {
                    // Format angka bulan (1-12)
                    if (is_numeric($bulan) && $bulan >= 1 && $bulan <= 12) {
                        $bulanValid[] = (int) $bulan;
                        continue;
                    }

                    // Format nama bulan (Januari, Februari, dst)
                    if (is_string($bulan)) {
                        $namaBulan = trim($bulan);
                        if (isset($bulanMap[$namaBulan])) {
                            $bulanValid[] = $bulanMap[$namaBulan];
                        }
                    }
                }

                $bulanValid = array_values(array_unique($bulanValid));
                $bulanCount = count($bulanValid);
                if ($bulanCount === 0) {
                    continue;
                }

                $produksiPerBulan = $hasilProduksi / $bulanCount;
                foreach ($bulanValid as $bulanAngka) {
                    $pengolahPerBulan[$bulanAngka] += $produksiPerBulan;
                }
            }
        }
        
        // Round nilai pengolah
        foreach ($pengolahPerBulan as $bulan => $nilai) {
            $pengolahPerBulan[$bulan] = round($nilai, 2);
        }
        
        // STATISTIK RINGKASAN
        $statistics = [
            'total_produksi_pembudidaya' => array_sum($pembudidayaPerBulan),
            'total_produksi_pengolah' => array_sum($pengolahPerBulan),
            'total_produksi_keseluruhan' => array_sum($pembudidayaPerBulan) + array_sum($pengolahPerBulan),
            'jumlah_pembudidaya' => PembudidayaProduksi::where('total_produksi', '>', 0)
                ->when($tahun, function($q) use ($tahun) {
                    return $q->whereHas('pembudidaya', function($query) use ($tahun) {
                        $query->where('tahun_pendataan', $tahun);
                    });
                })
                ->when($kecamatan, function($q) use ($kecamatan) {
                    return $q->whereHas('pembudidaya', function($query) use ($kecamatan) {
                        $query->where('id_kecamatan', $kecamatan);
                    });
                })
                ->distinct('id_pembudidaya')
                ->count('id_pembudidaya'),
            'jumlah_pengolah' => Pengolah::whereNotNull('produksi_data')
                ->when($tahun, function($q) use ($tahun) {
                    return $q->where('tahun_pendataan', $tahun);
                })
                ->when($kecamatan, function($q) use ($kecamatan) {
                    return $q->where('id_kecamatan', $kecamatan);
                })
                ->count(),
        ];
        
        // Rata-rata produksi per bulan
        $bulanDenganData = count(array_filter(array_merge($pembudidayaPerBulan, $pengolahPerBulan)));
        $statistics['rata_rata_per_bulan'] = $bulanDenganData > 0 
            ? round($statistics['total_produksi_keseluruhan'] / 12, 2) 
            : 0;
        
        // Bulan dengan produksi tertinggi dan terendah
        $totalPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $totalPerBulan[$i] = $pembudidayaPerBulan[$i] + $pengolahPerBulan[$i];
        }
        
        // Handle max dengan validasi
        if (count($totalPerBulan) > 0 && max($totalPerBulan) > 0) {
            $statistics['bulan_tertinggi'] = array_search(max($totalPerBulan), $totalPerBulan);
            $statistics['produksi_tertinggi'] = max($totalPerBulan);
        } else {
            $statistics['bulan_tertinggi'] = 1;
            $statistics['produksi_tertinggi'] = 0;
        }
        
        // Handle min dengan validasi array tidak kosong
        $filteredTotal = array_filter($totalPerBulan);
        if (count($filteredTotal) > 0) {
            $statistics['bulan_terendah'] = array_search(min($filteredTotal), $filteredTotal);
            $statistics['produksi_terendah'] = min($filteredTotal);
        } else {
            $statistics['bulan_terendah'] = 1;
            $statistics['produksi_terendah'] = 0;
        }
        
        // Data untuk filter dropdown
        // Get available years dari pembudidaya.tahun_pendataan karena field tahun di pembudidaya_produksis kosong
        $yearsPembudidaya = Pembudidaya::select('tahun_pendataan')
            ->distinct()
            ->whereNotNull('tahun_pendataan')
            ->orderBy('tahun_pendataan', 'desc')
            ->pluck('tahun_pendataan');
        
        $yearsPengolah = Pengolah::select('tahun_pendataan')
            ->distinct()
            ->whereNotNull('tahun_pendataan')
            ->orderBy('tahun_pendataan', 'desc')
            ->pluck('tahun_pendataan');
        
        $years = $yearsPembudidaya->merge($yearsPengolah)
            ->unique()
            ->sort()
            ->values()
            ->reverse()
            ->toArray();
        
        if (empty($years)) {
            $years = [date('Y')];
        }
        
        // Get kecamatan list
        $kecamatanList = \App\Models\MasterKecamatan::orderBy('nama_kecamatan')->get();
        
        // Get komoditas list from master data (tipe pembudidaya + pengolah), unique by kode
        $komoditasList = \App\Models\Komoditas::whereIn('tipe', ['pembudidaya', 'pengolah'])
            ->orderBy('nama_komoditas')
            ->get()
            ->unique('kode')
            ->pluck('nama_komoditas')
            ->filter()
            ->values()
            ->toArray();
        
        // Bulan names untuk display
        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return view('pages.grafik.produksi-ikan', compact(
            'pembudidayaPerBulan',
            'pengolahPerBulan',
            'years',
            'tahun',
            'kecamatanList',
            'kecamatan',
            'komoditasList',
            'komoditas',
            'statistics',
            'bulanNames'
        ));
    }
}
