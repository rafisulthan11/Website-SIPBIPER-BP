<?php

namespace App\Http\Controllers;
use App\Models\Pembudidaya;
use Illuminate\Http\Request;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\Komoditas;
use App\Models\PembudidayaInvestasi;
use App\Models\PembudidayaIzin;
use App\Models\PembudidayaProduksi;
use App\Models\PembudidayaKolam;
use App\Models\PembudidayaIkan;
use App\Models\PembudidayaTenagaKerja;
use Illuminate\Support\Facades\Storage;

class PembudidayaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan jumlah per halaman dari query string
        $search = trim((string) $request->query('q', ''));
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = Pembudidaya::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('id_pembudidaya');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nik_pembudidaya', 'like', "%{$search}%")
                  ->orWhere('jenis_budidaya', 'like', "%{$search}%")
                  ->orWhereHas('kecamatan', function ($qq) use ($search) {
                      $qq->where('nama_kecamatan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('desa', function ($qq) use ($search) {
                      $qq->where('nama_desa', 'like', "%{$search}%");
                  });
            });
        }

        $pembudidayas = $query->paginate($perPage)->withQueryString();

        return view('pages.pembudidaya.index', [
            'pembudidayas' => $pembudidayas,
            'q' => $search,
            'perPage' => $perPage,
            'allowedPerPage' => $allowedPerPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all(); // Nanti kita buat ini dinamis
        $komoditas = Komoditas::orderBy('nama_komoditas')->get();
        return view('pages.pembudidaya.create', compact('kecamatans', 'desas', 'komoditas'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data (sederhana untuk saat ini)
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => 'required|string|size:16|unique:pembudidayas,nik_pembudidaya',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_budidaya' => 'required|string',
            // Izin Usaha (opsional strings)
            'izin.nib' => 'nullable|string|max:255',
            'izin.npwp' => 'nullable|string|max:255',
            'izin.kusuka' => 'nullable|string|max:255',
            'izin.pengesahan_menkumham' => 'nullable|string|max:255',
            'izin.cbib' => 'nullable|string|max:255',
            'izin.skai' => 'nullable|string|max:255',
            'izin.surat_ijin_pembudidayaan_ikan' => 'nullable|string|max:255',
            'izin.akta_pendirian_usaha' => 'nullable|string|max:255',
            'izin.imb' => 'nullable|string|max:255',
            'izin.sup_perikanan' => 'nullable|string|max:255',
            'izin.sup_perdagangan' => 'nullable|string|max:255',
            // Investasi (opsional)
            'investasi.nilai_asset' => 'nullable|numeric',
            'investasi.laba_ditanam' => 'nullable|numeric',
            'investasi.sewa' => 'nullable|numeric',
            'investasi.pinjaman' => 'nullable|in:0,1',
            'investasi.modal_sendiri' => 'nullable|numeric',
            'investasi.lahan_status' => 'nullable|array',
            'investasi.lahan_status.*' => 'string',
            'investasi.luas_m2' => 'nullable|numeric',
            'investasi.nilai_bangunan' => 'nullable|numeric',
            'investasi.bangunan' => 'nullable|string',
            'investasi.sertifikat' => 'nullable|in:IMB,NON_IMB',
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib'];
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                // Store file and get the path
                $path = $file->storeAs('lampiran/pembudidaya', $filename, 'public');
                $uploadedFiles[$field] = $path;
            }
        }

        // Simpan data ke tabel pembudidayas
        $p = Pembudidaya::create(array_merge(
            $request->except(['investasi','izin', ...$fileFields]),
            $uploadedFiles
        ));

        // Simpan investasi jika ada input
        $inv = $request->input('investasi', []);
        if (!empty($inv)) {
            $payload = [
                'id_pembudidaya' => $p->id_pembudidaya,
                'nilai_asset' => $inv['nilai_asset'] ?? null,
                'laba_ditanam' => $inv['laba_ditanam'] ?? null,
                'sewa' => $inv['sewa'] ?? null,
                'pinjaman' => isset($inv['pinjaman']) ? (bool)$inv['pinjaman'] : null,
                'modal_sendiri' => $inv['modal_sendiri'] ?? null,
                'lahan_status' => $inv['lahan_status'] ?? null,
                'luas_m2' => $inv['luas_m2'] ?? null,
                'nilai_bangunan' => $inv['nilai_bangunan'] ?? null,
                'bangunan' => $inv['bangunan'] ?? null,
                'sertifikat' => $inv['sertifikat'] ?? null,
            ];
            PembudidayaInvestasi::create($payload);
        }

        // Simpan izin jika ada input
        $iz = $request->input('izin', []);
        if (!empty($iz)) {
            $payloadIzin = array_merge(
                [ 'id_pembudidaya' => $p->id_pembudidaya ],
                [
                    'nib' => $iz['nib'] ?? null,
                    'npwp' => $iz['npwp'] ?? null,
                    'kusuka' => $iz['kusuka'] ?? null,
                    'pengesahan_menkumham' => $iz['pengesahan_menkumham'] ?? null,
                    'cbib' => $iz['cbib'] ?? null,
                    'skai' => $iz['skai'] ?? null,
                    'surat_ijin_pembudidayaan_ikan' => $iz['surat_ijin_pembudidayaan_ikan'] ?? null,
                    'akta_pendirian_usaha' => $iz['akta_pendirian_usaha'] ?? null,
                    'imb' => $iz['imb'] ?? null,
                    'sup_perikanan' => $iz['sup_perikanan'] ?? null,
                    'sup_perdagangan' => $iz['sup_perdagangan'] ?? null,
                ]
            );
            PembudidayaIzin::create($payloadIzin);
        }

        // Simpan data produksi jika ada
        $prod = $request->input('produksi', []);
        if (!empty($prod)) {
            PembudidayaProduksi::create([
                'id_pembudidaya' => $p->id_pembudidaya,
                'total_luas_kolam' => $prod['total_luas_kolam'] ?? null,
                'total_produksi' => $prod['total_produksi'] ?? null,
                'satuan_produksi' => $prod['satuan_produksi'] ?? null,
                'harga_per_satuan' => $prod['harga_per_satuan'] ?? null,
            ]);
        }

        // Simpan data kolam jika ada
        $kolams = $request->input('kolam', []);
        if (!empty($kolams)) {
            foreach ($kolams as $kolam) {
                if (!empty($kolam['jenis_kolam'])) {
                    PembudidayaKolam::create([
                        'id_pembudidaya' => $p->id_pembudidaya,
                        'jenis_kolam' => $kolam['jenis_kolam'],
                        'ukuran' => $kolam['ukuran_m2'] ?? null,
                        'jumlah' => $kolam['jumlah'] ?? 0,
                        'komoditas' => $kolam['komoditas'] ?? null,
                    ]);
                }
            }
        }

        // Simpan data ikan jika ada
        $ikans = $request->input('ikan', []);
        if (!empty($ikans)) {
            foreach ($ikans as $ikan) {
                if (!empty($ikan['jenis_ikan'])) {
                    PembudidayaIkan::create([
                        'id_pembudidaya' => $p->id_pembudidaya,
                        'jenis_ikan' => $ikan['jenis_ikan'],
                        'jenis_indukan' => $ikan['jenis_indukan'] ?? null,
                        'jumlah' => $ikan['jumlah'] ?? 0,
                        'asal' => $ikan['asal'] ?? null,
                    ]);
                }
            }
        }

        // Simpan data tenaga kerja jika ada
        $tk = $request->input('tenaga_kerja', []);
        if (!empty($tk)) {
            PembudidayaTenagaKerja::create([
                'id_pembudidaya' => $p->id_pembudidaya,
                'wni_laki_tetap' => $tk['wni_laki_tetap'] ?? 0,
                'wni_laki_tidak_tetap' => $tk['wni_laki_tidak_tetap'] ?? 0,
                'wni_laki_keluarga' => $tk['wni_laki_keluarga'] ?? 0,
                'wni_perempuan_tetap' => $tk['wni_perempuan_tetap'] ?? 0,
                'wni_perempuan_tidak_tetap' => $tk['wni_perempuan_tidak_tetap'] ?? 0,
                'wni_perempuan_keluarga' => $tk['wni_perempuan_keluarga'] ?? 0,
                'wna_laki_tetap' => $tk['wna_laki_tetap'] ?? 0,
                'wna_laki_tidak_tetap' => $tk['wna_laki_tidak_tetap'] ?? 0,
                'wna_laki_keluarga' => $tk['wna_laki_keluarga'] ?? 0,
                'wna_perempuan_tetap' => $tk['wna_perempuan_tetap'] ?? 0,
                'wna_perempuan_tidak_tetap' => $tk['wna_perempuan_tidak_tetap'] ?? 0,
                'wna_perempuan_keluarga' => $tk['wna_perempuan_keluarga'] ?? 0,
            ]);
        }

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembudidaya $pembudidaya)
    {
    $pembudidaya->load(['kecamatan','desa','kecamatanUsaha','desaUsaha','investasi','izin','produksi','kolam','ikan','tenagaKerja']);
        return view('pages.pembudidaya.show', compact('pembudidaya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembudidaya $pembudidaya)
    {
    $kecamatans = MasterKecamatan::all();
    $desas = MasterDesa::all();
    $komoditas = Komoditas::orderBy('nama_komoditas')->get();
    $pembudidaya->load(['investasi','izin','produksi','kolam','ikan','tenagaKerja','kecamatanUsaha','desaUsaha']);
        return view('pages.pembudidaya.edit', compact('pembudidaya', 'kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Mengupdate data di database.
     */
    public function update(Request $request, Pembudidaya $pembudidaya)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => 'required|string|size:16|unique:pembudidayas,nik_pembudidaya,' . $pembudidaya->id_pembudidaya . ',id_pembudidaya',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_budidaya' => 'required|string',
            // Izin Usaha (opsional strings)
            'izin.nib' => 'nullable|string|max:255',
            'izin.npwp' => 'nullable|string|max:255',
            'izin.kusuka' => 'nullable|string|max:255',
            'izin.pengesahan_menkumham' => 'nullable|string|max:255',
            'izin.cbib' => 'nullable|string|max:255',
            'izin.skai' => 'nullable|string|max:255',
            'izin.surat_ijin_pembudidayaan_ikan' => 'nullable|string|max:255',
            'izin.akta_pendirian_usaha' => 'nullable|string|max:255',
            'izin.imb' => 'nullable|string|max:255',
            'izin.sup_perikanan' => 'nullable|string|max:255',
            'izin.sup_perdagangan' => 'nullable|string|max:255',
            // Investasi (opsional)
            'investasi.nilai_asset' => 'nullable|numeric',
            'investasi.laba_ditanam' => 'nullable|numeric',
            'investasi.sewa' => 'nullable|numeric',
            'investasi.pinjaman' => 'nullable|in:0,1',
            'investasi.modal_sendiri' => 'nullable|numeric',
            'investasi.lahan_status' => 'nullable|array',
            'investasi.lahan_status.*' => 'string',
            'investasi.luas_m2' => 'nullable|numeric',
            'investasi.nilai_bangunan' => 'nullable|numeric',
            'investasi.bangunan' => 'nullable|string',
            'investasi.sertifikat' => 'nullable|in:IMB,NON_IMB',
        ]);

        // Update data di tabel
    $pembudidaya->update($request->except(['investasi','izin']));

        $inv = $request->input('investasi', []);
        if (!empty($inv)) {
            $payload = [
                'nilai_asset' => $inv['nilai_asset'] ?? null,
                'laba_ditanam' => $inv['laba_ditanam'] ?? null,
                'sewa' => $inv['sewa'] ?? null,
                'pinjaman' => isset($inv['pinjaman']) ? (bool)$inv['pinjaman'] : null,
                'modal_sendiri' => $inv['modal_sendiri'] ?? null,
                'lahan_status' => $inv['lahan_status'] ?? null,
                'luas_m2' => $inv['luas_m2'] ?? null,
                'nilai_bangunan' => $inv['nilai_bangunan'] ?? null,
                'bangunan' => $inv['bangunan'] ?? null,
                'sertifikat' => $inv['sertifikat'] ?? null,
            ];

            $existing = $pembudidaya->investasi;
            if ($existing) {
                $existing->update($payload);
            } else {
                $payload['id_pembudidaya'] = $pembudidaya->id_pembudidaya;
                PembudidayaInvestasi::create($payload);
            }
        }

        // Update/simpan izin
        $iz = $request->input('izin', []);
        if (!empty($iz)) {
            $payloadIzin = [
                'nib' => $iz['nib'] ?? null,
                'npwp' => $iz['npwp'] ?? null,
                'kusuka' => $iz['kusuka'] ?? null,
                'pengesahan_menkumham' => $iz['pengesahan_menkumham'] ?? null,
                'cbib' => $iz['cbib'] ?? null,
                'skai' => $iz['skai'] ?? null,
                'surat_ijin_pembudidayaan_ikan' => $iz['surat_ijin_pembudidayaan_ikan'] ?? null,
                'akta_pendirian_usaha' => $iz['akta_pendirian_usaha'] ?? null,
                'imb' => $iz['imb'] ?? null,
                'sup_perikanan' => $iz['sup_perikanan'] ?? null,
                'sup_perdagangan' => $iz['sup_perdagangan'] ?? null,
            ];
            $existingIzin = $pembudidaya->izin;
            if ($existingIzin) {
                $existingIzin->update($payloadIzin);
            } else {
                $payloadIzin['id_pembudidaya'] = $pembudidaya->id_pembudidaya;
                PembudidayaIzin::create($payloadIzin);
            }
        }

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib'];
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($pembudidaya->$field) {
                    Storage::disk('public')->delete($pembudidaya->$field);
                }
                
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('lampiran/pembudidaya', $filename, 'public');
                $uploadedFiles[$field] = $path;
            }
        }
        
        if (!empty($uploadedFiles)) {
            $pembudidaya->update($uploadedFiles);
        }

        // Update/Create Produksi
        $prod = $request->input('produksi', []);
        if (!empty($prod)) {
            $produksiData = [
                'total_luas_kolam' => $prod['total_luas_kolam'] ?? null,
                'total_produksi' => $prod['total_produksi'] ?? null,
                'satuan_produksi' => $prod['satuan_produksi'] ?? null,
                'harga_per_satuan' => $prod['harga_per_satuan'] ?? null,
            ];
            
            $existingProduksi = $pembudidaya->produksi;
            if ($existingProduksi) {
                $existingProduksi->update($produksiData);
            } else {
                $produksiData['id_pembudidaya'] = $pembudidaya->id_pembudidaya;
                PembudidayaProduksi::create($produksiData);
            }
        }

        // Update Kolam - delete all and recreate
        $pembudidaya->kolam()->delete();
        $kolams = $request->input('kolam', []);
        if (!empty($kolams)) {
            foreach ($kolams as $kolam) {
                if (!empty($kolam['jenis_kolam'])) {
                    PembudidayaKolam::create([
                        'id_pembudidaya' => $pembudidaya->id_pembudidaya,
                        'jenis_kolam' => $kolam['jenis_kolam'],
                        'ukuran' => $kolam['ukuran_m2'] ?? null,
                        'jumlah' => $kolam['jumlah'] ?? 0,
                        'komoditas' => $kolam['komoditas'] ?? null,
                    ]);
                }
            }
        }

        // Update Ikan - delete all and recreate
        $pembudidaya->ikan()->delete();
        $ikans = $request->input('ikan', []);
        if (!empty($ikans)) {
            foreach ($ikans as $ikan) {
                if (!empty($ikan['jenis_ikan'])) {
                    PembudidayaIkan::create([
                        'id_pembudidaya' => $pembudidaya->id_pembudidaya,
                        'jenis_ikan' => $ikan['jenis_ikan'],
                        'jenis_indukan' => $ikan['jenis_indukan'] ?? null,
                        'jumlah' => $ikan['jumlah'] ?? 0,
                        'asal' => $ikan['asal'] ?? null,
                    ]);
                }
            }
        }

        // Update/Create Tenaga Kerja
        $tk = $request->input('tenaga_kerja', []);
        if (!empty($tk)) {
            $tkData = [
                'wni_laki_tetap' => $tk['wni_laki_tetap'] ?? 0,
                'wni_laki_tidak_tetap' => $tk['wni_laki_tidak_tetap'] ?? 0,
                'wni_laki_keluarga' => $tk['wni_laki_keluarga'] ?? 0,
                'wni_perempuan_tetap' => $tk['wni_perempuan_tetap'] ?? 0,
                'wni_perempuan_tidak_tetap' => $tk['wni_perempuan_tidak_tetap'] ?? 0,
                'wni_perempuan_keluarga' => $tk['wni_perempuan_keluarga'] ?? 0,
                'wna_laki_tetap' => $tk['wna_laki_tetap'] ?? 0,
                'wna_laki_tidak_tetap' => $tk['wna_laki_tidak_tetap'] ?? 0,
                'wna_laki_keluarga' => $tk['wna_laki_keluarga'] ?? 0,
                'wna_perempuan_tetap' => $tk['wna_perempuan_tetap'] ?? 0,
                'wna_perempuan_tidak_tetap' => $tk['wna_perempuan_tidak_tetap'] ?? 0,
                'wna_perempuan_keluarga' => $tk['wna_perempuan_keluarga'] ?? 0,
            ];
            
            $existingTk = $pembudidaya->tenagaKerja;
            if ($existingTk) {
                $existingTk->update($tkData);
            } else {
                $tkData['id_pembudidaya'] = $pembudidaya->id_pembudidaya;
                PembudidayaTenagaKerja::create($tkData);
            }
        }

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembudidaya $pembudidaya)
    {
        $pembudidaya->delete();

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil dihapus.');
    }

    /**
     * Get desa by kecamatan (for AJAX dependent dropdown)
     */
    public function getDesaByKecamatan($id_kecamatan)
    {
        $desas = MasterDesa::where('id_kecamatan', $id_kecamatan)
            ->orderBy('nama_desa')
            ->get(['id_desa', 'nama_desa']);
        
        return response()->json($desas);
    }
}
