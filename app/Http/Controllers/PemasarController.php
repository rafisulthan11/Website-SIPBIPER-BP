<?php

namespace App\Http\Controllers;

use App\Models\Pemasar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian dan jumlah per halaman dari query string
        $search = trim((string) $request->query('search', ''));
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = Pemasar::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('id_pemasar');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nik_pemasar', 'like', "%{$search}%")
                  ->orWhere('komoditas', 'like', "%{$search}%")
                  ->orWhere('wilayah_pemasaran', 'like', "%{$search}%")
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
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')->orderBy('nama_komoditas')->get();
        return view('pages.pemasar.create', compact('kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik_pemasar' => 'required|string|size:16|unique:pemasars,nik_pemasar',
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
            'jenis_pemasaran' => 'nullable|string',
            'komoditas' => 'nullable|string|max:255',
            'wilayah_pemasaran' => 'nullable|string',
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
            'kapasitas_terpasang_setahun' => 'nullable|numeric',
            'bulan_produksi' => 'nullable|array',
            'jumlah_hari_produksi' => 'nullable|integer',
            'distribusi_pemasaran' => 'nullable|string',
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
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
        $fileFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_npwp', 'foto_izin_usaha', 'foto_produk', 'foto_kusuka', 'foto_nib', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
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

        Pemasar::create(array_merge($validated, $uploadedFiles));

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pemasar $pemasar)
    {
        $pemasar->load(['kecamatan', 'desa']);
        return view('pages.pemasar.show', compact('pemasar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pemasar $pemasar)
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        $komoditas = \App\Models\Komoditas::where('status', 'aktif')->orderBy('nama_komoditas')->get();
        return view('pages.pemasar.edit', compact('pemasar', 'kecamatans', 'desas', 'komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemasar $pemasar)
    {
        $validated = $request->validate([
            'nik_pemasar' => 'required|string|size:16|unique:pemasars,nik_pemasar,' . $pemasar->id_pemasar . ',id_pemasar',
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
            'jenis_pemasaran' => 'nullable|string',
            'komoditas' => 'nullable|string|max:255',
            'wilayah_pemasaran' => 'nullable|string',
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
            'kapasitas_terpasang_setahun' => 'nullable|numeric',
            'bulan_produksi' => 'nullable|array',
            'jumlah_hari_produksi' => 'nullable|integer',
            'distribusi_pemasaran' => 'nullable|string',
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
            'foto_kusuka' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_nib' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_pirt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_sertifikat_halal' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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
        $fotoFields = ['foto_ktp', 'foto_sertifikat', 'foto_cpib_cbib', 'foto_unit_usaha', 'foto_npwp', 'foto_izin_usaha', 'foto_produk', 'foto_kusuka', 'foto_nib', 'foto_sertifikat_pirt', 'foto_sertifikat_halal'];
        foreach ($fotoFields as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($pemasar->$field) {
                    \Storage::delete('public/' . $pemasar->$field);
                }
                // Upload file baru
                $file = $request->file($field);
                $filename = uniqid() . '_' . time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $validated[$field] = $file->storeAs('lampiran/pemasar', $filename, 'public');
            }
        }

        $pemasar->update($validated);

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pemasar $pemasar)
    {
        $pemasar->delete();

        return redirect()->route('pemasar.index')
            ->with('success', 'Data pemasar berhasil dihapus.');
    }
}
