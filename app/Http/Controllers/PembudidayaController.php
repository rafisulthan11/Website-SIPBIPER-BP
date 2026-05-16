<?php

namespace App\Http\Controllers;
use App\Models\Pembudidaya;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PembudidayaController extends Controller
{
    /**
     * Helper method to create notification
     */
    protected function createNotification($userId, $type, $title, $message, $moduleId = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'module' => 'pembudidaya',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('pembudidaya.show', $moduleId) : route('pembudidaya.index'),
        ]);
    }

    /**
     * Notify admin and super admin roles
     */
    protected function notifyAdmins($type, $title, $message, $moduleId = null, $excludeUserId = null)
    {
        $admins = User::whereHas('role', function($q) {
            $q->whereIn('nama_role', ['admin', 'super admin']);
        })
        ->when($excludeUserId, function ($query) use ($excludeUserId) {
            $query->where('id_user', '!=', $excludeUserId);
        })
        ->get();

        foreach ($admins as $admin) {
            $this->createNotification($admin->id_user, $type, $title, $message, $moduleId);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan jumlah per halaman dari query string
        $search = trim((string) $request->query('q', ''));
        // Jika request memiliki parameter 'tahun', gunakan nilai tersebut (bisa empty string)
        // Jika tidak ada parameter 'tahun', gunakan tahun saat ini sebagai default (first load)
        $tahun = $request->has('tahun') ? (string) $request->query('tahun') : (string) date('Y');
        $status = $request->query('status', '');
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = Pembudidaya::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('id_pembudidaya');

        // Filter berdasarkan tahun pendataan (hanya jika tahun tidak kosong)
        if (!empty($tahun)) {
            $query->where('tahun_pendataan', $tahun);
        }

        // Filter berdasarkan status
        if (!empty($status)) {
            $query->where('status', $status);
        }

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
            'tahun' => $tahun,
            'status' => $status,
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
        $komoditas = Komoditas::where('status', 'aktif')
            ->where('tipe', 'pembudidaya')
            ->orderBy('nama_komoditas')
            ->get();
        return view('pages.pembudidaya.create', compact('kecamatans', 'desas', 'komoditas'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data (sederhana untuk saat ini)
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => [
                'required',
                'digits:16',
                Rule::unique('pembudidayas', 'nik_pembudidaya')->where(function ($query) use ($request) {
                    return $query->where('tahun_pendataan', $request->tahun_pendataan);
                })
            ],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'kontak' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
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
                'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik_pembudidaya.required' => 'NIK wajib diisi.',
            'nik_pembudidaya.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'nik_pembudidaya.unique' => 'NIK ini sudah terdaftar untuk tahun pendataan yang sama. Satu NIK hanya boleh didaftarkan satu kali per tahun.',
            'id_kecamatan.required' => 'Kecamatan dan desa wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'kontak.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
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

        try {
            $p = DB::transaction(function () use ($request, $fileFields, $uploadedFiles) {
                // Simpan data ke tabel pembudidayas
                $p = Pembudidaya::create(array_merge(
                    $request->except(['investasi','izin', ...$fileFields]),
                    $uploadedFiles,
                    [
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]
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

                // Simpan data produksi (multi-produk)
                $produksiData = $request->input('produksi', []);
                if (!empty($produksiData)) {
                    foreach ($produksiData as $prodIndex => $prod) {
                        if (isset($prod['total_luas_kolam']) || isset($prod['total_produksi'])) {
                            PembudidayaProduksi::create([
                                'id_pembudidaya' => $p->id_pembudidaya,
                                'product_index' => $prodIndex,
                                'bulan' => $prod['bulan'] ?? null,
                                'tahun' => $prod['tahun'] ?? null,
                                'total_luas_kolam' => $prod['total_luas_kolam'] ?? null,
                                'total_produksi' => $prod['total_produksi'] ?? null,
                                'satuan_produksi' => $prod['satuan_produksi'] ?? null,
                                'harga_per_satuan' => $prod['harga_per_satuan'] ?? null,
                            ]);
                        }

                        if (isset($prod['kolam']) && !empty($prod['kolam'])) {
                            foreach ($prod['kolam'] as $kolam) {
                                if (!empty($kolam['jenis_kolam'])) {
                                    PembudidayaKolam::create([
                                        'id_pembudidaya' => $p->id_pembudidaya,
                                        'product_index' => $prodIndex,
                                        'jenis_kolam' => $kolam['jenis_kolam'],
                                        'ukuran' => $kolam['ukuran_m2'] ?? null,
                                        'jumlah' => $kolam['jumlah'] ?? 0,
                                        'komoditas' => $kolam['komoditas'] ?? null,
                                    ]);
                                }
                            }
                        }

                        if (isset($prod['ikan']) && !empty($prod['ikan'])) {
                            foreach ($prod['ikan'] as $ikan) {
                                if (!empty($ikan['jenis_ikan'])) {
                                    PembudidayaIkan::create([
                                        'id_pembudidaya' => $p->id_pembudidaya,
                                        'product_index' => $prodIndex,
                                        'jenis_ikan' => $ikan['jenis_ikan'],
                                        'jenis_indukan' => $ikan['jenis_indukan'] ?? null,
                                        'jumlah' => $ikan['jumlah'] ?? 0,
                                        'asal' => $ikan['asal_indukan'] ?? null,
                                    ]);
                                }
                            }
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

                return $p;
            });
        } catch (\Throwable $e) {
            foreach ($uploadedFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

        // Notify all admins about new data
        $this->notifyAdmins(
            'create',
            'Data Pembudidaya Ditambahkan',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data pembudidaya: ' . $p->nama_lengkap,
            $p->id_pembudidaya
        );

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil ditambahkan dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Pembudidaya $pembudidaya)
    {
        $pembudidaya->load(['kecamatan','desa','kecamatanUsaha','desaUsaha','investasi','izin','produksi','kolam','ikan','tenagaKerja']);
        
        // Cek apakah diakses dari laporan
        $backupData = null;
        if ($request->query('from_report') && in_array($pembudidaya->status, ['pending', 'rejected'])) {
            $backup = DB::table('pembudidaya_verified_backup')
                ->where('id_pembudidaya', $pembudidaya->id_pembudidaya)
                ->first();
            
            if ($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    // Restore backup sebagai model instance
                    $backupData = new Pembudidaya();
                    
                    // Separate relationships
                    $relationships = [];
                    foreach (['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha', 'ikan', 'investasi', 'izin', 'produksi', 'kolam', 'tenagaKerja'] as $rel) {
                        if (isset($data[$rel])) {
                            $relationships[$rel] = $data[$rel];
                            unset($data[$rel]);
                        }
                    }
                    
                    $backupData->forceFill($data);
                    $backupData->exists = true;
                    
                    // Set relationships
                    foreach ($relationships as $relName => $relData) {
                        if (!$relData) continue;
                        
                        if (in_array($relName, ['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha'])) {
                            $modelClass = $relName === 'kecamatan' || $relName === 'kecamatanUsaha' ? \App\Models\MasterKecamatan::class : \App\Models\MasterDesa::class;
                            $model = new $modelClass();
                            $model->forceFill($relData);
                            $backupData->setRelation($relName, $model);
                        } elseif (in_array($relName, ['investasi', 'izin', 'tenagaKerja'])) {
                            $modelClassMap = [
                                'investasi' => \App\Models\PembudidayaInvestasi::class,
                                'izin' => \App\Models\PembudidayaIzin::class,
                                'tenagaKerja' => \App\Models\PembudidayaTenagaKerja::class,
                            ];
                            $model = new $modelClassMap[$relName]();
                            $model->forceFill($relData);
                            $model->exists = true;
                            $backupData->setRelation($relName, $model);
                        } else {
                            $listModelClassMap = [
                                'produksi' => PembudidayaProduksi::class,
                                'kolam' => PembudidayaKolam::class,
                                'ikan' => PembudidayaIkan::class,
                            ];

                            if (isset($listModelClassMap[$relName])) {
                                $rows = is_array($relData)
                                    ? (array_is_list($relData) ? $relData : [$relData])
                                    : [];

                                $collection = collect($rows)->map(function ($row) use ($listModelClassMap, $relName) {
                                    $modelClass = $listModelClassMap[$relName];
                                    $model = new $modelClass();
                                    $model->forceFill((array) $row);
                                    $model->exists = true;

                                    return $model;
                                });

                                $backupData->setRelation($relName, $collection);
                            } else {
                                $backupData->setRelation($relName, collect($relData));
                            }
                        }
                    }
                }
            }
        }
        
        return view('pages.pembudidaya.show', compact('pembudidaya', 'backupData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembudidaya $pembudidaya)
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        $komoditas = Komoditas::where('status', 'aktif')
            ->where('tipe', 'pembudidaya')
            ->orderBy('nama_komoditas')
            ->get();
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
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => [
                'required',
                'digits:16',
                Rule::unique('pembudidayas', 'nik_pembudidaya')
                    ->where(function ($query) use ($request) {
                        return $query->where('tahun_pendataan', $request->tahun_pendataan);
                    })
                    ->ignore($pembudidaya->id_pembudidaya, 'id_pembudidaya')
            ],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'kontak' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
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
        ], [
                'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
                'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:1024',
            'kontak.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
        ]);

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib'];
        $uploadedFiles = [];
        $oldFilesToDelete = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('lampiran/pembudidaya', $filename, 'public');
                $uploadedFiles[$field] = $path;

                if ($pembudidaya->$field) {
                    $oldFilesToDelete[] = $pembudidaya->$field;
                }
            }
        }
        try {
            DB::transaction(function () use ($request, $pembudidaya, $uploadedFiles) {
                // BACKUP DATA VERIFIED SEBELUM EDIT
                // Jika data saat ini sudah verified, backup dulu sebelum ubah ke pending
                if ($pembudidaya->status === 'verified') {
                    $pembudidaya->load([
                        'kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha',
                        'izin', 'investasi', 'produksi', 'kolam', 'ikan', 'tenagaKerja'
                    ]);

                    DB::table('pembudidaya_verified_backup')->updateOrInsert(
                        ['id_pembudidaya' => $pembudidaya->id_pembudidaya],
                        [
                            'data_verified' => json_encode($pembudidaya->toArray()),
                            'backed_up_at' => now()
                        ]
                    );
                }

                $pembudidaya->update(array_merge(
                    $request->except(['investasi','izin','created_by','verified_by','verified_at']),
                    [
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'updated_by' => auth()->id()
                    ]
                ));

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

                if (!empty($uploadedFiles)) {
                    $pembudidaya->update($uploadedFiles);
                }

                // Update/Create Produksi (multi-produk)
                $produksiData = $request->input('produksi', []);

                if (!empty($produksiData)) {
                    // Hapus data produksi, kolam dan ikan yang lama
                    PembudidayaProduksi::where('id_pembudidaya', $pembudidaya->id_pembudidaya)->delete();
                    $pembudidaya->kolam()->delete();
                    $pembudidaya->ikan()->delete();

                    foreach ($produksiData as $prodIndex => $prod) {
                        if (isset($prod['total_luas_kolam']) || isset($prod['total_produksi'])) {
                            PembudidayaProduksi::create([
                                'id_pembudidaya' => $pembudidaya->id_pembudidaya,
                                'product_index' => $prodIndex,
                                'bulan' => $prod['bulan'] ?? null,
                                'tahun' => $prod['tahun'] ?? null,
                                'total_luas_kolam' => $prod['total_luas_kolam'] ?? null,
                                'total_produksi' => $prod['total_produksi'] ?? null,
                                'satuan_produksi' => $prod['satuan_produksi'] ?? null,
                                'harga_per_satuan' => $prod['harga_per_satuan'] ?? null,
                            ]);
                        }

                        if (isset($prod['kolam']) && !empty($prod['kolam'])) {
                            foreach ($prod['kolam'] as $kolam) {
                                if (!empty($kolam['jenis_kolam'])) {
                                    PembudidayaKolam::create([
                                        'id_pembudidaya' => $pembudidaya->id_pembudidaya,
                                        'product_index' => $prodIndex,
                                        'jenis_kolam' => $kolam['jenis_kolam'],
                                        'ukuran' => $kolam['ukuran_m2'] ?? null,
                                        'jumlah' => $kolam['jumlah'] ?? 0,
                                        'komoditas' => $kolam['komoditas'] ?? null,
                                    ]);
                                }
                            }
                        }

                        if (isset($prod['ikan']) && !empty($prod['ikan'])) {
                            foreach ($prod['ikan'] as $ikan) {
                                if (!empty($ikan['jenis_ikan'])) {
                                    PembudidayaIkan::create([
                                        'id_pembudidaya' => $pembudidaya->id_pembudidaya,
                                        'product_index' => $prodIndex,
                                        'jenis_ikan' => $ikan['jenis_ikan'],
                                        'jenis_indukan' => $ikan['jenis_indukan'] ?? null,
                                        'jumlah' => $ikan['jumlah'] ?? 0,
                                        'asal' => $ikan['asal_indukan'] ?? null,
                                    ]);
                                }
                            }
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
            });
        } catch (\Throwable $e) {
            foreach ($uploadedFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

        foreach ($oldFilesToDelete as $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        // Notify all admins about data update
        $this->notifyAdmins(
            'update',
            'Data Pembudidaya Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data pembudidaya: ' . $pembudidaya->nama_lengkap,
            $pembudidaya->id_pembudidaya
        );

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil diperbarui dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembudidaya $pembudidaya)
    {
        $nama = $pembudidaya->nama_lengkap;
        $idPembudidaya = $pembudidaya->id_pembudidaya;
        $createdBy = $pembudidaya->created_by;
        $updatedBy = $pembudidaya->updated_by;
        $pembudidaya->delete();

        $this->notifyAdmins(
            'delete',
            'Data Pembudidaya Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pembudidaya: ' . $nama,
            $idPembudidaya,
            auth()->user()->id_user
        );

        $targetUserId = $updatedBy ?? $createdBy;
        $targetStaffId = null;
        if ($targetUserId) {
            $targetStaffId = User::where('id_user', $targetUserId)
                ->whereHas('role', function ($q) {
                    $q->where('nama_role', 'staff');
                })
                ->value('id_user');
        }

        if ($targetStaffId && $targetStaffId != auth()->user()->id_user) {
            $this->createNotification(
                $targetStaffId,
                'delete',
                'Data Pembudidaya Dihapus',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pembudidaya: ' . $nama,
                $idPembudidaya
            );
        }

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

    /**
     * Verify data (admin only)
     */
    public function verify(Pembudidaya $pembudidaya)
    {
        // Cek role - hanya admin yang bisa verify
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pembudidaya.index')->with('error', 'Hanya admin yang dapat memverifikasi data.');
        }

        // Get created_by BEFORE update to ensure it's not lost
        $createdBy = $pembudidaya->created_by;
        $updatedBy = $pembudidaya->updated_by;

        $pembudidaya->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_perbaikan' => null,
        ]);

        // Hapus backup karena data baru sudah diverifikasi
        DB::table('pembudidaya_verified_backup')
            ->where('id_pembudidaya', $pembudidaya->id_pembudidaya)
            ->delete();

        $this->notifyAdmins(
            'verified',
            'Data Pembudidaya Diverifikasi',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pembudidaya: ' . $pembudidaya->nama_lengkap,
            $pembudidaya->id_pembudidaya,
            auth()->user()->id_user
        );

        // PERBAIKAN: Kirim notifikasi ke staff yang EDIT data (updated_by)
        // Jika tidak ada updated_by, baru fallback ke created_by
        $targetUserId = $updatedBy ?? $createdBy;
        
        $targetStaffId = null;
        if ($targetUserId) {
            $targetStaffId = User::where('id_user', $targetUserId)
                ->whereHas('role', function ($q) {
                    $q->where('nama_role', 'staff');
                })
                ->value('id_user');
        }

        // Kirim notifikasi hanya ke staff yang membuat/mengedit data
        if ($targetStaffId && $targetStaffId != auth()->user()->id_user) {
            $this->createNotification(
                $targetStaffId,
                'verified',
                'Data Pembudidaya Diverifikasi',
                'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pembudidaya: ' . $pembudidaya->nama_lengkap,
                $pembudidaya->id_pembudidaya
            );
        }

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil diverifikasi dan status diubah menjadi VERIFIED.');
    }

    /**
     * Reject data (admin only)
     */
    public function reject(Request $request, Pembudidaya $pembudidaya)
    {
        // Cek role - hanya admin yang bisa reject
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pembudidaya.index')->with('error', 'Hanya admin yang dapat menolak data.');
        }

        $validated = $request->validate([
            'catatan_perbaikan' => 'required|string|max:2000',
        ], [
            'catatan_perbaikan.required' => 'Catatan perbaikan wajib diisi saat menolak data.',
        ]);

        // Get created_by BEFORE update to ensure it's not lost
        $createdBy = $pembudidaya->created_by;
        $updatedBy = $pembudidaya->updated_by;

        $pembudidaya->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'catatan_perbaikan' => $validated['catatan_perbaikan'],
        ]);

        $this->notifyAdmins(
            'rejected',
            'Data Pembudidaya Ditolak',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pembudidaya: ' . $pembudidaya->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
            $pembudidaya->id_pembudidaya,
            auth()->user()->id_user
        );

        // PERBAIKAN: Kirim notifikasi ke staff yang EDIT data (updated_by)
        // Jika tidak ada updated_by, baru fallback ke created_by
        $targetUserId = $updatedBy ?? $createdBy;
        
        $targetStaffId = null;
        if ($targetUserId) {
            $targetStaffId = User::where('id_user', $targetUserId)
                ->whereHas('role', function ($q) {
                    $q->where('nama_role', 'staff');
                })
                ->value('id_user');
        }

        // Kirim notifikasi hanya ke staff yang membuat/mengedit data
        if ($targetStaffId && $targetStaffId != auth()->user()->id_user) {
            $this->createNotification(
                $targetStaffId,
                'rejected',
                'Data Pembudidaya Ditolak',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pembudidaya: ' . $pembudidaya->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
                $pembudidaya->id_pembudidaya
            );
        }

        return redirect()->route('pembudidaya.index')->with('warning', 'Data pembudidaya ditolak dan status diubah menjadi REJECTED.');
    }
}
