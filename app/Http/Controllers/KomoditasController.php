<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = in_array($request->per_page, $allowedPerPage) ? $request->per_page : 10;
        $q = $request->q;

        $komoditas = Komoditas::query()
            ->when($q, function ($query, $q) {
                return $query->where('nama_komoditas', 'like', '%' . $q . '%')
                    ->orWhere('tipe', 'like', '%' . $q . '%')
                    ->orWhere('kode', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%');
            })
            ->orderBy('nama_komoditas')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'q' => $q]);

        return view('pages.komoditas.index', compact('komoditas', 'allowedPerPage', 'perPage', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.komoditas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_komoditas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        Komoditas::create($validated);

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $komoditas = Komoditas::findOrFail($id);
        return view('pages.komoditas.show', compact('komoditas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $komoditas = Komoditas::findOrFail($id);
        return view('pages.komoditas.edit', compact('komoditas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_komoditas' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $komoditas = Komoditas::findOrFail($id);
        $komoditas->update($validated);

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $komoditas = Komoditas::findOrFail($id);
        $komoditas->delete();

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil dihapus.');
    }
}
