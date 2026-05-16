<?php

namespace App\Http\Controllers;

use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HargaIkanSegarController extends Controller
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
            'module' => 'harga_ikan_segar',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('harga-ikan-segar.show', $moduleId) : route('harga-ikan-segar.index'),
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

        $query = HargaIkanSegar::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('tanggal_input')
            ->orderByDesc('id_harga');

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
                $q->where('jenis_ikan', 'like', "%{$search}%")
                  ->orWhere('nik_pedagang', 'like', "%{$search}%")
                  ->orWhere('nama_pedagang', 'like', "%{$search}%")
                  ->orWhere('nama_pasar', 'like', "%{$search}%")
                  ->orWhere('ukuran', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%")
                  ->orWhereHas('kecamatan', function ($qq) use ($search) {
                      $qq->where('nama_kecamatan', 'like', "%{$search}%");
                  })
                  ->orWhereHas('desa', function ($qq) use ($search) {
                      $qq->where('nama_desa', 'like', "%{$search}%");
                  });
            });
        }

        $hargaIkanSegars = $query->paginate($perPage)->withQueryString();

        return view('pages.harga-ikan-segar.index', [
            'hargaIkanSegars' => $hargaIkanSegars,
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
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();
        $desas = MasterDesa::orderBy('nama_desa')->get();
        $pasars = \App\Models\Pasar::orderBy('nama_pasar')->get();
        
        return view('pages.harga-ikan-segar.create', compact('kecamatans', 'desas', 'pasars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'tanggal_input' => 'required|date',
            'nama_pasar' => 'required|string|max:100',
            'nama_pedagang' => 'required|string|max:100',
            'nik_pedagang' => 'required|digits:16',
            'asal_ikan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'ikan' => 'required|array|min:1',
            'ikan.*.jenis_ikan' => 'required|string|max:100',
            'ikan.*.ukuran' => 'nullable|string|max:50',
            'ikan.*.satuan' => 'required|string|max:20',
            'ikan.*.harga_produsen' => 'required|numeric|min:0',
            'ikan.*.harga_konsumen' => 'required|numeric|min:0',
            'ikan.*.kuantitas_perminggu' => 'required|numeric|min:0',
        ], [
            'tahun_pendataan.required' => 'Tahun pendataan wajib diisi.',
            'id_kecamatan.required' => 'Kecamatan wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'tanggal_input.required' => 'Tanggal input wajib diisi.',
            'nama_pasar.required' => 'Nama pasar wajib diisi.',
            'nama_pedagang.required' => 'Nama pedagang wajib diisi.',
            'nik_pedagang.required' => 'NIK wajib diisi.',
            'nik_pedagang.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'asal_ikan.required' => 'Asal ikan wajib diisi.',
            'ikan.required' => 'Detail ikan wajib diisi.',
            'ikan.*.jenis_ikan.required' => 'Jenis ikan wajib diisi.',
            'ikan.*.satuan.required' => 'Satuan wajib diisi.',
            'ikan.*.harga_produsen.required' => 'Harga produsen wajib diisi.',
            'ikan.*.harga_konsumen.required' => 'Harga konsumen wajib diisi.',
            'ikan.*.kuantitas_perminggu.required' => 'Kuantitas perminggu wajib diisi.',
        ]);

        $exists = HargaIkanSegar::where('nik_pedagang', $validated['nik_pedagang'])
            ->where('nama_pasar', $validated['nama_pasar'])
            ->where('tanggal_input', $validated['tanggal_input'])
            ->where('tahun_pendataan', $validated['tahun_pendataan'])
            ->exists();

        if ($exists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'nik_pedagang' => 'data Harga ikan dengan tahun pendataan, tanggal input, nik pedagang, dan nama pasar yang anda masukan sudah terdaftar'
            ]);
        }

        // Loop through each ikan data and create separate records atomically
        $lastCreatedRecord = null;
        try {
            $lastCreatedRecord = DB::transaction(function () use ($validated) {
                $lastRecord = null;

                foreach ($validated['ikan'] as $ikanData) {
                    $lastRecord = HargaIkanSegar::create([
                        'id_kecamatan' => $validated['id_kecamatan'],
                        'id_desa' => $validated['id_desa'],
                        'tanggal_input' => $validated['tanggal_input'],
                        'nama_pasar' => $validated['nama_pasar'],
                        'nama_pedagang' => $validated['nama_pedagang'],
                        'nik_pedagang' => $validated['nik_pedagang'],
                        'asal_ikan' => $validated['asal_ikan'] ?? null,
                        'keterangan' => $validated['keterangan'] ?? null,
                        'jenis_ikan' => $ikanData['jenis_ikan'],
                        'ukuran' => $ikanData['ukuran'] ?? null,
                        'satuan' => $ikanData['satuan'],
                        'harga_produsen' => $ikanData['harga_produsen'] ?? null,
                        'harga_konsumen' => $ikanData['harga_konsumen'] ?? null,
                        'kuantitas_perminggu' => $ikanData['kuantitas_perminggu'] ?? null,
                        'tahun_pendataan' => $validated['tahun_pendataan'],
                        'status' => 'pending',
                        'catatan_perbaikan' => null,
                        'created_by' => auth()->user()->id_user,
                        'updated_by' => auth()->user()->id_user,
                    ]);
                }

                return $lastRecord;
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        // Notify all admins about new data
        if ($lastCreatedRecord) {
            $this->notifyAdmins(
                'create',
                'Data Harga Ikan Ditambahkan',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data harga ikan: ' . $lastCreatedRecord->nama_pedagang,
                $lastCreatedRecord->id_harga
            );
        }

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan berhasil ditambahkan dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::with(['kecamatan', 'desa'])->findOrFail($id);
        
        // Cek apakah diakses dari laporan
        $backupData = null;
        if ($request->query('from_report') && in_array($hargaIkanSegar->status, ['pending', 'rejected'])) {
            $backup = DB::table('harga_ikan_segar_verified_backup')
                ->where('id_harga', $hargaIkanSegar->id_harga)
                ->first();
            
            if ($backup) {
                $data = json_decode($backup->data_verified, true);
                if ($data) {
                    $backupData = new HargaIkanSegar();
                    
                    $relationships = [];
                    foreach (['kecamatan', 'desa', 'pasar'] as $rel) {
                        if (isset($data[$rel])) {
                            $relationships[$rel] = $data[$rel];
                            unset($data[$rel]);
                        }
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
                    if (isset($relationships['pasar'])) {
                        $pasar = new \App\Models\Pasar();
                        $pasar->forceFill($relationships['pasar']);
                        $backupData->setRelation('pasar', $pasar);
                    }
                }
            }
        }
        
        return view('pages.harga-ikan-segar.show', compact('hargaIkanSegar', 'backupData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();
        $desas = MasterDesa::orderBy('nama_desa')->get();
        $pasars = \App\Models\Pasar::orderBy('nama_pasar')->get();
        
        return view('pages.harga-ikan-segar.edit', compact('hargaIkanSegar', 'kecamatans', 'desas', 'pasars'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        $originalUniqueKey = [
            'tahun_pendataan' => $hargaIkanSegar->tahun_pendataan,
            'tanggal_input' => $hargaIkanSegar->tanggal_input,
            'nik_pedagang' => $hargaIkanSegar->nik_pedagang,
            'nama_pasar' => $hargaIkanSegar->nama_pasar,
        ];

        $validated = $request->validate([
            'tahun_pendataan' => 'required|integer|min:2026|max:' . (date('Y') + 5),
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'tanggal_input' => 'required|date',
            'nama_pasar' => 'required|string|max:100',
            'nama_pedagang' => 'required|string|max:100',
            'nik_pedagang' => 'required|digits:16',
            'asal_ikan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'ikan' => 'required|array|min:1',
            'ikan.*.jenis_ikan' => 'required|string|max:100',
            'ikan.*.ukuran' => 'nullable|string|max:50',
            'ikan.*.satuan' => 'required|string|max:20',
            'ikan.*.harga_produsen' => 'required|numeric|min:0',
            'ikan.*.harga_konsumen' => 'required|numeric|min:0',
            'ikan.*.kuantitas_perminggu' => 'required|numeric|min:0',
        ], [
            'tahun_pendataan.required' => 'Tahun pendataan wajib diisi.',
            'id_kecamatan.required' => 'Kecamatan wajib diisi.',
            'id_desa.required' => 'Desa wajib diisi.',
            'tanggal_input.required' => 'Tanggal input wajib diisi.',
            'nama_pasar.required' => 'Nama pasar wajib diisi.',
            'nama_pedagang.required' => 'Nama pedagang wajib diisi.',
            'nik_pedagang.required' => 'NIK wajib diisi.',
            'nik_pedagang.digits' => 'Penulisan NIK salah atau tidak sesuai format.',
            'ikan.required' => 'Detail ikan wajib diisi.',
            'ikan.*.jenis_ikan.required' => 'Jenis ikan wajib diisi.',
            'ikan.*.harga_produsen.required' => 'Harga produsen wajib diisi.',
            'ikan.*.harga_konsumen.required' => 'Harga konsumen wajib diisi.',
            'ikan.*.satuan.required' => 'Satuan wajib diisi.',
            'ikan.*.kuantitas_perminggu.required' => 'Kuantitas perminggu wajib diisi.',
        ]);

        $currentUniqueKey = [
            'tahun_pendataan' => $validated['tahun_pendataan'],
            'tanggal_input' => $validated['tanggal_input'],
            'nik_pedagang' => $validated['nik_pedagang'],
            'nama_pasar' => $validated['nama_pasar'],
        ];

        if ($currentUniqueKey !== $originalUniqueKey) {
            $exists = HargaIkanSegar::where('nik_pedagang', $validated['nik_pedagang'])
                ->where('nama_pasar', $validated['nama_pasar'])
                ->where('tanggal_input', $validated['tanggal_input'])
                ->where('tahun_pendataan', $validated['tahun_pendataan'])
                ->where('id_harga', '!=', $hargaIkanSegar->id_harga)
                ->exists();

            if ($exists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'nik_pedagang' => 'data Harga ikan dengan tahun pendataan, tanggal input, nik pedagang, dan nama pasar yang anda masukan sudah terdaftar'
                ]);
            }
        }

        DB::transaction(function () use ($validated, $hargaIkanSegar) {
            // Ensure verification and audit fields are never overwritten
            unset($validated['created_by'], $validated['verified_by'], $validated['verified_at']);

            // BACKUP DATA VERIFIED SEBELUM EDIT
            // Jika data saat ini sudah verified, backup dulu sebelum ubah ke pending
            if ($hargaIkanSegar->status === 'verified') {
                // Load semua relasi untuk backup
                $hargaIkanSegar->load(['kecamatan', 'desa']);

                // Simpan snapshot data verified ke tabel backup
                DB::table('harga_ikan_segar_verified_backup')->updateOrInsert(
                    ['id_harga' => $hargaIkanSegar->id_harga],
                    [
                        'data_verified' => json_encode($hargaIkanSegar->toArray()),
                        'backed_up_at' => now()
                    ]
                );
            }

            $rows = $validated['ikan'];

            $hargaIkanSegar->update([
                'id_kecamatan' => $validated['id_kecamatan'],
                'id_desa' => $validated['id_desa'],
                'tanggal_input' => $validated['tanggal_input'],
                'nama_pasar' => $validated['nama_pasar'],
                'nama_pedagang' => $validated['nama_pedagang'],
                'nik_pedagang' => $validated['nik_pedagang'],
                'asal_ikan' => $validated['asal_ikan'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'jenis_ikan' => $rows[0]['jenis_ikan'],
                'ukuran' => $rows[0]['ukuran'] ?? null,
                'satuan' => $rows[0]['satuan'],
                'harga_produsen' => $rows[0]['harga_produsen'],
                'harga_konsumen' => $rows[0]['harga_konsumen'],
                'kuantitas_perminggu' => $rows[0]['kuantitas_perminggu'],
                'tahun_pendataan' => $validated['tahun_pendataan'],
                'status' => 'pending',
                'catatan_perbaikan' => null,
                'updated_by' => auth()->user()->id_user,
            ]);

            for ($index = 1; $index < count($rows); $index++) {
                $ikanData = $rows[$index];

                HargaIkanSegar::create([
                    'id_kecamatan' => $validated['id_kecamatan'],
                    'id_desa' => $validated['id_desa'],
                    'tanggal_input' => $validated['tanggal_input'],
                    'nama_pasar' => $validated['nama_pasar'],
                    'nama_pedagang' => $validated['nama_pedagang'],
                    'nik_pedagang' => $validated['nik_pedagang'],
                    'asal_ikan' => $validated['asal_ikan'] ?? null,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'jenis_ikan' => $ikanData['jenis_ikan'],
                    'ukuran' => $ikanData['ukuran'] ?? null,
                    'satuan' => $ikanData['satuan'],
                    'harga_produsen' => $ikanData['harga_produsen'],
                    'harga_konsumen' => $ikanData['harga_konsumen'],
                    'kuantitas_perminggu' => $ikanData['kuantitas_perminggu'],
                    'tahun_pendataan' => $validated['tahun_pendataan'],
                    'status' => 'pending',
                    'catatan_perbaikan' => null,
                    'created_by' => auth()->user()->id_user,
                    'updated_by' => auth()->user()->id_user,
                ]);
            }
        });

        // Notify all admins about data update
        $this->notifyAdmins(
            'update',
            'Data Harga Ikan Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data harga ikan: ' . $hargaIkanSegar->nama_pedagang,
            $hargaIkanSegar->id_harga
        );

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan berhasil diperbarui dengan status PENDING. Menunggu verifikasi admin.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        $namaPedagang = $hargaIkanSegar->nama_pedagang;
        $idHarga = $hargaIkanSegar->id_harga;
        $createdBy = $hargaIkanSegar->created_by;
        $updatedBy = $hargaIkanSegar->updated_by;
        $hargaIkanSegar->delete();

        $this->notifyAdmins(
            'delete',
            'Data Harga Ikan Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data harga ikan: ' . $namaPedagang,
            $idHarga,
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
                'Data Harga Ikan Dihapus',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data harga ikan: ' . $namaPedagang,
                $idHarga
            );
        }

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan berhasil dihapus.');
    }

    /**
     * Get pasar by desa (for AJAX dependent dropdown)
     */
    public function getPasarByDesa($id_desa)
    {
        $desa = MasterDesa::find($id_desa);
        if (!$desa) {
            return response()->json([]);
        }

        // Prioritaskan pencarian via foreign key id_desa.
        // Fallback ke kolom teks desa/kecamatan untuk data lama yang belum terisi id_desa/id_kecamatan.
        $kecamatan = MasterKecamatan::find($desa->id_kecamatan);
        $namaDesa = strtolower(trim((string) $desa->nama_desa));
        $namaKecamatan = $kecamatan ? strtolower(trim((string) $kecamatan->nama_kecamatan)) : null;

        $pasars = \App\Models\Pasar::query()
            ->where(function ($query) use ($id_desa, $namaDesa, $namaKecamatan) {
                $query->where('id_desa', $id_desa);

                if ($namaDesa !== '') {
                    $query->orWhereRaw('LOWER(TRIM(desa)) = ?', [$namaDesa]);

                    if ($namaKecamatan) {
                        $query->orWhere(function ($q) use ($namaDesa, $namaKecamatan) {
                            $q->whereRaw('LOWER(TRIM(desa)) = ?', [$namaDesa])
                                ->whereRaw('LOWER(TRIM(kecamatan)) = ?', [$namaKecamatan]);
                        });
                    }
                }
            })
            ->where(function ($query) {
                $query->where('status', 'aktif')
                    ->orWhereRaw('LOWER(TRIM(status)) = ?', ['aktif']);
            })
            ->orderBy('nama_pasar')
            ->get(['id_pasar', 'nama_pasar'])
            ->unique('nama_pasar')
            ->values();

        return response()->json($pasars);
    }

    /**
     * Verify data (admin only)
     */
    public function verify(string $id)
    {
        // Cek role - hanya admin yang bisa verify
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('harga-ikan-segar.index')->with('error', 'Hanya admin yang dapat memverifikasi data.');
        }

        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        
        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $hargaIkanSegar->created_by;
        $updatedBy = $hargaIkanSegar->updated_by;
        
        $hargaIkanSegar->update([
            'status' => 'verified',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => null,
        ]);

        // Hapus backup karena data baru sudah diverifikasi
        DB::table('harga_ikan_segar_verified_backup')
            ->where('id_harga', $hargaIkanSegar->id_harga)
            ->delete();

        $this->notifyAdmins(
            'verified',
            'Data Harga Ikan Diverifikasi',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data harga ikan: ' . $hargaIkanSegar->nama_pedagang,
            $hargaIkanSegar->id_harga,
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
                'Data Harga Ikan Diverifikasi',
                'Pengguna ' . auth()->user()->nama_lengkap . ' memverifikasi data harga ikan: ' . $hargaIkanSegar->nama_pedagang,
                $hargaIkanSegar->id_harga
            );
        }

        return redirect()->route('harga-ikan-segar.index')->with('success', 'Data harga ikan berhasil diverifikasi dan status diubah menjadi VERIFIED.');
    }

    /**
     * Reject data (admin only)
     */
    public function reject(Request $request, string $id)
    {
        // Cek role - hanya admin yang bisa reject
        if (auth()->user()->role->nama_role !== 'admin') {
            return redirect()->route('harga-ikan-segar.index')->with('error', 'Hanya admin yang dapat menolak data.');
        }

        $validated = $request->validate([
            'catatan_perbaikan' => 'required|string|max:2000',
        ], [
            'catatan_perbaikan.required' => 'Catatan perbaikan wajib diisi saat menolak data.',
        ]);

        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        
        // Get user IDs BEFORE update to ensure they're not lost
        $createdBy = $hargaIkanSegar->created_by;
        $updatedBy = $hargaIkanSegar->updated_by;
        
        $hargaIkanSegar->update([
            'status' => 'rejected',
            'verified_by' => auth()->user()->id_user,
            'verified_at' => now(),
            'catatan_perbaikan' => $validated['catatan_perbaikan'],
        ]);

        $this->notifyAdmins(
            'rejected',
            'Data Harga Ikan Ditolak',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data harga ikan: ' . $hargaIkanSegar->nama_pedagang . '. Catatan: ' . $validated['catatan_perbaikan'],
            $hargaIkanSegar->id_harga,
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
                'Data Harga Ikan Ditolak',
                'Pengguna ' . auth()->user()->nama_lengkap . ' menolak data harga ikan: ' . $hargaIkanSegar->nama_pedagang . '. Catatan: ' . $validated['catatan_perbaikan'],
                $hargaIkanSegar->id_harga
            );
        }

        return redirect()->route('harga-ikan-segar.index')->with('warning', 'Data harga ikan ditolak dan status diubah menjadi REJECTED.');
    }
}
