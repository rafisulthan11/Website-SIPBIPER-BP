<?php

namespace App\Http\Controllers;

use App\Models\HargaIkanSegar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use Illuminate\Http\Request;

class HargaIkanSegarController extends Controller
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

        $query = HargaIkanSegar::query()
            ->with(['kecamatan', 'desa'])
            ->orderByDesc('tanggal_input')
            ->orderByDesc('id_harga');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('jenis_ikan', 'like', "%{$search}%")
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
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'tanggal_input' => 'required|date',
            'nama_pasar' => 'required|string|max:100',
            'nama_pedagang' => 'required|string|max:100',
            'asal_ikan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'ikan' => 'required|array|min:1',
            'ikan.*.jenis_ikan' => 'required|string|max:100',
            'ikan.*.ukuran' => 'nullable|string|max:50',
            'ikan.*.satuan' => 'required|string|max:20',
            'ikan.*.harga_produsen' => 'nullable|numeric|min:0',
            'ikan.*.harga_konsumen' => 'nullable|numeric|min:0',
            'ikan.*.kuantitas_perminggu' => 'nullable|numeric|min:0',
        ]);

        // Loop through each ikan data and create separate records
        foreach ($validated['ikan'] as $ikanData) {
            HargaIkanSegar::create([
                'id_kecamatan' => $validated['id_kecamatan'],
                'id_desa' => $validated['id_desa'],
                'tanggal_input' => $validated['tanggal_input'],
                'nama_pasar' => $validated['nama_pasar'],
                'nama_pedagang' => $validated['nama_pedagang'],
                'asal_ikan' => $validated['asal_ikan'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'jenis_ikan' => $ikanData['jenis_ikan'],
                'ukuran' => $ikanData['ukuran'] ?? null,
                'satuan' => $ikanData['satuan'],
                'harga_produsen' => $ikanData['harga_produsen'] ?? null,
                'harga_konsumen' => $ikanData['harga_konsumen'] ?? null,
                'kuantitas_perminggu' => $ikanData['kuantitas_perminggu'] ?? null,
            ]);
        }

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan segar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::with(['kecamatan', 'desa'])->findOrFail($id);
        
        return view('pages.harga-ikan-segar.show', compact('hargaIkanSegar'));
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

        $validated = $request->validate([
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'tanggal_input' => 'required|date',
            'nama_pasar' => 'required|string|max:100',
            'nama_pedagang' => 'required|string|max:100',
            'asal_ikan' => 'nullable|string|max:255',
            'jenis_ikan' => 'required|string|max:100',
            'ukuran' => 'nullable|string|max:50',
            'harga_produsen' => 'nullable|numeric|min:0',
            'harga_konsumen' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'kuantitas_perminggu' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $hargaIkanSegar->update($validated);

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan segar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $hargaIkanSegar = HargaIkanSegar::findOrFail($id);
        $hargaIkanSegar->delete();

        return redirect()->route('harga-ikan-segar.index')
            ->with('success', 'Data harga ikan segar berhasil dihapus.');
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

        // Cari pasar berdasarkan id_desa dengan foreign key (lebih akurat)
        $pasars = \App\Models\Pasar::where('id_desa', $id_desa)
            ->where('status', 'aktif')
            ->orderBy('nama_pasar')
            ->get(['id_pasar', 'nama_pasar']);
        
        // Jika tidak ada pasar di desa tersebut, kembalikan array kosong
        // (tidak lagi fallback ke semua pasar aktif)
        return response()->json($pasars);
    }
}
