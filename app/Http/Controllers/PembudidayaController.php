<?php

namespace App\Http\Controllers;
use App\Models\Pembudidaya;
use Illuminate\Http\Request;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;

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
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => 'required|string|size:16|unique:pembudidayas,nik_pembudidaya',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_budidaya' => 'required|string',
        ]);

        // Simpan data ke tabel pembudidayas
        Pembudidaya::create($request->all());

        return redirect()->route('pembudidaya.index')->with('success', 'Data pembudidaya berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembudidaya $pembudidaya)
    {
        // Kita bisa langsung mengirimkan objek $pembudidaya ke view
        return view('pages.pembudidaya.show', compact('pembudidaya'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembudidaya $pembudidaya)
    {
        $kecamatans = MasterKecamatan::all();
        $desas = MasterDesa::all();
        return view('pages.pembudidaya.edit', compact('pembudidaya', 'kecamatans', 'desas'));
    }

    /**
     * Mengupdate data di database.
     */
    public function update(Request $request, Pembudidaya $pembudidaya)
    {
        // Validasi data
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik_pembudidaya' => 'required|string|size:16|unique:pembudidayas,nik_pembudidaya,' . $pembudidaya->id_pembudidaya . ',id_pembudidaya',
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'jenis_kegiatan_usaha' => 'required|string',
            'jenis_budidaya' => 'required|string',
        ]);

        // Update data di tabel
        $pembudidaya->update($request->all());

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
