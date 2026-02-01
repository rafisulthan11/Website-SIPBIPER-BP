<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use Illuminate\Http\Request;

class PasarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = in_array($request->per_page, $allowedPerPage) ? $request->per_page : 10;
        $q = $request->q;

        $pasar = Pasar::query()
            ->when($q, function ($query, $q) {
                return $query->where('nama_pasar', 'like', '%' . $q . '%')
                    ->orWhere('kecamatan', 'like', '%' . $q . '%')
                    ->orWhere('desa', 'like', '%' . $q . '%')
                    ->orWhere('alamat', 'like', '%' . $q . '%')
                    ->orWhere('latitude', 'like', '%' . $q . '%')
                    ->orWhere('longitude', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%');
            })
            ->orderBy('nama_pasar')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'q' => $q]);

        return view('pages.pasar.index', compact('pasar', 'allowedPerPage', 'perPage', 'q'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pasar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasar' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        Pasar::create($validated);

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pasar = Pasar::findOrFail($id);
        return view('pages.pasar.show', compact('pasar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pasar = Pasar::findOrFail($id);
        return view('pages.pasar.edit', compact('pasar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_pasar' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,tidak aktif'
        ]);

        $pasar = Pasar::findOrFail($id);
        $pasar->update($validated);

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pasar = Pasar::findOrFail($id);
        $pasar->delete();

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil dihapus.');
    }
}
