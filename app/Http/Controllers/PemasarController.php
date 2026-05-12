<?php

namespace App\Http\Controllers;

use App\Models\Pemasar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\PemasarPemasaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PemasarController extends Controller
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
            'module' => 'pemasar',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('pemasar.show', $moduleId) : route('pemasar.index'),
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
        $search = trim((string) $request->query('search', ''));
        // Jika request memiliki parameter 'tahun', gunakan nilai tersebut (bisa empty string)
        // Jika tidak ada parameter 'tahun', gunakan tahun saat ini sebagai default (first load)
        $tahun = $request->has('tahun') ? (string) $request->query('tahun') : (string) date('Y');
        $status = $request->query('status', '');
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = Pemasar::query()
            ->with(['kecamatan', 'desa', 'pemasaran'])
            ->orderByDesc('id_pemasar');

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
                  ->orWhere('nik_pemasar', 'like', "%{$search}%")
                  ->orWhereHas('kecamatan', function ($qq) use ($search) {
                      $qq->where('nama_kecamatan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('desa', function ($qq) use ($search) {
                      $qq->where('nama_desa', 'like', "%{$search}%");
                  });
            });
        }

        $pemasars = $query->paginate($perPage)->withQueryString();

        return view('pages.pemasar.index', [
            'pemasars' => $pemasars,
            'search' => $search,
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
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')
            ->where('tipe', 'pemasar')
            ->orderBy('nama_komoditas')
            ->get();
        return view('pages.pemasar.create', compact('kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nik_pemasar' => [
                'required',
                'digits:16',
                Rule::unique('pemasars', 'nik_pemasar')->where(function ($query) use ($request) {
                    return $query->where('tahun_pendataan', $request->tahun_pendataan);
                })
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'no_npwp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'nama_usaha' => 'nullable|string|max:255',
            'nama_kelompok' => 'nullable|string|max:255',
            'npwp_usaha' => 'nullable|string|max:255',
            'alamat_usaha' => 'nullable|string',
            'telp_usaha' => 'nullable|string|max:255',
            'email_usaha' => 'nullable|email|max:255',
            'skala_usaha' => 'nullable|string',
            'status_usaha' => 'nullable|string',
            'tahun_mulai_usaha' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'kontak' => 'required|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'kecamatan_usaha' => 'nullable|exists:master_kecamatans,id_kecamatan',
            'desa_usaha' => 'nullable|exists:master_desas,id_desa',
            // Izin Usaha
            'nib' => 'nullable|string|max:255',
            'npwp_izin' => 'nullable|string|max:255',
            'kusuka' => 'nullable|string|max:255',
            'pengesahan_menkumham' => 'nullable|string|max:255',
            'tdu_php' => 'nullable|string|max:255',
            'sppl' => 'nullable|string|max:255',
            'siup_perdagangan' => 'nullable|string|max:255',
            'akta_pendiri_usaha' => 'nullable|string|max:255',
            'imb' => 'nullable|string|max:255',
            'siup_perikanan' => 'nullable|string|max:255',
            'ukl_upl' => 'nullable|string|max:255',
            'amdal' => 'nullable|string|max:255',
            // Investasi
            'mesin_peralatan' => 'nullable|array',
            'mesin_peralatan.*.jenis_mesin' => 'nullable|string',
            'mesin_peralatan.*.kapasitas' => 'nullable|string',
            'mesin_peralatan.*.jumlah' => 'nullable|integer',
            'mesin_peralatan.*.asal' => 'nullable|string',
            'investasi_tanah' => 'nullable|numeric',
            'investasi_gedung' => 'nullable|numeric',
            'investasi_mesin_peralatan' => 'nullable|numeric',
            'investasi_kendaraan' => 'nullable|numeric',
            'investasi_lain_lain' => 'nullable|numeric',
            'investasi_sub_jumlah' => 'nullable|numeric',
            'modal_kerja_1_bulan' => 'nullable|numeric',
            'modal_kerja_sub_jumlah' => 'nullable|numeric',
            'modal_sendiri' => 'nullable|numeric',
            'laba_ditanam' => 'nullable|numeric',
            'modal_pinjam' => 'nullable|numeric',
            'sertifikat_lahan' => 'nullable|array',
            'luas_lahan' => 'nullable|numeric',
            'nilai_lahan' => 'nullable|numeric',
            'sertifikat_bangunan' => 'nullable|array',
            'luas_bangunan' => 'nullable|numeric',
            'nilai_bangunan' => 'nullable|numeric',
            'bulan_produksi' => 'nullable|array',
            'distribusi_pemasaran' => 'nullable|string',
            // Production Fields
            'kapasitas_terpasang' => 'nullable|numeric',
            'hasil_produksi_kg' => 'nullable|numeric',
            'hasil_produksi_rp' => 'nullable|numeric',
            // New pemasaran array structure (multiple sections)
            'pemasaran' => 'nullable|array',
            'pemasaran.*.kapasitas_terpasang' => 'nullable|numeric',
            'pemasaran.*.hasil_produksi_kg' => 'nullable|numeric',
            'pemasaran.*.hasil_produksi_rp' => 'nullable|numeric',
            'pemasaran.*.bulan_produksi' => 'nullable|array',
            'pemasaran.*.distribusi_pemasaran' => 'nullable|string',
            'pemasaran.*.data_pemasaran' => 'nullable|array',
            'pemasaran.*.data_pemasaran.*.jenis_ikan' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.komoditas' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.komoditas' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.jumlah_volume' => 'nullable|numeric',
            'pemasaran.*.data_pemasaran.*.asal_ikan' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.harga_beli' => 'nullable|numeric',
            'pemasaran.*.data_pemasaran.*.harga_jual' => 'nullable|numeric',
            // Old structure (backward compatibility)
            'data_pemasaran' => 'nullable|array',
            'data_pemasaran.*.jenis_ikan' => 'nullable|string',
            'data_pemasaran.*.komoditas' => 'nullable|string',
            'data_pemasaran.*.komoditas' => 'nullable|string',
            'data_pemasaran.*.jumlah_volume' => 'nullable|numeric',
            'data_pemasaran.*.kebutuhan_min' => 'nullable|numeric',
            'data_pemasaran.*.kebutuhan_max' => 'nullable|numeric',
            'data_pemasaran.*.asal_ikan' => 'nullable|string',
            'data_pemasaran.*.harga_beli' => 'nullable|numeric',
            'data_pemasaran.*.harga_jual' => 'nullable|numeric',
            // Tenaga Kerja
            'wni_laki_tetap' => 'nullable|integer',
            'wni_laki_tidak_tetap' => 'nullable|integer',
            'wni_laki_keluarga' => 'nullable|integer',
            'wni_perempuan_tetap' => 'nullable|integer',
            'wni_perempuan_tidak_tetap' => 'nullable|integer',
            'wni_perempuan_keluarga' => 'nullable|integer',
            'wna_laki_tetap' => 'nullable|integer',
            'wna_laki_tidak_tetap' => 'nullable|integer',
            'wna_laki_keluarga' => 'nullable|integer',
            'wna_perempuan_tetap' => 'nullable|integer',
            'wna_perempuan_tidak_tetap' => 'nullable|integer',
            'wna_perempuan_keluarga' => 'nullable|integer',
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_izin_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_produk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik_pemasar.required' => 'NIK wajib diisi.',
            'nik_pemasar.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'nik_pemasar.unique' => 'NIK ini sudah terdaftar untuk tahun pendataan yang sama. Satu NIK hanya boleh didaftarkan satu kali per tahun.',
            'id_kecamatan.required' => 'Kecamatan dan desa wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'kontak.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
            'jenis_kegiatan_usaha.required' => 'Jenis kegiatan usaha wajib diisi.',
        ]);

        // Map kecamatan_usaha dan desa_usaha ke id_kecamatan_usaha dan id_desa_usaha
        if ($request->has('kecamatan_usaha')) {
            $validated['id_kecamatan_usaha'] = $request->kecamatan_usaha;
            unset($validated['kecamatan_usaha']);
        }
        if ($request->has('desa_usaha')) {
            $validated['id_desa_usaha'] = $request->desa_usaha;
            unset($validated['desa_usaha']);
        }

        $pemasaranSections = [];
        if (isset($validated['pemasaran']) && is_array($validated['pemasaran'])) {
            $pemasaranSections = $validated['pemasaran'];

            unset($validated['pemasaran']);
        }

        unset($validated['kapasitas_terpasang'], $validated['hasil_produksi_kg'], $validated['hasil_produksi_rp'], $validated['bulan_produksi'], $validated['distribusi_pemasaran']);
        unset($validated['data_pemasaran']);

        // Convert array fields to JSON
        if (isset($validated['sertifikat_lahan'])) {
            $validated['sertifikat_lahan'] = json_encode($validated['sertifikat_lahan']);
        }
        if (isset($validated['sertifikat_bangunan'])) {
            $validated['sertifikat_bangunan'] = json_encode($validated['sertifikat_bangunan']);
        }
        if (isset($validated['bulan_produksi'])) {
            $validated['bulan_produksi'] = json_encode($validated['bulan_produksi']);
        }
        if (isset($validated['mesin_peralatan'])) {
            $validated['mesin_peralatan'] = json_encode($validated['mesin_peralatan']);
        }
        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_npwp', 'foto_izin_usaha', 'foto_produk', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = uniqid() . '_' . time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                // Store file and get the path
                $path = $file->storeAs('lampiran/pemasar', $filename, 'public');
                $uploadedFiles[$field] = $path;
            }
        }

        try {
            $pemasar = DB::transaction(function () use ($validated, $uploadedFiles) {
                return Pemasar::create(array_merge(
                    $validated,
                    $uploadedFiles,
                    [
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'created_by' => auth()->user()->id_user,
                        'updated_by' => auth()->user()->id_user,
                    ]
                ));
            });
        } catch (\Throwable $e) {
            foreach ($uploadedFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

        $this->syncPemasaranSections($pemasar->id_pemasar, $pemasaranSections);

        // Notify all admins about new data
        $this->notifyAdmins(
            'create',
            'Data Pemasar Ditambahkan',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data pemasar: ' . $pemasar->nama_lengkap,
            $pemasar->id_pemasar
        );

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil ditambahkan dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Pemasar $pemasar)
    {
        $pemasar->load([
            'kecamatan',
            'desa',
            'pemasaran' => function ($query) {
                $query->orderBy('section_index')->orderBy('id_pemasar_pemasaran');
            },
        ]);
        
        // Cek apakah diakses dari laporan
        $backupData = null;
        if ($request->query('from_report') && in_array($pemasar->status, ['pending', 'rejected'])) {
            $backup = DB::table('pemasar_verified_backup')
                ->where('id_pemasar', $pemasar->id_pemasar)
                ->first();
            
            if ($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $backupData = new Pemasar();
                    
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
                    }

                    $pemasaranRows = [];
                    if (isset($data['pemasaran']) && is_array($data['pemasaran'])) {
                        $pemasaranRows = $data['pemasaran'];
                        unset($data['pemasaran']);
                    }
                    
                    $backupData->forceFill($data);
                    $backupData->exists = true;
                    
                    if (isset($relationships['kecamatan'])) {
                        $kecamatan = new \App\Models\MasterKecamatan();
                        $kecamatan->forceFill($relationships['kecamatan']);
                        $backupData->setRelation('kecamatan', $kecamatan);
                    }
                    if (isset($relationships['desa'])) {
                        $desa = new \App\Models\MasterDesa();
                        $desa->forceFill($relationships['desa']);
                        $backupData->setRelation('desa', $desa);
                    }

                    if (count($pemasaranRows) > 0) {
                        $backupData->setRelation(
                            'pemasaran',
                            collect($pemasaranRows)->map(function ($row) {
                                $pemasaran = new PemasarPemasaran();
                                $pemasaran->forceFill((array) $row);
                                $pemasaran->exists = true;
                                return $pemasaran;
                            })
                        );
                    }
                }
            }
        }
        
        return view('pages.pemasar.show', compact('pemasar', 'backupData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemasar $pemasar)
    {
        $pemasar->load([
            'pemasaran' => function ($query) {
                $query->orderBy('section_index')->orderBy('id_pemasar_pemasaran');
            },
        ]);
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')
            ->where('tipe', 'pemasar')
            ->orderBy('nama_komoditas')
            ->get();
        return view('pages.pemasar.edit', compact('pemasar', 'kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemasar $pemasar)
    {
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nik_pemasar' => [
                'required',
                'digits:16',
                Rule::unique('pemasars', 'nik_pemasar')
                    ->where(function ($query) use ($request) {
                        return $query->where('tahun_pendataan', $request->tahun_pendataan);
                    })
                    ->ignore($pemasar->id_pemasar, 'id_pemasar')
            ],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'no_npwp' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'alamat' => 'nullable|string',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'nama_usaha' => 'nullable|string|max:255',
            'nama_kelompok' => 'nullable|string|max:255',
            'npwp_usaha' => 'nullable|string|max:255',
            'alamat_usaha' => 'nullable|string',
            'telp_usaha' => 'nullable|string|max:255',
            'email_usaha' => 'nullable|email|max:255',
            'skala_usaha' => 'nullable|string',
            'status_usaha' => 'nullable|string',
            'tahun_mulai_usaha' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'kontak' => 'required|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'kecamatan_usaha' => 'nullable|exists:master_kecamatans,id_kecamatan',
            'desa_usaha' => 'nullable|exists:master_desas,id_desa',
            // Investasi
            'mesin_peralatan' => 'nullable|array',
            'mesin_peralatan.*.jenis_mesin' => 'nullable|string',
            'mesin_peralatan.*.kapasitas' => 'nullable|string',
            'mesin_peralatan.*.jumlah' => 'nullable|integer',
            'mesin_peralatan.*.asal' => 'nullable|string',
            'investasi_tanah' => 'nullable|numeric',
            'investasi_gedung' => 'nullable|numeric',
            'investasi_mesin_peralatan' => 'nullable|numeric',
            'investasi_kendaraan' => 'nullable|numeric',
            'investasi_lain_lain' => 'nullable|numeric',
            'investasi_sub_jumlah' => 'nullable|numeric',
            'modal_kerja_1_bulan' => 'nullable|numeric',
            'modal_kerja_sub_jumlah' => 'nullable|numeric',
            'modal_sendiri' => 'nullable|numeric',
            'laba_ditanam' => 'nullable|numeric',
            'modal_pinjam' => 'nullable|numeric',
            'sertifikat_lahan' => 'nullable|array',
            'luas_lahan' => 'nullable|numeric',
            'nilai_lahan' => 'nullable|numeric',
            'sertifikat_bangunan' => 'nullable|array',
            'luas_bangunan' => 'nullable|numeric',
            'nilai_bangunan' => 'nullable|numeric',
            'bulan_produksi' => 'nullable|array',
            'distribusi_pemasaran' => 'nullable|string',
            // Production Fields
            'kapasitas_terpasang' => 'nullable|numeric',
            'hasil_produksi_kg' => 'nullable|numeric',
            'hasil_produksi_rp' => 'nullable|numeric',
            // New pemasaran array structure (multiple sections)
            'pemasaran' => 'nullable|array',
            'pemasaran.*.kapasitas_terpasang' => 'nullable|numeric',
            'pemasaran.*.hasil_produksi_kg' => 'nullable|numeric',
            'pemasaran.*.hasil_produksi_rp' => 'nullable|numeric',
            'pemasaran.*.bulan_produksi' => 'nullable|array',
            'pemasaran.*.distribusi_pemasaran' => 'nullable|string',
            'pemasaran.*.data_pemasaran' => 'nullable|array',
            'pemasaran.*.data_pemasaran.*.jenis_ikan' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.komoditas' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.jumlah_volume' => 'nullable|numeric',
            'pemasaran.*.data_pemasaran.*.asal_ikan' => 'nullable|string',
            'pemasaran.*.data_pemasaran.*.harga_beli' => 'nullable|numeric',
            'pemasaran.*.data_pemasaran.*.harga_jual' => 'nullable|numeric',
            // Old structure (backward compatibility)
            'data_pemasaran' => 'nullable|array',
            'data_pemasaran.*.jenis_ikan' => 'nullable|string',
            'data_pemasaran.*.komoditas' => 'nullable|string',
            'data_pemasaran.*.jumlah_volume' => 'nullable|numeric',
            'data_pemasaran.*.kebutuhan_min' => 'nullable|numeric',
            'data_pemasaran.*.kebutuhan_max' => 'nullable|numeric',
            'data_pemasaran.*.asal_ikan' => 'nullable|string',
            'data_pemasaran.*.harga_beli' => 'nullable|numeric',
            'data_pemasaran.*.harga_jual' => 'nullable|numeric',
            // Tenaga Kerja
            'wni_laki_tetap' => 'nullable|integer',
            'wni_laki_tidak_tetap' => 'nullable|integer',
            'wni_laki_keluarga' => 'nullable|integer',
            'wni_perempuan_tetap' => 'nullable|integer',
            'wni_perempuan_tidak_tetap' => 'nullable|integer',
            'wni_perempuan_keluarga' => 'nullable|integer',
            'wna_laki_tetap' => 'nullable|integer',
            'wna_laki_tidak_tetap' => 'nullable|integer',
            'wna_laki_keluarga' => 'nullable|integer',
            'wna_perempuan_tetap' => 'nullable|integer',
            'wna_perempuan_tidak_tetap' => 'nullable|integer',
            'wna_perempuan_keluarga' => 'nullable|integer',
            // Izin Usaha
            'nib' => 'nullable|string|max:255',
            'npwp_izin' => 'nullable|string|max:255',
            'kusuka' => 'nullable|string|max:255',
            'pengesahan_menkumham' => 'nullable|string|max:255',
            'tdu_php' => 'nullable|string|max:255',
            'sppl' => 'nullable|string|max:255',
            'siup_perdagangan' => 'nullable|string|max:255',
            'akta_pendiri_usaha' => 'nullable|string|max:255',
            'imb' => 'nullable|string|max:255',
            'siup_perikanan' => 'nullable|string|max:255',
            'ukl_upl' => 'nullable|string|max:255',
            'amdal' => 'nullable|string|max:255',
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_npwp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_izin_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_produk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik_pemasar.required' => 'NIK wajib diisi.',
            'nik_pemasar.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'nik_pemasar.unique' => 'NIK ini sudah terdaftar untuk tahun pendataan yang sama. Satu NIK hanya boleh didaftarkan satu kali per tahun.',
            'id_kecamatan.required' => 'Kecamatan dan desa wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'kontak.required' => 'Nomor telepon wajib diisi',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
            'jenis_kegiatan_usaha.required' => 'Jenis kegiatan usaha wajib diisi.',
        ]);

        // Map kecamatan_usaha dan desa_usaha ke id_kecamatan_usaha dan id_desa_usaha
        if ($request->has('kecamatan_usaha')) {
            $validated['id_kecamatan_usaha'] = $request->kecamatan_usaha;
            unset($validated['kecamatan_usaha']);
        }
        if ($request->has('desa_usaha')) {
            $validated['id_desa_usaha'] = $request->desa_usaha;
            unset($validated['desa_usaha']);
        }

        $pemasaranSections = [];
        if (isset($validated['pemasaran']) && is_array($validated['pemasaran'])) {
            $pemasaranSections = $validated['pemasaran'];

            unset($validated['pemasaran']);
        }

        unset($validated['kapasitas_terpasang'], $validated['hasil_produksi_kg'], $validated['hasil_produksi_rp'], $validated['bulan_produksi'], $validated['distribusi_pemasaran']);
        unset($validated['data_pemasaran']);

        // Convert array fields to JSON
        if (isset($validated['sertifikat_lahan'])) {
            $validated['sertifikat_lahan'] = json_encode($validated['sertifikat_lahan']);
        }
        if (isset($validated['sertifikat_bangunan'])) {
            $validated['sertifikat_bangunan'] = json_encode($validated['sertifikat_bangunan']);
        }
        if (isset($validated['bulan_produksi'])) {
            $validated['bulan_produksi'] = json_encode($validated['bulan_produksi']);
        }
        if (isset($validated['mesin_peralatan'])) {
            $validated['mesin_peralatan'] = json_encode($validated['mesin_peralatan']);
        }
        // Handle file uploads
        $fotoFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_npwp', 'foto_izin_usaha', 'foto_produk', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        $uploadedFiles = [];
        $oldFilesToDelete = [];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                // Upload file baru
                $file = $request->file($field);
                $filename = uniqid() . '_' . time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $storedPath = $file->storeAs('lampiran/pemasar', $filename, 'public');
                $validated[$field] = $storedPath;
                $uploadedFiles[] = $storedPath;

                if ($pemasar->$field) {
                    $oldFilesToDelete[] = $pemasar->$field;
                }
            }
        }

        // Ensure verification and audit fields are never overwritten
        unset($validated['created_by'], $validated['verified_by'], $validated['verified_at']);
        
        try {
            DB::transaction(function () use ($pemasar, $validated) {
                // BACKUP DATA VERIFIED SEBELUM EDIT
                // Jika data saat ini sudah verified, backup dulu sebelum ubah ke pending
                if ($pemasar->status === 'verified') {
                    // Load semua relasi untuk backup
                    $pemasar->load([
                        'kecamatan',
                        'desa',
                        'kecamatanUsaha',
                        'desaUsaha',
                        'pemasaran' => function ($query) {
                            $query->orderBy('section_index')->orderBy('id_pemasar_pemasaran');
                        },
                    ]);

                    // Simpan snapshot data verified ke tabel backup
                    DB::table('pemasar_verified_backup')->updateOrInsert(
                        ['id_pemasar' => $pemasar->id_pemasar],
                        [
                            'data_verified' => json_encode($pemasar->toArray()),
                            'backed_up_at' => now()
                        ]
                    );
                }

                $pemasar->update(array_merge(
                    $validated,
                    [
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'updated_by' => auth()->user()->id_user
                    ]
                ));
            });
        } catch (\Throwable $e) {
            foreach ($uploadedFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }

        $this->syncPemasaranSections($pemasar->id_pemasar, $pemasaranSections);

        foreach ($oldFilesToDelete as $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        // Notify all admins about data update
        $this->notifyAdmins(
            'update',
            'Data Pemasar Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data pemasar: ' . $pemasar->nama_lengkap,
            $pemasar->id_pemasar
        );

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil diperbarui dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemasar $pemasar)
    {
        $nama = $pemasar->nama_lengkap;
        $idPemasar = $pemasar->id_pemasar;
        $createdBy = $pemasar->created_by;
        $updatedBy = $pemasar->updated_by;
        $pemasar->delete();

        $this->notifyAdmins(
            'delete',
            'Data Pemasar Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pemasar: ' . $nama,
            $idPemasar,
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
                'Data Pemasar Dihapus',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pemasar: ' . $nama,
                $idPemasar
            );
        }

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil dihapus.');
    }

    /**
     * Verify data (admin only)
     */
    public function verify(Pemasar $pemasar)
    {
        // Cek role - hanya admin yang bisa verify
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pemasar.index')->with('error', 'Hanya admin yang dapat memverifikasi data.');
        }

        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $pemasar->created_by;
        $updatedBy = $pemasar->updated_by;

        $pemasar->update([
            'status' => 'verified',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => null,
        ]);

        // Hapus backup karena data baru sudah diverifikasi
        DB::table('pemasar_verified_backup')
            ->where('id_pemasar', $pemasar->id_pemasar)
            ->delete();

        $this->notifyAdmins(
            'verified',
            'Data Pemasar Diverifikasi',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pemasar: ' . $pemasar->nama_lengkap,
            $pemasar->id_pemasar,
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
                'Data Pemasar Diverifikasi',
                'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pemasar: ' . $pemasar->nama_lengkap,
                $pemasar->id_pemasar
            );
        }

        return redirect()->route('pemasar.index')->with('success', 'Data pemasar berhasil diverifikasi dan status diubah menjadi VERIFIED.');
    }

    /**
     * Reject data (admin only)
     */
    public function reject(Request $request, Pemasar $pemasar)
    {
        // Cek role - hanya admin yang bisa reject
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pemasar.index')->with('error', 'Hanya admin yang dapat menolak data.');
        }

        $validated = $request->validate([
            'catatan_perbaikan' => 'required|string|max:2000',
        ], [
            'catatan_perbaikan.required' => 'Catatan perbaikan wajib diisi saat menolak data.',
        ]);

        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $pemasar->created_by;
        $updatedBy = $pemasar->updated_by;

        $pemasar->update([
            'status' => 'rejected',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => $validated['catatan_perbaikan'],
        ]);

        $this->notifyAdmins(
            'rejected',
            'Data Pemasar Ditolak',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pemasar: ' . $pemasar->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
            $pemasar->id_pemasar,
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
                'Data Pemasar Ditolak',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pemasar: ' . $pemasar->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
                $pemasar->id_pemasar
            );
        }

        return redirect()->route('pemasar.index')->with('warning', 'Data pemasar ditolak dan status diubah menjadi REJECTED.');
    }

    private function syncPemasaranSections($idPemasar, array $sections): void
    {
        PemasarPemasaran::where('id_pemasar', $idPemasar)->delete();

        $payload = $this->buildPemasaranPayload($idPemasar, $sections);
        if (count($payload)) {
            PemasarPemasaran::insert($payload);
        }
    }

    private function buildPemasaranPayload($idPemasar, array $sections): array
    {
        $payload = [];

        foreach ($sections as $sectionIndex => $section) {
            if (!is_array($section)) {
                continue;
            }

            $sectionRows = $section['data_pemasaran'] ?? [];
            if (!is_array($sectionRows)) {
                continue;
            }

            foreach ($sectionRows as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $payload[] = [
                    'id_pemasar' => $idPemasar,
                    'section_index' => (int) $sectionIndex,
                    'kapasitas_terpasang' => isset($section['kapasitas_terpasang']) ? (float) $section['kapasitas_terpasang'] : null,
                    'hasil_produksi_kg' => isset($section['hasil_produksi_kg']) ? (float) $section['hasil_produksi_kg'] : null,
                    'hasil_produksi_rp' => isset($section['hasil_produksi_rp']) ? (float) $section['hasil_produksi_rp'] : null,
                    'bulan_produksi' => isset($section['bulan_produksi']) ? json_encode($section['bulan_produksi']) : null,
                    'distribusi_pemasaran' => $section['distribusi_pemasaran'] ?? null,
                    'komoditas' => $row['komoditas'] ?? $row['jenis_ikan'] ?? null,
                    'asal_ikan' => $row['asal_ikan'] ?? null,
                    'jumlah_volume' => isset($row['jumlah_volume']) ? (float) $row['jumlah_volume'] : null,
                    'harga_beli' => isset($row['harga_beli']) ? (float) $row['harga_beli'] : null,
                    'harga_jual' => isset($row['harga_jual']) ? (float) $row['harga_jual'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        return $payload;
    }
}
