<?php

namespace App\Http\Controllers;

use App\Models\Pengolah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PengolahController extends Controller
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
            'module' => 'pengolah',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('pengolah.show', $moduleId) : route('pengolah.index'),
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

        $query = Pengolah::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('id_pengolah');

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
                  ->orWhere('nik_pengolah', 'like', "%{$search}%")
                  ->orWhere('komoditas', 'like', "%{$search}%")
                  ->orWhereHas('kecamatan', function ($qq) use ($search) {
                      $qq->where('nama_kecamatan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('desa', function ($qq) use ($search) {
                      $qq->where('nama_desa', 'like', "%{$search}%");
                  });
            });
        }

        $pengolahs = $query->paginate($perPage)->withQueryString();

        return view('pages.pengolah.index', [
            'pengolahs' => $pengolahs,
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
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')
            ->where('tipe', 'pengolah')
            ->orderBy('nama_komoditas')
            ->get();
        return view('pages.pengolah.create', compact('kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nama_lengkap' => 'required|string|max:255',
            'nik_pengolah' => [
                'required',
                'digits:16',
                Rule::unique('pengolahs', 'nik_pengolah')->where(function ($query) use ($request) {
                    return $query->where('tahun_pendataan', $request->tahun_pendataan);
                })
            ],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_pengolahan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kontak' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'no_npwp' => 'nullable|string|max:255',
            // Profil Usaha
            'nama_usaha' => 'nullable|string|max:255',
            'nama_kelompok' => 'nullable|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'tahun_mulai_usaha' => 'nullable|integer',
            'kecamatan_usaha' => 'nullable|exists:master_kecamatans,id_kecamatan',
            'desa_usaha' => 'nullable|exists:master_desas,id_desa',
            'alamat_lengkap_usaha' => 'nullable|string',
            'latitude_usaha' => 'nullable|numeric',
            'longitude_usaha' => 'nullable|numeric',
            // Produksi Data
            'produksi' => 'nullable|array',
            // Tenaga Kerja Data
            'tenaga_kerja_wni_laki_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_laki_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_laki_keluarga' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_keluarga' => 'nullable|integer',
            'tenaga_kerja_wna_laki_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_laki_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_laki_keluarga' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_keluarga' => 'nullable|integer',
            'npwp_usaha' => 'nullable|string|max:255',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'skala_usaha' => 'nullable|string',
            'status_usaha' => 'nullable|string',
            'komoditas' => 'nullable|string',
            // Izin Usaha
            'nib' => 'nullable|string|max:255',
            'kusuka' => 'nullable|string|max:255',
            'pengesahan_menkumham' => 'nullable|string|max:255',
            'tdu_php' => 'nullable|string|max:255',
            'akta_pendirian_usaha' => 'nullable|string|max:255',
            'imb' => 'nullable|string|max:255',
            'siup_perikanan' => 'nullable|string|max:255',
            'siup_perdagangan' => 'nullable|string|max:255',
            'sppl' => 'nullable|string|max:255',
            'ukl_upl' => 'nullable|string|max:255',
            'amdal' => 'nullable|string|max:255',
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik_pengolah.required' => 'NIK wajib diisi.',
            'nik_pengolah.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'nik_pengolah.unique' => 'NIK ini sudah terdaftar untuk tahun pendataan yang sama. Satu NIK hanya boleh didaftarkan satu kali per tahun.',
            'id_kecamatan.required' => 'Kecamatan dan desa wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'kontak.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
            'jenis_kegiatan_usaha.required' => 'Jenis kegiatan usaha wajib diisi.',
        ]);

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = uniqid() . '_' . time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                // Store file and get the path
                $path = $file->storeAs('lampiran/pengolah', $filename, 'public');
                $uploadedFiles[$field] = $path;
            }
        }

        // Map form field names to database field names
        $mappedData = [];
        $mappedData['id_kecamatan_usaha'] = $validated['kecamatan_usaha'] ?? null;
        $mappedData['id_desa_usaha'] = $validated['desa_usaha'] ?? null;
        $mappedData['alamat_usaha'] = $validated['alamat_lengkap_usaha'] ?? null;
        $mappedData['latitude'] = $validated['latitude_usaha'] ?? null;
        $mappedData['longitude'] = $validated['longitude_usaha'] ?? null;

        // Remove form field names that don't exist in database
        unset($validated['kecamatan_usaha'], $validated['desa_usaha'], $validated['alamat_lengkap_usaha'], $validated['latitude_usaha'], $validated['longitude_usaha']);

        // Handle produksi data (from Alpine.js)
        if ($request->has('produksi')) {
            $mappedData['produksi_data'] = $request->produksi;
            unset($validated['produksi']);
        }

        // Handle tenaga kerja data
        $tenagaKerjaData = [
            'wni_laki_tetap' => $validated['tenaga_kerja_wni_laki_tetap'] ?? 0,
            'wni_laki_tidak_tetap' => $validated['tenaga_kerja_wni_laki_tidak_tetap'] ?? 0,
            'wni_laki_keluarga' => $validated['tenaga_kerja_wni_laki_keluarga'] ?? 0,
            'wni_perempuan_tetap' => $validated['tenaga_kerja_wni_perempuan_tetap'] ?? 0,
            'wni_perempuan_tidak_tetap' => $validated['tenaga_kerja_wni_perempuan_tidak_tetap'] ?? 0,
            'wni_perempuan_keluarga' => $validated['tenaga_kerja_wni_perempuan_keluarga'] ?? 0,
            'wna_laki_tetap' => $validated['tenaga_kerja_wna_laki_tetap'] ?? 0,
            'wna_laki_tidak_tetap' => $validated['tenaga_kerja_wna_laki_tidak_tetap'] ?? 0,
            'wna_laki_keluarga' => $validated['tenaga_kerja_wna_laki_keluarga'] ?? 0,
            'wna_perempuan_tetap' => $validated['tenaga_kerja_wna_perempuan_tetap'] ?? 0,
            'wna_perempuan_tidak_tetap' => $validated['tenaga_kerja_wna_perempuan_tidak_tetap'] ?? 0,
            'wna_perempuan_keluarga' => $validated['tenaga_kerja_wna_perempuan_keluarga'] ?? 0,
        ];
        $mappedData['tenaga_kerja_data'] = $tenagaKerjaData;

        // Remove individual tenaga kerja fields
        unset(
            $validated['tenaga_kerja_wni_laki_tetap'],
            $validated['tenaga_kerja_wni_laki_tidak_tetap'],
            $validated['tenaga_kerja_wni_laki_keluarga'],
            $validated['tenaga_kerja_wni_perempuan_tetap'],
            $validated['tenaga_kerja_wni_perempuan_tidak_tetap'],
            $validated['tenaga_kerja_wni_perempuan_keluarga'],
            $validated['tenaga_kerja_wna_laki_tetap'],
            $validated['tenaga_kerja_wna_laki_tidak_tetap'],
            $validated['tenaga_kerja_wna_laki_keluarga'],
            $validated['tenaga_kerja_wna_perempuan_tetap'],
            $validated['tenaga_kerja_wna_perempuan_tidak_tetap'],
            $validated['tenaga_kerja_wna_perempuan_keluarga']
        );

        try {
            // Simpan data ke tabel pengolahs
            $pengolah = DB::transaction(function () use ($validated, $mappedData, $uploadedFiles) {
                return Pengolah::create(array_merge(
                    $validated,
                    $mappedData,
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

        // Notify all admins about new data
        $this->notifyAdmins(
            'create',
            'Data Pengolah Ditambahkan',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data pengolah: ' . $pengolah->nama_lengkap,
            $pengolah->id_pengolah
        );

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil ditambahkan dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Pengolah $pengolah)
    {
        $pengolah->load(['kecamatan', 'desa']);
        
        // Cek apakah diakses dari laporan
        $backupData = null;
        if ($request->query('from_report') && in_array($pengolah->status, ['pending', 'rejected'])) {
            $backup = DB::table('pengolah_verified_backup')
                ->where('id_pengolah', $pengolah->id_pengolah)
                ->first();
            
            if ($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $backupData = new Pengolah();
                    
                    $relationships = [];
                    if (isset($data['kecamatan'])) {
                        $relationships['kecamatan'] = $data['kecamatan'];
                        unset($data['kecamatan']);
                    }
                    if (isset($data['desa'])) {
                        $relationships['desa'] = $data['desa'];
                        unset($data['desa']);
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
                }
            }
        }
        
        return view('pages.pengolah.show', compact('pengolah', 'backupData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengolah $pengolah)
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')
            ->where('tipe', 'pengolah')
            ->orderBy('nama_komoditas')
            ->get();
        return view('pages.pengolah.edit', compact('pengolah', 'kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengolah $pengolah)
    {
        // Validasi data
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'nama_lengkap' => 'required|string|max:255',
            'nik_pengolah' => [
                'required',
                'digits:16',
                Rule::unique('pengolahs', 'nik_pengolah')
                    ->where(function ($query) use ($request) {
                        return $query->where('tahun_pendataan', $request->tahun_pendataan);
                    })
                    ->ignore($pengolah->id_pengolah, 'id_pengolah')
            ],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_pengolahan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kontak' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'no_npwp' => 'nullable|string|max:255',
            // Profil Usaha
            'nama_usaha' => 'nullable|string|max:255',
            'nama_kelompok' => 'nullable|string|max:255',
            'komoditas' => 'nullable|string|max:255',
            'tahun_mulai_usaha' => 'nullable|integer',
            'kecamatan_usaha' => 'nullable|exists:master_kecamatans,id_kecamatan',
            'desa_usaha' => 'nullable|exists:master_desas,id_desa',
            'alamat_lengkap_usaha' => 'nullable|string',
            'latitude_usaha' => 'nullable|numeric',
            'longitude_usaha' => 'nullable|numeric',
            'npwp_usaha' => 'nullable|string|max:255',
            'telp_usaha' => 'nullable|string|max:20',
            'email_usaha' => 'nullable|email|max:255',
            'skala_usaha' => 'nullable|string',
            'status_usaha' => 'nullable|string',
            'komoditas' => 'nullable|string',
            // Produksi Data
            'produksi' => 'nullable|array',
            // Tenaga Kerja Data
            'tenaga_kerja_wni_laki_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_laki_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_laki_keluarga' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wni_perempuan_keluarga' => 'nullable|integer',
            'tenaga_kerja_wna_laki_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_laki_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_laki_keluarga' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_tidak_tetap' => 'nullable|integer',
            'tenaga_kerja_wna_perempuan_keluarga' => 'nullable|integer',
            // Izin Usaha
            'nib' => 'nullable|string|max:255',
            'kusuka' => 'nullable|string|max:255',
            'pengesahan_menkumham' => 'nullable|string|max:255',
            'tdu_php' => 'nullable|string|max:255',
            'akta_pendirian_usaha' => 'nullable|string|max:255',
            'imb' => 'nullable|string|max:255',
            'siup_perikanan' => 'nullable|string|max:255',
            'siup_perdagangan' => 'nullable|string|max:255',
            'sppl' => 'nullable|string|max:255',
            'ukl_upl' => 'nullable|string|max:255',
            'amdal' => 'nullable|string|max:255',
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nik_pengolah.required' => 'NIK wajib diisi.',
            'nik_pengolah.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'nik_pengolah.unique' => 'NIK ini sudah terdaftar untuk tahun pendataan yang sama. Satu NIK hanya boleh didaftarkan satu kali per tahun.',
            'id_kecamatan.required' => 'Kecamatan dan desa wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'kontak.required' => 'Nomor telepon wajib diisi.',
            'email.email' => 'Penulisan email salah atau tidak sesuai format.',
            'jenis_kegiatan_usaha.required' => 'Jenis kegiatan usaha wajib diisi.',
        ]);

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        $uploadedFiles = [];
        $oldFilesToDelete = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = uniqid() . '_' . time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                // Store file and get the path
                $path = $file->storeAs('lampiran/pengolah', $filename, 'public');
                $uploadedFiles[$field] = $path;

                if ($pengolah->$field) {
                    $oldFilesToDelete[] = $pengolah->$field;
                }
            }
        }

        // Map form field names to database field names
        $mappedData = [];
        $mappedData['id_kecamatan_usaha'] = $validated['kecamatan_usaha'] ?? null;
        $mappedData['id_desa_usaha'] = $validated['desa_usaha'] ?? null;
        $mappedData['alamat_usaha'] = $validated['alamat_lengkap_usaha'] ?? null;
        $mappedData['latitude'] = $validated['latitude_usaha'] ?? null;
        $mappedData['longitude'] = $validated['longitude_usaha'] ?? null;

        // Remove form field names that don't exist in database
        unset($validated['kecamatan_usaha'], $validated['desa_usaha'], $validated['alamat_lengkap_usaha'], $validated['latitude_usaha'], $validated['longitude_usaha']);

        // Handle produksi data (from Alpine.js)
        if ($request->has('produksi')) {
            $mappedData['produksi_data'] = $request->produksi;
            unset($validated['produksi']);
        }

        // Handle tenaga kerja data
        $tenagaKerjaData = [
            'wni_laki_tetap' => $validated['tenaga_kerja_wni_laki_tetap'] ?? 0,
            'wni_laki_tidak_tetap' => $validated['tenaga_kerja_wni_laki_tidak_tetap'] ?? 0,
            'wni_laki_keluarga' => $validated['tenaga_kerja_wni_laki_keluarga'] ?? 0,
            'wni_perempuan_tetap' => $validated['tenaga_kerja_wni_perempuan_tetap'] ?? 0,
            'wni_perempuan_tidak_tetap' => $validated['tenaga_kerja_wni_perempuan_tidak_tetap'] ?? 0,
            'wni_perempuan_keluarga' => $validated['tenaga_kerja_wni_perempuan_keluarga'] ?? 0,
            'wna_laki_tetap' => $validated['tenaga_kerja_wna_laki_tetap'] ?? 0,
            'wna_laki_tidak_tetap' => $validated['tenaga_kerja_wna_laki_tidak_tetap'] ?? 0,
            'wna_laki_keluarga' => $validated['tenaga_kerja_wna_laki_keluarga'] ?? 0,
            'wna_perempuan_tetap' => $validated['tenaga_kerja_wna_perempuan_tetap'] ?? 0,
            'wna_perempuan_tidak_tetap' => $validated['tenaga_kerja_wna_perempuan_tidak_tetap'] ?? 0,
            'wna_perempuan_keluarga' => $validated['tenaga_kerja_wna_perempuan_keluarga'] ?? 0,
        ];
        $mappedData['tenaga_kerja_data'] = $tenagaKerjaData;

        // Remove individual tenaga kerja fields
        unset(
            $validated['tenaga_kerja_wni_laki_tetap'],
            $validated['tenaga_kerja_wni_laki_tidak_tetap'],
            $validated['tenaga_kerja_wni_laki_keluarga'],
            $validated['tenaga_kerja_wni_perempuan_tetap'],
            $validated['tenaga_kerja_wni_perempuan_tidak_tetap'],
            $validated['tenaga_kerja_wni_perempuan_keluarga'],
            $validated['tenaga_kerja_wna_laki_tetap'],
            $validated['tenaga_kerja_wna_laki_tidak_tetap'],
            $validated['tenaga_kerja_wna_laki_keluarga'],
            $validated['tenaga_kerja_wna_perempuan_tetap'],
            $validated['tenaga_kerja_wna_perempuan_tidak_tetap'],
            $validated['tenaga_kerja_wna_perempuan_keluarga']
        );

        // Update data di tabel
        // Ensure verification and audit fields are never overwritten
        unset($validated['created_by'], $validated['verified_by'], $validated['verified_at']);
        unset($mappedData['created_by'], $mappedData['verified_by'], $mappedData['verified_at']);
        
        try {
            DB::transaction(function () use ($pengolah, $validated, $mappedData, $uploadedFiles) {
                // BACKUP DATA VERIFIED SEBELUM EDIT
                // Jika data saat ini sudah verified, backup dulu sebelum ubah ke pending
                if ($pengolah->status === 'verified') {
                    // Load semua relasi untuk backup
                    $pengolah->load(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha']);

                    // Simpan snapshot data verified ke tabel backup
                    DB::table('pengolah_verified_backup')->updateOrInsert(
                        ['id_pengolah' => $pengolah->id_pengolah],
                        [
                            'data_verified' => json_encode($pengolah->toArray()),
                            'backed_up_at' => now()
                        ]
                    );
                }

                $updateData = array_merge(
                    $validated,
                    $mappedData,
                    $uploadedFiles,
                    [
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'updated_by' => auth()->user()->id_user,
                        'updated_at' => now()
                    ]
                );

                $pengolah->update($updateData);
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
            'Data Pengolah Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data pengolah: ' . $pengolah->nama_lengkap,
            $pengolah->id_pengolah
        );

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil diperbarui dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengolah $pengolah)
    {
        $nama = $pengolah->nama_lengkap;
        $idPengolah = $pengolah->id_pengolah;
        $createdBy = $pengolah->created_by;
        $updatedBy = $pengolah->updated_by;
        $pengolah->delete();

        $this->notifyAdmins(
            'delete',
            'Data Pengolah Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pengolah: ' . $nama,
            $idPengolah,
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
                'Data Pengolah Dihapus',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pengolah: ' . $nama,
                $idPengolah
            );
        }

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil dihapus.');
    }

    /**
     * Verify data (admin only)
     */
    public function verify(Pengolah $pengolah)
    {
        // Cek role - hanya admin yang bisa verify
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pengolah.index')->with('error', 'Hanya admin yang dapat memverifikasi data.');
        }

        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $pengolah->created_by;
        $updatedBy = $pengolah->updated_by;

        $pengolah->update([
            'status' => 'verified',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => null,
        ]);

        // Hapus backup karena data baru sudah diverifikasi
        DB::table('pengolah_verified_backup')
            ->where('id_pengolah', $pengolah->id_pengolah)
            ->delete();

        $this->notifyAdmins(
            'verified',
            'Data Pengolah Diverifikasi',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pengolah: ' . $pengolah->nama_lengkap,
            $pengolah->id_pengolah,
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
                'Data Pengolah Diverifikasi',
                'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data pengolah: ' . $pengolah->nama_lengkap,
                $pengolah->id_pengolah
            );
        }

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil diverifikasi dan status diubah menjadi VERIFIED.');
    }

    /**
     * Reject data (admin only)
     */
    public function reject(Request $request, Pengolah $pengolah)
    {
        // Cek role - hanya admin yang bisa reject
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('pengolah.index')->with('error', 'Hanya admin yang dapat menolak data.');
        }

        $validated = $request->validate([
            'catatan_perbaikan' => 'required|string|max:2000',
        ], [
            'catatan_perbaikan.required' => 'Catatan perbaikan wajib diisi saat menolak data.',
        ]);

        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $pengolah->created_by;
        $updatedBy = $pengolah->updated_by;

        $pengolah->update([
            'status' => 'rejected',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => $validated['catatan_perbaikan'],
        ]);

        $this->notifyAdmins(
            'rejected',
            'Data Pengolah Ditolak',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pengolah: ' . $pengolah->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
            $pengolah->id_pengolah,
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
                'Data Pengolah Ditolak',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data pengolah: ' . $pengolah->nama_lengkap . '. Catatan: ' . $validated['catatan_perbaikan'],
                $pengolah->id_pengolah
            );
        }

        return redirect()->route('pengolah.index')->with('warning', 'Data pengolah ditolak dan status diubah menjadi REJECTED.');
    }
}
