<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\Pengolah;
use App\Models\Pemasar;
use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;
use App\Models\Komoditas;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PembudidayaExport;
use App\Exports\PengolahExport;
use App\Exports\PemasarExport;
use App\Exports\RekapitulasiPembudidayaExport;
use App\Exports\RekapitulasiPengolahExport;
use App\Exports\RekapitulasiPemasarExport;
use App\Exports\HargaIkanSegarExport;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Show rekapitulasi pembudidaya with filters and pagination.
     */
    public function rekapitulasiPembudidaya(Request $request)
    {
        // Query data verified
        $query = Pembudidaya::with(['kecamatan','desa','ikan'])
            ->where('status', 'verified');

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->whereHas('ikan', function($q) use ($request) {
                $q->where('jenis_ikan', $request->komoditas);
            });
        }

        if ($request->filled('kategori')) {
            $query->where('jenis_kegiatan_usaha', $request->kategori);
        }

        // Filter berdasarkan tahun pendataan
        if ($request->filled('tahun')) {
            $query->where('tahun_pendataan', (int) $request->tahun);
        }

        // Filter pembudidaya yang memiliki produksi di bulan tertentu
        if ($request->filled('bulan')) {
            $query->whereHas('produksi', function($q) use ($request) {
                $q->where('bulan', $request->bulan);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        // Ambil data backup untuk data yang sedang pending edit
        $backupData = DB::table('pembudidaya_verified_backup as backup')
            ->join('pembudidayas as current', 'current.id_pembudidaya', '=', 'backup.id_pembudidaya')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Convert array to Pembudidaya model instance
                    $pembudidaya = new Pembudidaya();
                    
                    // Separate relationships from attributes
                    $relationships = [];
                    if (isset($data['ikan'])) {
                        $relationships['ikan'] = $data['ikan'];
                        unset($data['ikan']);
                    }
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    if (isset($data['produksi'])) {
                        $relationships['produksi'] = $data['produksi'];
                        unset($data['produksi']);
                    }
                    if (isset($data['kolam'])) {
                        $relationships['kolam'] = $data['kolam'];
                        unset($data['kolam']);
                    }
                    
                    $pembudidaya->forceFill($data);
                    $pembudidaya->exists = true;
                    $pembudidaya->setAttribute('from_backup_snapshot', true);
                    
                    // Set relationships as Collections
                    if (isset($relationships['ikan'])) {
                        $pembudidaya->setRelation('ikan', collect($relationships['ikan']));
                    }
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pembudidaya->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pembudidaya->setRelation('desa', $desa);
                    }
                    if (isset($relationships['produksi'])) {
                        $pembudidaya->setRelation('produksi', collect($relationships['produksi']));
                    }
                    if (isset($relationships['kolam'])) {
                        $pembudidaya->setRelation('kolam', collect($relationships['kolam']));
                    }
                    
                    return $pembudidaya;
                }
                return null;
            })
            ->filter(function ($item) use ($request) {
                if (!$item) {
                    return false;
                }

                if ($request->filled('kecamatan') && (string) ($item->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                    return false;
                }

                if ($request->filled('kategori') && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $request->kategori) {
                    return false;
                }

                if ($request->filled('tahun') && (int) ($item->tahun_pendataan ?? 0) !== (int) $request->tahun) {
                    return false;
                }

                if ($request->filled('search')) {
                    $search = strtolower((string) $request->search);
                    $haystack = strtolower((string) ($item->nama_lengkap ?? '')) . ' ' . strtolower((string) ($item->nama_usaha ?? '')) . ' ' . strtolower((string) ($item->jenis_kegiatan_usaha ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                if ($request->filled('komoditas')) {
                    $ikanRows = collect($item->ikan ?? []);
                    $hasKomoditas = $ikanRows->contains(function ($ikan) use ($request) {
                        $jenis = is_array($ikan) ? ($ikan['jenis_ikan'] ?? null) : ($ikan->jenis_ikan ?? null);
                        return (string) $jenis === (string) $request->komoditas;
                    });
                    if (!$hasKomoditas) {
                        return false;
                    }
                }

                if ($request->filled('bulan')) {
                    $produksiRows = collect($item->produksi ?? []);
                    $hasBulan = $produksiRows->contains(function ($row) use ($request) {
                        $bulan = is_array($row) ? ($row['bulan'] ?? null) : ($row->bulan ?? null);
                        return (string) $bulan === (string) $request->bulan;
                    });
                    if (!$hasBulan) {
                        return false;
                    }
                }

                return true;
            })
            ->filter();

        $perPage = (int) $request->input('per_page', 10);
        
        // Get verified data
        $verifiedPembudidayas = $query->get();
        
        // Merge with backup data
        $allPembudidayas = $verifiedPembudidayas->keyBy('id_pembudidaya');
        foreach ($backupData as $backupItem) {
            $allPembudidayas[$backupItem->id_pembudidaya] = $backupItem;
        }
        $allPembudidayas = $allPembudidayas->values();
        
        // Hitung total produksi keseluruhan
        $totalProduksiKeseluruhan = 0;
        $totalLuasKolam = 0;

        foreach ($allPembudidayas as $pembudidayaItem) {
            $isBackup = (bool) ($pembudidayaItem->from_backup_snapshot ?? false);

            if ($isBackup && $pembudidayaItem->relationLoaded('produksi')) {
                $produksiRows = collect($pembudidayaItem->produksi ?? []);
                if ($request->filled('bulan')) {
                    $produksiRows = $produksiRows->filter(function ($row) use ($request) {
                        $bulan = is_array($row) ? ($row['bulan'] ?? null) : ($row->bulan ?? null);
                        return $bulan == $request->bulan;
                    });
                }

                $totalProduksiKeseluruhan += $produksiRows->sum(function ($row) {
                    $produksi = floatval(is_array($row) ? ($row['total_produksi'] ?? 0) : ($row->total_produksi ?? 0));
                    $satuan = strtolower(is_array($row) ? ($row['satuan_produksi'] ?? 'kg') : ($row->satuan_produksi ?? 'kg'));
                    return str_contains($satuan, 'ton') ? $produksi * 1000 : $produksi;
                });

                $totalLuasKolam += $produksiRows->sum(function ($row) {
                    return floatval(is_array($row) ? ($row['total_luas_kolam'] ?? 0) : ($row->total_luas_kolam ?? 0));
                });

                continue;
            }

            $produksiRows = DB::table('pembudidaya_produksis')
                ->select('total_produksi', 'satuan_produksi', 'total_luas_kolam')
                ->where('id_pembudidaya', $pembudidayaItem->id_pembudidaya);

            if ($request->filled('bulan')) {
                $produksiRows->where('bulan', $request->bulan);
            }

            $rows = $produksiRows->get();

            $totalProduksiKeseluruhan += $rows->sum(function ($row) {
                $produksi = floatval($row->total_produksi ?? 0);
                $satuan = strtolower($row->satuan_produksi ?? 'kg');
                return str_contains($satuan, 'ton') ? $produksi * 1000 : $produksi;
            });

            $totalLuasKolam += $rows->sum('total_luas_kolam');
        }
        
        // Konversi satuan untuk display: jika >= 1000 kg, ubah ke ton
        if ($totalProduksiKeseluruhan >= 1000) {
            $totalProduksiDisplay = $totalProduksiKeseluruhan / 1000;
            $satuanProduksi = 'Ton';
        } else {
            $totalProduksiDisplay = $totalProduksiKeseluruhan;
            $satuanProduksi = 'Kg';
        }
        
        // Sort collection by nama_lengkap
        $allPembudidayas = $allPembudidayas->sortBy('nama_lengkap')->values();
        
        // Manual pagination for collection
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $allPembudidayas->slice($offset, $perPage)->values();
        
        $pembudidayas = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $allPembudidayas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Tambahkan total luas kolam dan total produksi untuk setiap pembudidaya
        $pembudidayas->getCollection()->transform(function($pembudidaya) use ($request) {
            $isBackup = (bool) ($pembudidaya->from_backup_snapshot ?? false);

            if ($isBackup && $pembudidaya->relationLoaded('produksi')) {
                $records = collect($pembudidaya->produksi ?? []);
                if ($request->filled('bulan')) {
                    $records = $records->filter(function ($row) use ($request) {
                        $bulan = is_array($row) ? ($row['bulan'] ?? null) : ($row->bulan ?? null);
                        return $bulan == $request->bulan;
                    });
                }
            } else {
                $produksiRecords = DB::table('pembudidaya_produksis')
                    ->select('total_produksi', 'satuan_produksi', 'total_luas_kolam')
                    ->where('id_pembudidaya', $pembudidaya->id_pembudidaya);

                if ($request->filled('bulan')) {
                    $produksiRecords->where('bulan', $request->bulan);
                }

                $records = $produksiRecords->get();
            }
            
            // Hitung total produksi dalam Kg dengan konversi satuan
            $totalProduksiKg = $records->sum(function($record) {
                $produksi = is_array($record) ? ($record['total_produksi'] ?? 0) : ($record->total_produksi ?? 0);
                $satuan = strtolower(is_array($record) ? ($record['satuan_produksi'] ?? 'kg') : ($record->satuan_produksi ?? 'kg'));
                
                // Konversi ke Kg jika satuannya Ton
                if (str_contains($satuan, 'ton')) {
                    return $produksi * 1000; // Ton ke Kg
                }
                
                return $produksi; // Sudah dalam Kg
            });
            
            $pembudidaya->total_luas_kolam_pembudidaya = $records->sum(function($record) {
                return floatval(is_array($record) ? ($record['total_luas_kolam'] ?? 0) : ($record->total_luas_kolam ?? 0));
            });
            
            // Konversi display: jika >= 1000 Kg, ubah ke Ton untuk display
            if ($totalProduksiKg >= 1000) {
                $pembudidaya->total_produksi_pembudidaya = $totalProduksiKg / 1000;
                $pembudidaya->satuan_produksi = 'Ton';
            } else {
                $pembudidaya->total_produksi_pembudidaya = $totalProduksiKg;
                $pembudidaya->satuan_produksi = 'Kg';
            }
            
            return $pembudidaya;
        });

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter (tipe pembudidaya, aktif & tidak aktif)
        // Hanya tampilkan komoditas yang ada data pembudidaya-nya
        $komoditasAktif = Komoditas::where('tipe', 'pembudidaya')
            ->pluck('nama_komoditas')
            ->unique()
            ->values();
        
        $komoditasDipakai = \DB::table('pembudidaya_ikans')
            ->distinct()
            ->pluck('jenis_ikan')
            ->filter()
            ->unique()
            ->values();
        
        // Gabungkan dan urutkan
        $komoditas = $komoditasAktif->merge($komoditasDipakai)
            ->unique()
            ->sort()
            ->values();

        // collect kategori (jenis_kegiatan_usaha) distinct values for filter
        $kategoris = ['Pembenihan/Pembibitan', 'Pembesaran', 'Tambak'];

        // Hitung unique RTP berdasarkan NIK (jika ada data dengan NIK sama dan tahun berbeda, hitung 1 saja)
        $uniqueRTP = $allPembudidayas->pluck('nik_pembudidaya')->filter()->unique()->count();

        return view('pages.laporan.rekapitulasi-pembudidaya', compact('pembudidayas','kecamatans','komoditas','kategoris','totalProduksiKeseluruhan','totalProduksiDisplay','satuanProduksi','totalLuasKolam','uniqueRTP'));
    }

    /**
     * Rekapitulasi Pengolah.
     */
    public function rekapitulasiPengolah(Request $request)
    {
        // Query data verified
        $query = Pengolah::with(['kecamatan','desa'])
            ->where('status', 'verified');

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', 'like', '%'.$request->komoditas.'%');
        }

        if ($request->filled('kategori')) {
            $query->where('skala_usaha', $request->kategori);
        }

        if ($request->filled('jenis_kegiatan_usaha')) {
            $query->where('jenis_kegiatan_usaha', $request->jenis_kegiatan_usaha);
        }

        // Filter berdasarkan tahun pendataan
        if ($request->filled('tahun')) {
            $query->where('tahun_pendataan', (int) $request->tahun);
        }

        // Jangan filter di query, biarkan semua pengolah dimuat
        // Filter akan dilakukan di level PHP saat menghitung produksi

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        // Ambil data backup untuk data yang sedang pending edit
        $backupData = DB::table('pengolah_verified_backup as backup')
            ->join('pengolahs as current', 'current.id_pengolah', '=', 'backup.id_pengolah')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $pengolah = new Pengolah();
                    
                    // Separate relationships from attributes
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    
                    $pengolah->forceFill($data);
                    $pengolah->exists = true;
                    
                    // Set relationships as model instances
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pengolah->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pengolah->setRelation('desa', $desa);
                    }
                    
                    return $pengolah;
                }
                return null;
            })
            ->filter(function ($item) use ($request) {
                if (!$item) {
                    return false;
                }

                if ($request->filled('kecamatan') && (string) ($item->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                    return false;
                }
                if ($request->filled('komoditas') && !str_contains(strtolower((string) ($item->komoditas ?? '')), strtolower((string) $request->komoditas))) {
                    return false;
                }
                if ($request->filled('kategori') && (string) ($item->skala_usaha ?? '') !== (string) $request->kategori) {
                    return false;
                }
                if ($request->filled('jenis_kegiatan_usaha') && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $request->jenis_kegiatan_usaha) {
                    return false;
                }
                if ($request->filled('tahun') && (int) ($item->tahun_pendataan ?? 0) !== (int) $request->tahun) {
                    return false;
                }
                if ($request->filled('search')) {
                    $search = strtolower((string) $request->search);
                    $haystack = strtolower((string) ($item->nama_lengkap ?? '')) . ' ' . strtolower((string) ($item->nama_usaha ?? '')) . ' ' . strtolower((string) ($item->jenis_kegiatan_usaha ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                return true;
            })
            ->filter();

        // Get verified data
        $verifiedPengolahs = $query->get();
        
        // Merge with backup data
        $allPengolahs = $verifiedPengolahs->keyBy('id_pengolah');
        foreach ($backupData as $backupItem) {
            $allPengolahs[$backupItem->id_pengolah] = $backupItem;
        }
        $allPengolahs = $allPengolahs->values();
        
        // Tambahkan total produksi untuk setiap pengolah
        $allPengolahs->transform(function($pengolah) use ($request) {
            $totalProduksiKg = 0;
            
            if (!empty($pengolah->produksi_data)) {
                $produksiArray = is_string($pengolah->produksi_data) 
                    ? json_decode($pengolah->produksi_data, true) 
                    : $pengolah->produksi_data;
                
                if (is_array($produksiArray)) {
                    foreach ($produksiArray as $produk) {
                        // Filter berdasarkan bulan
                        if ($request->filled('bulan')) {
                            $bulanProduksi = $produk['bulan_produksi'] ?? [];
                            // Cari nama bulan langsung (tidak di-convert ke angka)
                            if (!in_array($request->bulan, $bulanProduksi)) {
                                continue;
                            }
                        }
                        
                        $hasilProduksiKg = floatval($produk['harga_produksi_qty'] ?? $produk['hasil_produksi_qty'] ?? 0);
                        $totalProduksiKg += $hasilProduksiKg;
                    }
                }
            }
            
            // Konversi display: jika >= 1000 Kg, ubah ke Ton untuk display
            if ($totalProduksiKg >= 1000) {
                $pengolah->total_produksi_pengolah = $totalProduksiKg / 1000;
                $pengolah->satuan_produksi = 'Ton';
            } else {
                $pengolah->total_produksi_pengolah = $totalProduksiKg;
                $pengolah->satuan_produksi = 'Kg';
            }
            
            // Tandai apakah pengolah memiliki produksi
            $pengolah->has_produksi = $totalProduksiKg > 0;
            
            return $pengolah;
        });
        
        // Filter pengolah yang tidak memiliki produksi jika ada filter bulan
        if ($request->filled('bulan')) {
            $allPengolahs = $allPengolahs->filter(function($pengolah) {
                return $pengolah->has_produksi;
            })->values();
        }

        $totalProduksiKeseluruhan = $allPengolahs->sum(function ($pengolah) {
            return floatval($pengolah->total_produksi_pengolah ?? 0);
        });

        if ($totalProduksiKeseluruhan >= 1000) {
            $totalProduksiDisplay = $totalProduksiKeseluruhan / 1000;
            $satuanProduksi = 'Ton';
        } else {
            $totalProduksiDisplay = $totalProduksiKeseluruhan;
            $satuanProduksi = 'Kg';
        }

        $perPage = (int) $request->input('per_page', 10);
        
        // Sort collection by nama_lengkap
        $allPengolahs = $allPengolahs->sortBy('nama_lengkap')->values();
        
        // Manual pagination for collection
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $allPengolahs->slice($offset, $perPage)->values();
        
        $pengolahs = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $allPengolahs->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter
        $komoditas = Komoditas::where('status', 'aktif')
            ->orderBy('nama_komoditas')
            ->pluck('nama_komoditas');

        // collect kategori (jenis_pengolahan) distinct values for filter
        $kategoris = ['Mikro', 'Kecil', 'Menengah', 'Besar'];
        
        // collect jenis_kegiatan_usaha distinct values for filter
        $jenis_kegiatan_usaha_list = ['Pengalengan', 'Pembekuan', 'Penggaraman/Pengeringan', 'Pemindangan', 'Pengasapan/Pemanggangan', 'Fermentasi/Peragian', 'Pereduksian/Ekstraksi', 'Pelumatan Daging/Surimi'];

        // Hitung unique RTP berdasarkan NIK (jika ada data dengan NIK sama dan tahun berbeda, hitung 1 saja)
        $uniqueRTP = $allPengolahs->pluck('nik_pengolah')->filter()->unique()->count();

        return view('pages.laporan.rekapitulasi-pengolah', compact('pengolahs','kecamatans','komoditas','kategoris','jenis_kegiatan_usaha_list','totalProduksiDisplay','satuanProduksi','uniqueRTP'));
    }

    /**
     * Rekapitulasi Pelaku Usaha (gabungan Pembudidaya, Pengolah, Pemasar).
     */
    public function rekapitulasiPelakuUsaha(Request $request)
    {
        // Ambil data dari ketiga tabel
        $pembudidayaQuery = Pembudidaya::with(['kecamatan','desa'])
            ->where('status', 'verified')
            ->select('pembudidayas.id_pembudidaya as id', 'pembudidayas.nama_lengkap', 'pembudidayas.nama_usaha', 'pembudidayas.id_kecamatan', 'pembudidayas.id_desa', 'pembudidayas.jenis_kegiatan_usaha')
            ->addSelect(DB::raw("'Pembudidaya' as tipe_pelaku"));
        
        $pengolahQuery = Pengolah::with(['kecamatan','desa'])
            ->where('status', 'verified')
            ->select('pengolahs.id_pengolah as id', 'pengolahs.nama_lengkap', 'pengolahs.nama_usaha', 'pengolahs.id_kecamatan', 'pengolahs.id_desa', 'pengolahs.jenis_kegiatan_usaha', 'pengolahs.produksi_data')
            ->addSelect(DB::raw("'Pengolah' as tipe_pelaku"));
        
        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $pembudidayaQuery->where('id_kecamatan', $request->kecamatan);
            $pengolahQuery->where('id_kecamatan', $request->kecamatan);
        }

        // Filter berdasarkan tahun pendataan
        if ($request->filled('tahun')) {
            $pembudidayaQuery->where('tahun_pendataan', (int) $request->tahun);
            $pengolahQuery->where('tahun_pendataan', (int) $request->tahun);
        }

        // Filter berdasarkan tipe pelaku
        $includePembudidaya = true;
        $includePengolah = true;
        
        if ($request->filled('tipe_pelaku')) {
            $includePembudidaya = $request->tipe_pelaku === 'Pembudidaya';
            $includePengolah = $request->tipe_pelaku === 'Pengolah';
        }

        // Gabungkan data dari ketiga query
        $pelakuUsaha = collect();
        
        if ($includePembudidaya) {
            $pembudidayaData = $pembudidayaQuery->get();
            // Hitung total produksi untuk setiap pembudidaya
            $pembudidayaData->each(function($item) use ($request) {
                $query = DB::table('pembudidaya_produksis')
                    ->where('id_pembudidaya', $item->id);
                
                // Filter bulan jika ada
                if ($request->filled('bulan')) {
                    $query->where('bulan', $request->bulan);
                }
                
                $item->total_produksi = $query->sum('total_produksi');
            });
            
            // Jika ada filter bulan, hanya tampilkan yang punya data produksi
            if ($request->filled('bulan')) {
                $pembudidayaData = $pembudidayaData->filter(function($item) {
                    return $item->total_produksi > 0;
                });
            }
            
            $pelakuUsaha = $pelakuUsaha->merge($pembudidayaData);
        }
        if ($includePengolah) {
            $pengolahData = $pengolahQuery->get();
            // Hitung total produksi untuk setiap pengolah dari JSON produksi_data
            $pengolahData->each(function($item) use ($request) {
                if ($item->produksi_data && is_array($item->produksi_data)) {
                    $totalProduksiKg = 0;
                    
                    foreach ($item->produksi_data as $produk) {
                        // Filter berdasarkan bulan
                        if ($request->filled('bulan')) {
                            $bulanProduksi = $produk['bulan_produksi'] ?? [];
                            // Cari nama bulan langsung (tidak di-convert ke angka)
                            if (!in_array($request->bulan, $bulanProduksi)) {
                                continue;
                            }
                        }
                        
                        $qty = floatval($produk['jumlah_produk_qty'] ?? 0);
                        $pack = intval($produk['jumlah_produk_pack'] ?? 0);
                        $totalProduksiKg += ($qty * $pack);
                    }
                    
                    $item->total_produksi = $totalProduksiKg;
                } else {
                    $item->total_produksi = 0;
                }
            });
            
            // Jika ada filter bulan, hanya tampilkan yang punya data produksi
            if ($request->filled('bulan')) {
                $pengolahData = $pengolahData->filter(function($item) {
                    return $item->total_produksi > 0;
                });
            }
            
            $pelakuUsaha = $pelakuUsaha->merge($pengolahData);
        }

        // Filter search (dilakukan setelah merge karena union tidak support orWhere dengan relasi)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $pelakuUsaha = $pelakuUsaha->filter(function($item) use ($search) {
                return str_contains(strtolower($item->nama_lengkap ?? ''), $search) ||
                       str_contains(strtolower($item->nama_usaha ?? ''), $search) ||
                       str_contains(strtolower($item->jenis_kegiatan_usaha ?? ''), $search);
            });
        }

        // Sort berdasarkan nama
        $pelakuUsaha = $pelakuUsaha->sortBy('nama_lengkap')->values();

        // Hitung total produksi keseluruhan sebelum pagination
        $totalProduksiKeseluruhan = $pelakuUsaha->sum('total_produksi');

        // Tambahkan data backup untuk pembudidaya dan pengolah
        $backupPembudidaya = DB::table('pembudidaya_verified_backup as backup')
            ->join('pembudidayas as current', 'current.id_pembudidaya', '=', 'backup.id_pembudidaya')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) use ($request) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Separate relationships
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    if (isset($data['ikan'])) {
                        $relationships['ikan'] = $data['ikan'];
                        unset($data['ikan']);
                    }
                    
                    $pembudidaya = new Pembudidaya();
                    $pembudidaya->forceFill($data);
                    $pembudidaya->exists = true;
                    $pembudidaya->tipe_pelaku = 'Pembudidaya';
                    $pembudidaya->id = $pembudidaya->id_pembudidaya;
                    
                    // Filter berdasarkan tahun pendataan
                    if ($request->filled('tahun') && isset($pembudidaya->tahun_pendataan)) {
                        if ($pembudidaya->tahun_pendataan != (int) $request->tahun) {
                            return null; // Skip data yang tidak matching tahun_pendataan
                        }
                    }
                    
                    // Set relationships
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pembudidaya->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pembudidaya->setRelation('desa', $desa);
                    }
                    if (isset($relationships['ikan'])) {
                        $pembudidaya->setRelation('ikan', collect($relationships['ikan']));
                    }
                    
                    // Hitung total produksi
                    $query = DB::table('pembudidaya_produksis')->where('id_pembudidaya', $pembudidaya->id);
                    if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
                    $pembudidaya->total_produksi = $query->sum('total_produksi');

                    if ($request->filled('kecamatan') && (string) ($pembudidaya->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                        return null;
                    }
                    
                    return $pembudidaya;
                }
                return null;
            })
            ->filter();

        $backupPengolah = DB::table('pengolah_verified_backup as backup')
            ->join('pengolahs as current', 'current.id_pengolah', '=', 'backup.id_pengolah')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) use ($request) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Separate relationships
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    
                    $pengolah = new Pengolah();
                    $pengolah->forceFill($data);
                    $pengolah->exists = true;
                    $pengolah->tipe_pelaku = 'Pengolah';
                    $pengolah->id = $pengolah->id_pengolah;
                    
                    // Filter berdasarkan tahun pendataan
                    if ($request->filled('tahun') && isset($pengolah->tahun_pendataan)) {
                        if ($pengolah->tahun_pendataan != (int) $request->tahun) {
                            return null; // Skip data yang tidak matching tahun_pendataan
                        }
                    }
                    
                    // Set relationships
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pengolah->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pengolah->setRelation('desa', $desa);
                    }
                    
                    // Hitung total produksi dari JSON
                    if ($pengolah->produksi_data && is_array($pengolah->produksi_data)) {
                        $totalProduksiKg = 0;
                        
                        foreach ($pengolah->produksi_data as $produk) {
                            // Filter berdasarkan bulan
                            if ($request->filled('bulan')) {
                                $bulanProduksi = $produk['bulan_produksi'] ?? [];
                                // Cari nama bulan langsung (tidak di-convert ke angka)
                                if (!in_array($request->bulan, $bulanProduksi)) {
                                    continue;
                                }
                            }
                            
                            $qty = floatval($produk['jumlah_produk_qty'] ?? 0);
                            $pack = intval($produk['jumlah_produk_pack'] ?? 0);
                            $totalProduksiKg += ($qty * $pack);
                        }
                        
                        $pengolah->total_produksi = $totalProduksiKg;
                    } else {
                        $pengolah->total_produksi = 0;
                    }

                    if ($request->filled('kecamatan') && (string) ($pengolah->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                        return null;
                    }
                    
                    return $pengolah;
                }
                return null;
            })
            ->filter();

        if (!$includePembudidaya) {
            $backupPembudidaya = collect();
        }
        if (!$includePengolah) {
            $backupPengolah = collect();
        }

        // Merge backup data
        $pelakuUsaha = $pelakuUsaha->merge($backupPembudidaya)->merge($backupPengolah);

        if ($request->filled('search')) {
            $search = strtolower((string) $request->search);
            $pelakuUsaha = $pelakuUsaha->filter(function ($item) use ($search) {
                return str_contains(strtolower((string) ($item->nama_lengkap ?? '')), $search) ||
                    str_contains(strtolower((string) ($item->nama_usaha ?? '')), $search) ||
                    str_contains(strtolower((string) ($item->jenis_kegiatan_usaha ?? '')), $search);
            })->values();
        }
        
        // Recalculate after merge
        $pelakuUsaha = $pelakuUsaha->sortBy('nama_lengkap')->values();
        $totalProduksiKeseluruhan = $pelakuUsaha->sum('total_produksi');

        // Manual pagination
        $perPage = (int) $request->input('per_page', 10);
        $currentPage = $request->input('page', 1);
        $total = $pelakuUsaha->count();
        $pelakuUsaha = $pelakuUsaha->forPage($currentPage, $perPage);

        $pelakuUsahaPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $pelakuUsaha,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();
        $tipePelakuOptions = ['Pembudidaya', 'Pengolah'];

        return view('pages.laporan.rekapitulasi-pelaku-usaha', compact('pelakuUsahaPaginated','kecamatans','tipePelakuOptions','totalProduksiKeseluruhan'));
    }

    /**
     * Export Rekapitulasi Pelaku Usaha ke Excel
     */
    public function exportRekapitulasiPelakuUsaha(Request $request)
    {
        // Ambil data dari ketiga tabel dengan semua field
        $pembudidayaQuery = Pembudidaya::with(['kecamatan','desa','kecamatanUsaha','desaUsaha'])
            ->where('status', 'verified')
            ->select('pembudidayas.*')
            ->selectRaw("id_pembudidaya as id")
            ->addSelect(DB::raw("'Pembudidaya' as tipe_pelaku"));
        
        $pengolahQuery = Pengolah::with(['kecamatan','desa','kecamatanUsaha','desaUsaha'])
            ->where('status', 'verified')
            ->select('pengolahs.*')
            ->selectRaw("id_pengolah as id")
            ->addSelect(DB::raw("'Pengolah' as tipe_pelaku"));
        
        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $pembudidayaQuery->where('id_kecamatan', $request->kecamatan);
            $pengolahQuery->where('id_kecamatan', $request->kecamatan);
        }

        // Filter berdasarkan tahun pendataan (untuk pembudidaya)
        if ($request->filled('tahun')) {
            $pembudidayaQuery->where('tahun_pendataan', (int) $request->tahun);
        }

        // Filter berdasarkan tipe pelaku
        $includePembudidaya = true;
        $includePengolah = true;
        
        if ($request->filled('tipe_pelaku')) {
            $includePembudidaya = $request->tipe_pelaku === 'Pembudidaya';
            $includePengolah = $request->tipe_pelaku === 'Pengolah';
        }

        // Gabungkan data dari ketiga query
        $pelakuUsaha = collect();
        
        if ($includePembudidaya) {
            $pembudidayaData = $pembudidayaQuery->get();
            // Hitung total produksi untuk setiap pembudidaya
            $pembudidayaData->each(function($item) use ($request) {
                $query = DB::table('pembudidaya_produksis')
                    ->where('id_pembudidaya', $item->id);
                
                // Filter bulan jika ada
                if ($request->filled('bulan')) {
                    $query->where('bulan', $request->bulan);
                }
                
                $item->total_produksi = $query->sum('total_produksi');
                
                // Ambil detail produksi untuk export
                $detailQuery = DB::table('pembudidaya_produksis')
                    ->where('id_pembudidaya', $item->id);
                    
                if ($request->filled('bulan')) {
                    $detailQuery->where('bulan', $request->bulan);
                }
                
                $item->detail_produksi = $detailQuery->get();
            });
            
            // Jika ada filter bulan, hanya tampilkan yang punya data produksi
            if ($request->filled('bulan')) {
                $pembudidayaData = $pembudidayaData->filter(function($item) {
                    return $item->total_produksi > 0;
                });
            }
            
            $pelakuUsaha = $pelakuUsaha->merge($pembudidayaData);
        }
        if ($includePengolah) {
            $pengolahData = $pengolahQuery->get();
            // Hitung total produksi untuk setiap pengolah dari JSON produksi_data
            $pengolahData->each(function($item) use ($request) {
                if ($item->produksi_data && is_array($item->produksi_data)) {
                    $produksiCollection = collect($item->produksi_data);
                    
                    // Filter bulan jika ada
                    if ($request->filled('bulan')) {
                        $produksiCollection = $produksiCollection->filter(function($prod) use ($request) {
                            $bulanProduksi = $prod['bulan_produksi'] ?? [];
                            return in_array($request->bulan, $bulanProduksi);
                        });
                    }
                    
                    $item->total_produksi = $produksiCollection->sum('jumlah_produk_qty');
                    $item->detail_produksi = $produksiCollection->values();
                } else {
                    $item->total_produksi = 0;
                    $item->detail_produksi = collect();
                }
            });
            
            // Jika ada filter bulan, hanya tampilkan yang punya data produksi
            if ($request->filled('bulan')) {
                $pengolahData = $pengolahData->filter(function($item) {
                    return $item->total_produksi > 0;
                });
            }
            
            $pelakuUsaha = $pelakuUsaha->merge($pengolahData);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $pelakuUsaha = $pelakuUsaha->filter(function($item) use ($search) {
                return str_contains(strtolower($item->nama_lengkap ?? ''), $search) ||
                       str_contains(strtolower($item->nama_usaha ?? ''), $search) ||
                       str_contains(strtolower($item->jenis_kegiatan_usaha ?? ''), $search);
            });
        }

        // Tambahkan data backup untuk pembudidaya dan pengolah untuk export
        $backupPembudidaya = DB::table('pembudidaya_verified_backup as backup')
            ->join('pembudidayas as current', 'current.id_pembudidaya', '=', 'backup.id_pembudidaya')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) use ($request) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Separate relationships
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    if (isset($data['ikan'])) {
                        $relationships['ikan'] = $data['ikan'];
                        unset($data['ikan']);
                    }
                    
                    $pembudidaya = new Pembudidaya();
                    $pembudidaya->forceFill($data);
                    $pembudidaya->exists = true;
                    $pembudidaya->tipe_pelaku = 'Pembudidaya';
                    $pembudidaya->id = $pembudidaya->id_pembudidaya;
                    
                    // Set relationships
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pembudidaya->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pembudidaya->setRelation('desa', $desa);
                    }
                    if (isset($relationships['ikan'])) {
                        $pembudidaya->setRelation('ikan', collect($relationships['ikan']));
                    }
                    
                    // Filter berdasarkan tahun pendataan
                    if ($request->filled('tahun') && isset($pembudidaya->tahun_pendataan)) {
                        if ($pembudidaya->tahun_pendataan != (int) $request->tahun) {
                            return null; // Skip data yang tidak matching tahun_pendataan
                        }
                    }
                    
                    // Hitung total produksi dan detail
                    $query = DB::table('pembudidaya_produksis')->where('id_pembudidaya', $pembudidaya->id);
                    if ($request->filled('bulan')) $query->where('bulan', $request->bulan);
                    $pembudidaya->total_produksi = $query->sum('total_produksi');
                    $pembudidaya->detail_produksi = $query->get();

                    if ($request->filled('kecamatan') && (string) ($pembudidaya->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                        return null;
                    }
                    
                    return $pembudidaya;
                }
                return null;
            })
            ->filter();

        $backupPengolah = DB::table('pengolah_verified_backup as backup')
            ->join('pengolahs as current', 'current.id_pengolah', '=', 'backup.id_pengolah')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) use ($request) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Separate relationships
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    
                    $pengolah = new Pengolah();
                    $pengolah->forceFill($data);
                    $pengolah->exists = true;
                    $pengolah->tipe_pelaku = 'Pengolah';
                    $pengolah->id = $pengolah->id_pengolah;
                    
                    // Filter berdasarkan tahun pendataan
                    if ($request->filled('tahun') && isset($pengolah->tahun_pendataan)) {
                        if ($pengolah->tahun_pendataan != (int) $request->tahun) {
                            return null; // Skip data yang tidak matching tahun_pendataan
                        }
                    }
                    
                    // Set relationships
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pengolah->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pengolah->setRelation('desa', $desa);
                    }
                    
                    // Hitung total produksi dari JSON
                    if ($pengolah->produksi_data && is_array($pengolah->produksi_data)) {
                        $prodCol = collect($pengolah->produksi_data);
                        if ($request->filled('bulan')) {
                            $prodCol = $prodCol->filter(function ($prod) use ($request) {
                                $bulanProduksi = $prod['bulan_produksi'] ?? [];
                                return in_array($request->bulan, $bulanProduksi);
                            });
                        }

                        $pengolah->total_produksi = $prodCol->sum(function ($prod) {
                            $qty = floatval($prod['jumlah_produk_qty'] ?? 0);
                            $pack = floatval($prod['jumlah_produk_pack'] ?? 0);
                            return $qty * $pack;
                        });
                        $pengolah->detail_produksi = $prodCol->values();
                    } else {
                        $pengolah->total_produksi = 0;
                        $pengolah->detail_produksi = collect();
                    }

                    if ($request->filled('kecamatan') && (string) ($pengolah->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                        return null;
                    }
                    
                    return $pengolah;
                }
                return null;
            })
            ->filter();

        if (!$includePembudidaya) {
            $backupPembudidaya = collect();
        }
        if (!$includePengolah) {
            $backupPengolah = collect();
        }

        // Merge backup data
        $pelakuUsaha = $pelakuUsaha->merge($backupPembudidaya)->merge($backupPengolah);

        if ($request->filled('search')) {
            $search = strtolower((string) $request->search);
            $pelakuUsaha = $pelakuUsaha->filter(function ($item) use ($search) {
                return str_contains(strtolower((string) ($item->nama_lengkap ?? '')), $search) ||
                    str_contains(strtolower((string) ($item->nama_usaha ?? '')), $search) ||
                    str_contains(strtolower((string) ($item->jenis_kegiatan_usaha ?? '')), $search);
            })->values();
        }

        // Sort berdasarkan nama
        $pelakuUsaha = $pelakuUsaha->sortBy('nama_lengkap')->values();

        // Cek apakah data kosong
        if ($pelakuUsaha->isEmpty()) {
            return redirect()->back()->with('error_export', 'Tidak dapat mengunduh data Excel karena data hasil filter kosong. Silakan ubah filter Anda.');
        }

        return Excel::download(new \App\Exports\RekapitulasiPelakuUsahaExport($pelakuUsaha), 'rekapitulasi-pelaku-usaha.xlsx');
    }

    /**
     * Rekapitulasi Pemasar.
     */
    public function rekapitulasiPemasar(Request $request)
    {
        // Query data verified
        $query = Pemasar::with(['kecamatan','desa'])
            ->where('status', 'verified');

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('komoditas')) {
            $query->where('komoditas', 'like', '%'.$request->komoditas.'%');
        }

        if ($request->filled('kategori')) {
            $query->where('skala_usaha', $request->kategori);
        }

        if ($request->filled('jenis_kegiatan_usaha')) {
            $query->where('jenis_kegiatan_usaha', $request->jenis_kegiatan_usaha);
        }

        // Filter berdasarkan tahun pendataan
        if ($request->filled('tahun')) {
            $query->where('tahun_pendataan', (int) $request->tahun);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        // Ambil data backup untuk data yang sedang pending edit
        $backupData = DB::table('pemasar_verified_backup as backup')
            ->join('pemasars as current', 'current.id_pemasar', '=', 'backup.id_pemasar')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) use ($request) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $pemasar = new Pemasar();
                    
                    // Separate relationships from attributes
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    
                    $pemasar->forceFill($data);
                    $pemasar->exists = true;
                    
                    // Filter berdasarkan tahun pendataan
                    if ($request->filled('tahun') && isset($pemasar->tahun_pendataan)) {
                        if ($pemasar->tahun_pendataan != (int) $request->tahun) {
                            return null; // Skip data yang tidak matching tahun_pendataan
                        }
                    }
                    
                    // Set relationships as model instances
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $pemasar->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $pemasar->setRelation('desa', $desa);
                    }
                    
                    return $pemasar;
                }
                return null;
            })
            ->filter(function ($item) use ($request) {
                if (!$item) {
                    return false;
                }

                if ($request->filled('kecamatan') && (string) ($item->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                    return false;
                }
                if ($request->filled('komoditas') && !str_contains(strtolower((string) ($item->komoditas ?? '')), strtolower((string) $request->komoditas))) {
                    return false;
                }
                if ($request->filled('kategori') && (string) ($item->skala_usaha ?? '') !== (string) $request->kategori) {
                    return false;
                }
                if ($request->filled('jenis_kegiatan_usaha') && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $request->jenis_kegiatan_usaha) {
                    return false;
                }
                if ($request->filled('tahun') && (int) ($item->tahun_pendataan ?? 0) !== (int) $request->tahun) {
                    return false;
                }
                if ($request->filled('search')) {
                    $search = strtolower((string) $request->search);
                    $haystack = strtolower((string) ($item->nama_lengkap ?? '')) . ' ' . strtolower((string) ($item->nama_usaha ?? '')) . ' ' . strtolower((string) ($item->jenis_kegiatan_usaha ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                return true;
            })
            ->filter();

        // Get verified data
        $verifiedPemasars = $query->get();
        
        // Merge with backup data
        $allPemasars = $verifiedPemasars->keyBy('id_pemasar');
        foreach ($backupData as $backupItem) {
            $allPemasars[$backupItem->id_pemasar] = $backupItem;
        }
        $allPemasars = $allPemasars->values();

        $perPage = (int) $request->input('per_page', 10);
        
        // Sort collection by nama_lengkap
        $allPemasars = $allPemasars->sortBy('nama_lengkap')->values();
        
        // Manual pagination for collection
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $allPemasars->slice($offset, $perPage)->values();
        
        $pemasars = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $allPemasars->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Hitung total harga pemasaran dari semua pemasar yang difilter (sebelum pagination)
        // Ambil dari data_pemasaran JSON field harga_jual
        $totalHargaPemasaran = 0;
        foreach ($allPemasars as $pemasar) {
            if ($pemasar->data_pemasaran) {
                $dataPemasaran = is_string($pemasar->data_pemasaran) 
                    ? json_decode($pemasar->data_pemasaran, true) 
                    : $pemasar->data_pemasaran;
                
                if (is_array($dataPemasaran)) {
                    foreach ($dataPemasaran as $item) {
                        $totalHargaPemasaran += floatval($item['harga_jual'] ?? 0);
                    }
                }
            }
        }

        // Hitung total pemasaran keseluruhan (Kg dan Rp) dari hasil_produksi
        $totalPemasaranKg = 0;
        $totalPemasaranRp = 0;
        foreach ($allPemasars as $pemasar) {
            $totalPemasaranKg += floatval($pemasar->hasil_produksi_kg ?? 0);
            $totalPemasaranRp += floatval($pemasar->hasil_produksi_rp ?? 0);
        }

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect komoditas distinct values for filter
        $komoditas = Komoditas::where('status', 'aktif')
            ->orderBy('nama_komoditas')
            ->pluck('nama_komoditas');

        // collect kategori (jenis_pemasaran) distinct values for filter
        $kategoris = ['Mikro', 'Kecil', 'Menengah', 'Besar'];
        
        // collect jenis_kegiatan_usaha distinct values for filter
        $jenis_kegiatan_usaha_list = ['Pemasar Ikan Segar Pengecer', 'Pemasar Ikan Segar Pedagang Besar', 'Pemasar Ikan Pindang/Asap', 'Pemasar Ikan Hias', 'Pemasar Ikan Asin'];

        // Hitung unique RTP berdasarkan NIK (jika ada data dengan NIK sama dan tahun berbeda, hitung 1 saja)
        $uniqueRTP = $allPemasars->pluck('nik_pemasar')->filter()->unique()->count();

        return view('pages.laporan.rekapitulasi-pemasar', compact('pemasars','kecamatans','komoditas','kategoris','jenis_kegiatan_usaha_list','totalHargaPemasaran','uniqueRTP','totalPemasaranKg','totalPemasaranRp'));
    }

    /**
     * Rekap Harga Ikan Segar.
     */
    public function rekapHargaIkanSegar(Request $request)
    {
        // Query data verified
        $query = HargaIkanSegar::with(['kecamatan','desa'])
            ->where('status', 'verified');

        // filters
        if ($request->filled('kecamatan')) {
            $query->where('id_kecamatan', $request->kecamatan);
        }

        if ($request->filled('jenis_ikan')) {
            $query->where('jenis_ikan', 'like', '%'.$request->jenis_ikan.'%');
        }

        if ($request->filled('nama_pasar')) {
            $query->where('nama_pasar', 'like', '%'.$request->nama_pasar.'%');
        }

        if ($request->filled('bulan')) {
            $bulanFilter = str_pad((string) $request->bulan, 2, '0', STR_PAD_LEFT);
            $query->whereMonth('tanggal_input', (int) $bulanFilter);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pasar', 'like', '%'.$search.'%')
                  ->orWhere('nama_pedagang', 'like', '%'.$search.'%')
                  ->orWhere('jenis_ikan', 'like', '%'.$search.'%');
            });
        }

        // Ambil data backup untuk data yang sedang pending edit
        $backupData = DB::table('harga_ikan_segar_verified_backup as backup')
            ->join('harga_ikan_segars as current', 'current.id_harga', '=', 'backup.id_harga')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $hargaIkan = new HargaIkanSegar();
                    
                    // Separate relationships from attributes
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }
                    if (isset($data['pasar'])) {
                        $relationships['pasar'] = $data['pasar'];
                        unset($data['pasar']);
                    }
                    
                    $hargaIkan->forceFill($data);
                    $hargaIkan->exists = true;
                    
                    // Set relationships as model instances
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $hargaIkan->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $hargaIkan->setRelation('desa', $desa);
                    }
                    if (isset($relationships['pasar'])) {
                        $pasar = new \App\Models\Pasar();
                        $pasar->forceFill($relationships['pasar']);
                        $hargaIkan->setRelation('pasar', $pasar);
                    }
                    
                    return $hargaIkan;
                }
                return null;
            })
            ->filter(function ($item) use ($request) {
                if (!$item) {
                    return false;
                }

                if ($request->filled('kecamatan') && (string) ($item->id_kecamatan ?? '') !== (string) $request->kecamatan) {
                    return false;
                }
                if ($request->filled('jenis_ikan') && !str_contains(strtolower((string) ($item->jenis_ikan ?? '')), strtolower((string) $request->jenis_ikan))) {
                    return false;
                }
                if ($request->filled('nama_pasar') && !str_contains(strtolower((string) ($item->nama_pasar ?? '')), strtolower((string) $request->nama_pasar))) {
                    return false;
                }
                if ($request->filled('bulan')) {
                    $bulan = (string) ($item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('m') : '');
                    if ($bulan !== str_pad((string) $request->bulan, 2, '0', STR_PAD_LEFT)) {
                        return false;
                    }
                }
                if ($request->filled('search')) {
                    $search = strtolower((string) $request->search);
                    $haystack = strtolower((string) ($item->nama_pasar ?? '')) . ' ' . strtolower((string) ($item->nama_pedagang ?? '')) . ' ' . strtolower((string) ($item->jenis_ikan ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                return true;
            })
            ->filter();

        // Get verified data
        $verifiedHargaIkan = $query->get();
        
        // Merge with backup data
        $allHargaIkan = $verifiedHargaIkan->keyBy('id_harga');
        foreach ($backupData as $backupItem) {
            $allHargaIkan[$backupItem->id_harga] = $backupItem;
        }
        $allHargaIkan = $allHargaIkan->values();

        $perPage = (int) $request->input('per_page', 10);
        
        // Sort collection by tanggal_input descending
        $allHargaIkan = $allHargaIkan->sortByDesc('tanggal_input')->values();
        
        // Manual pagination for collection
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $allHargaIkan->slice($offset, $perPage)->values();
        
        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $allHargaIkan->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        // collect jenis_ikan distinct values for filter
        $jenisIkans = HargaIkanSegar::select('jenis_ikan')
            ->whereNotNull('jenis_ikan')
            ->groupBy('jenis_ikan')
            ->orderBy('jenis_ikan')
            ->pluck('jenis_ikan');

        // collect nama_pasar distinct values for filter
        $namaPasars = HargaIkanSegar::select('nama_pasar')
            ->whereNotNull('nama_pasar')
            ->groupBy('nama_pasar')
            ->orderBy('nama_pasar')
            ->pluck('nama_pasar');

        // Ringkasan penting untuk dashboard rekap harga ikan
        $totalDataHarga = $allHargaIkan->count();
        $totalJenisIkan = $allHargaIkan->pluck('jenis_ikan')->filter()->unique()->count();
        $totalPasarAktif = $allHargaIkan->pluck('nama_pasar')->filter()->unique()->count();

        $avgHargaProdusen = round(
            $allHargaIkan
                ->pluck('harga_produsen')
                ->filter(fn ($value) => is_numeric($value) && (float) $value > 0)
                ->avg() ?? 0
        );

        $avgHargaKonsumen = round(
            $allHargaIkan
                ->pluck('harga_konsumen')
                ->filter(fn ($value) => is_numeric($value) && (float) $value > 0)
                ->avg() ?? 0
        );

        $totalKuantitasMingguan = $allHargaIkan
            ->pluck('kuantitas_perminggu')
            ->filter(fn ($value) => is_numeric($value))
            ->sum();

        return view('pages.laporan.rekap_harga_ikan_segar', compact(
            'items',
            'kecamatans',
            'jenisIkans',
            'namaPasars',
            'totalDataHarga',
            'totalJenisIkan',
            'totalPasarAktif',
            'avgHargaProdusen',
            'avgHargaKonsumen',
            'totalKuantitasMingguan'
        ));
    }

    /**
     * Export Pembudidaya to Excel.
     */
    public function exportPembudidaya(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'jenis_kegiatan_usaha', 'bulan', 'tahun', 'search', 'id']);
        
        // Cek apakah ada data sebelum export
        $export = new RekapitulasiPembudidayaExport($filters);
        $data = $export->collection();
        
        if ($data->isEmpty()) {
            return redirect()->back()->with('error_export', 'Tidak dapat mengunduh data Excel karena data hasil filter kosong. Silakan ubah filter Anda.');
        }
        
        $filename = 'Rekapitulasi_Pembudidaya_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download($export, $filename);
    }

    /**
     * Export Pengolah to Excel.
     */
    public function exportPengolah(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'jenis_kegiatan_usaha', 'bulan', 'tahun', 'search', 'id']);
        
        // Cek apakah ada data sebelum export
        $export = new RekapitulasiPengolahExport($filters);
        $data = $export->collection();
        
        if ($data->isEmpty()) {
            return redirect()->back()->with('error_export', 'Tidak dapat mengunduh data Excel karena data hasil filter kosong. Silakan ubah filter Anda.');
        }
        
        $filename = 'Rekapitulasi_Pengolah_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download($export, $filename);
    }

    /**
     * Export Pemasar to Excel.
     */
    public function exportPemasar(Request $request)
    {
        $filters = $request->only(['kecamatan', 'komoditas', 'kategori', 'jenis_kegiatan_usaha', 'bulan', 'tahun', 'search', 'id']);
        
        // Cek apakah ada data sebelum export
        $export = new RekapitulasiPemasarExport($filters);
        $data = $export->collection();
        
        if ($data->isEmpty()) {
            return redirect()->back()->with('error_export', 'Tidak dapat mengunduh data Excel karena data hasil filter kosong. Silakan ubah filter Anda.');
        }
        
        $filename = 'Rekapitulasi_Pemasar_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download($export, $filename);
    }

    /**
     * Export Harga Ikan Segar to Excel.
     */
    public function exportHargaIkanSegar(Request $request)
    {
        $filters = $request->only(['kecamatan', 'jenis_ikan', 'nama_pasar', 'bulan', 'search', 'id']);
        
        // Cek apakah ada data sebelum export
        $export = new HargaIkanSegarExport($filters);
        $data = $export->collection();
        
        if ($data->isEmpty()) {
            return redirect()->back()->with('error_export', 'Tidak dapat mengunduh data Excel karena data hasil filter kosong. Silakan ubah filter Anda.');
        }

        $filename = 'Rekap_Harga_Ikan_Segar_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download($export, $filename);
    }

    /**
     * Generate PDF detail Pembudidaya.
     */
    public function pdfPembudidaya($id)
    {
        $pembudidaya = Pembudidaya::with([
            'kecamatan', 
            'desa',
            'kecamatanUsaha',
            'desaUsaha',
            'izin',
            'investasi',
            'produksiFirst',
            'kolam',
            'ikan',
            'tenagaKerja'
        ])->findOrFail($id);

        $pembudidaya = $this->resolveVerifiedSnapshotIfPending(
            $pembudidaya,
            'pembudidaya_verified_backup',
            'id_pembudidaya',
            [
                'kecamatan' => \App\Models\MasterKecamatan::class,
                'desa' => \App\Models\MasterDesa::class,
                'kecamatanUsaha' => \App\Models\MasterKecamatan::class,
                'desaUsaha' => \App\Models\MasterDesa::class,
                'izin' => \App\Models\PembudidayaIzin::class,
                'investasi' => \App\Models\PembudidayaInvestasi::class,
                'tenagaKerja' => \App\Models\PembudidayaTenagaKerja::class,
            ],
            [
                'produksi' => \App\Models\PembudidayaProduksi::class,
                'kolam' => \App\Models\PembudidayaKolam::class,
                'ikan' => \App\Models\PembudidayaIkan::class,
            ],
            function (Pembudidaya $item) {
                if ($item->relationLoaded('produksi') && $item->produksi->count() > 0) {
                    $item->setRelation('produksiFirst', $item->produksi->first());
                }
            }
        );
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pembudidaya', compact('pembudidaya'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
            
        $filename = 'Detail_Pembudidaya_' . $pembudidaya->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Pengolah.
     */
    public function pdfPengolah($id)
    {
        $pengolah = Pengolah::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha'])->findOrFail($id);

        $pengolah = $this->resolveVerifiedSnapshotIfPending(
            $pengolah,
            'pengolah_verified_backup',
            'id_pengolah',
            [
                'kecamatan' => \App\Models\MasterKecamatan::class,
                'desa' => \App\Models\MasterDesa::class,
                'kecamatanUsaha' => \App\Models\MasterKecamatan::class,
                'desaUsaha' => \App\Models\MasterDesa::class,
            ],
            []
        );
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pengolah', compact('pengolah'));
        $filename = 'Detail_Pengolah_' . $pengolah->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Pemasar.
     */
    public function pdfPemasar($id)
    {
        $pemasar = Pemasar::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha'])->findOrFail($id);

        $pemasar = $this->resolveVerifiedSnapshotIfPending(
            $pemasar,
            'pemasar_verified_backup',
            'id_pemasar',
            [
                'kecamatan' => \App\Models\MasterKecamatan::class,
                'desa' => \App\Models\MasterDesa::class,
                'kecamatanUsaha' => \App\Models\MasterKecamatan::class,
                'desaUsaha' => \App\Models\MasterDesa::class,
            ],
            []
        );
        
        $pdf = Pdf::loadView('pages.laporan.pdf.pemasar', compact('pemasar'));
        $filename = 'Detail_Pemasar_' . $pemasar->nama_lengkap . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Generate PDF detail Harga Ikan Segar.
     */
    public function pdfHargaIkanSegar($id)
    {
        $harga = HargaIkanSegar::with(['kecamatan', 'desa'])->findOrFail($id);

        $harga = $this->resolveVerifiedSnapshotIfPending(
            $harga,
            'harga_ikan_segar_verified_backup',
            'id_harga',
            [
                'kecamatan' => \App\Models\MasterKecamatan::class,
                'desa' => \App\Models\MasterDesa::class,
                'pasar' => \App\Models\Pasar::class,
            ],
            []
        );
        
        $pdf = Pdf::loadView('pages.laporan.pdf.harga-ikan-segar', compact('harga'));
        $filename = 'Detail_Harga_Ikan_' . $harga->jenis_ikan . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Saat data current sedang pending/rejected, gunakan snapshot data verified terakhir.
     */
    private function resolveVerifiedSnapshotIfPending(
        Model $entity,
        string $backupTable,
        string $idColumn,
        array $singleRelations = [],
        array $listRelations = [],
        ?callable $afterHydrate = null
    ): Model {
        if (!in_array($entity->status, ['pending', 'rejected'], true)) {
            return $entity;
        }

        $backup = DB::table($backupTable)
            ->where($idColumn, $entity->{$idColumn})
            ->first();

        if (!$backup || empty($backup->data_verified)) {
            return $entity;
        }

        $data = json_decode($backup->data_verified, true);
        if (!is_array($data)) {
            return $entity;
        }

        $allRelationKeys = array_unique(array_merge(array_keys($singleRelations), array_keys($listRelations)));
        $relationPayload = [];
        foreach ($allRelationKeys as $key) {
            if (array_key_exists($key, $data)) {
                $relationPayload[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        $snapshot = $entity->newInstance([], true);
        $snapshot->forceFill($data);
        $snapshot->exists = true;

        foreach ($singleRelations as $relation => $class) {
            if (empty($relationPayload[$relation]) || !is_array($relationPayload[$relation])) {
                continue;
            }

            $related = new $class();
            $related->forceFill($relationPayload[$relation]);
            $related->exists = true;
            $snapshot->setRelation($relation, $related);
        }

        foreach ($listRelations as $relation => $class) {
            if (empty($relationPayload[$relation])) {
                continue;
            }

            $rows = is_array($relationPayload[$relation])
                ? (array_is_list($relationPayload[$relation]) ? $relationPayload[$relation] : [$relationPayload[$relation]])
                : [];

            $collection = collect($rows)->map(function ($row) use ($class) {
                $related = new $class();
                $related->forceFill((array) $row);
                $related->exists = true;
                return $related;
            });

            $snapshot->setRelation($relation, $collection);
        }

        if ($afterHydrate) {
            $afterHydrate($snapshot);
        }

        return $snapshot;
    }
}
