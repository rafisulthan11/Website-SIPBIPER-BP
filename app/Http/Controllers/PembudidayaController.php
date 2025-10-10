<?php

namespace App\Http\Controllers;
use App\Models\Pembudidaya;
use Illuminate\Http\Request;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\PembudidayaInvestasi;
use App\Models\PembudidayaIzin;

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
        return view('pages.pembudidaya.create', compact('kecamatans', 'desas'));
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
        ]);

        // Simpan data ke tabel pembudidayas
    $p = Pembudidaya::create($request->except(['investasi','izin']));

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

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembudidaya $pembudidaya)
    {
    $pembudidaya->load(['kecamatan','desa','investasi','izin']);
        return view('pages.pembudidaya.show', compact('pembudidaya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembudidaya $pembudidaya)
    {
    $kecamatans = MasterKecamatan::all();
    $desas = MasterDesa::all();
    $pembudidaya->load(['investasi','izin']);
        return view('pages.pembudidaya.edit', compact('pembudidaya', 'kecamatans', 'desas'));
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
}
