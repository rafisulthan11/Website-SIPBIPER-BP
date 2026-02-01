<?php

namespace App\Http\Controllers;

use App\Models\Pengolah;
use Illuminate\Http\Request;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Support\Facades\Storage;

class PengolahController extends Controller
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

        $query = Pengolah::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('id_pengolah');

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
        $komoditas = \App\Models\Komoditas::orderBy('nama_komoditas')->get();
        return view('pages.pengolah.create', compact('kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pengolah' => 'required|string|size:16|unique:pengolahs,nik_pengolah',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'nullable|string',
            'jenis_pengolahan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:20',
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
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

        // Simpan data ke tabel pengolahs
        $pengolah = Pengolah::create(array_merge($validated, $mappedData, $uploadedFiles));

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengolah $pengolah)
    {
        $pengolah->load(['kecamatan', 'desa']);
        return view('pages.pengolah.show', compact('pengolah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengolah $pengolah)
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::orderBy('nama_komoditas')->get();
        return view('pages.pengolah.edit', compact('pengolah', 'kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengolah $pengolah)
    {
        // Validasi data
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pengolah' => 'required|string|size:16|unique:pengolahs,nik_pengolah,' . $pengolah->id_pengolah . ',id_pengolah',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'nullable|string',
            'jenis_pengolahan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'jumlah_tanggungan' => 'nullable|integer',
            'aset_pribadi' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:20',
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
            // Lampiran
            'foto_ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_cpib_cbib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_unit_usaha' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Handle file uploads
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_kusuka', 'foto_nib', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        $uploadedFiles = [];
        
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($pengolah->$field) {
                    \Storage::delete('public/' . $pengolah->$field);
                }
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

        // Update data di tabel
        $pengolah->update(array_merge($validated, $mappedData, $uploadedFiles));

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengolah $pengolah)
    {
        $pengolah->delete();

        return redirect()->route('pengolah.index')->with('success', 'Data pengolah berhasil dihapus.');
    }
}
