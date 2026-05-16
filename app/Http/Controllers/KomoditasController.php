<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    /**
     * Create a notification entry.
     */
    protected function createNotification($userId, $type, $title, $message, $moduleId = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'module' => 'komoditas',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('komoditas.show', $moduleId) : route('komoditas.index'),
        ]);
    }

    /**
     * Notify admin and super admin except the current actor.
     */
    protected function notifySuperAdmins($type, $title, $message, $moduleId = null)
    {
        $actorId = auth()->user()?->id_user;

        $superAdmins = User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', ['admin', 'super admin']);
        })
        ->when($actorId, function ($query) use ($actorId) {
            $query->where('id_user', '!=', $actorId);
        })
        ->get();

        foreach ($superAdmins as $superAdmin) {
            $this->createNotification($superAdmin->id_user, $type, $title, $message, $moduleId);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = in_array($request->per_page, $allowedPerPage) ? $request->per_page : 10;
        $q = $request->q;
        $tipe = $request->tipe;

        $komoditas = Komoditas::query()
            ->when($tipe, function ($query, $tipe) {
                return $query->where('tipe', $tipe);
            })
            ->when($q, function ($query, $q) {
                return $query->where('nama_komoditas', 'like', '%' . $q . '%')
                    ->orWhere('tipe', 'like', '%' . $q . '%')
                    ->orWhere('kode', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%');
            })
            ->orderBy('nama_komoditas')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage, 'q' => $q, 'tipe' => $tipe]);

        return view('pages.komoditas.index', compact('komoditas', 'allowedPerPage', 'perPage', 'q', 'tipe'));
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
        ], [
            'kode_tipe_unique' => 'kode dan tipe komoditas sudah terdaftar',
        ]);

        $exists = Komoditas::where('kode', $validated['kode'])
            ->where('tipe', $validated['tipe'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['kode' => 'kode dan tipe komoditas sudah terdaftar'])
                ->withInput();
        }

        $komoditas = Komoditas::create(array_merge($validated, [
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]));

        $this->notifySuperAdmins(
            'create',
            'Data Komoditas Ditambahkan',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data komoditas: ' . $komoditas->nama_komoditas,
            $komoditas->id_komoditas
        );

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $komoditas = Komoditas::with(['createdBy', 'updatedBy'])->findOrFail($id);
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
        ], [
            'kode_tipe_unique' => 'kode dan tipe komoditas sudah terdaftar',
        ]);

        $exists = Komoditas::where('kode', $validated['kode'])
            ->where('tipe', $validated['tipe'])
            ->where('id_komoditas', '!=', $id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['kode' => 'kode dan tipe komoditas sudah terdaftar'])
                ->withInput();
        }

        $komoditas = Komoditas::findOrFail($id);
        $komoditas->update(array_merge($validated, [
            'updated_by' => auth()->id()
        ]));

        $this->notifySuperAdmins(
            'update',
            'Data Komoditas Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data komoditas: ' . $komoditas->nama_komoditas,
            $komoditas->id_komoditas
        );

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $komoditas = Komoditas::findOrFail($id);
        $namaKomoditas = $komoditas->nama_komoditas;
        $idKomoditas = $komoditas->id_komoditas;
        $komoditas->delete();

        $this->notifySuperAdmins(
            'delete',
            'Data Komoditas Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data komoditas: ' . $namaKomoditas,
            $idKomoditas
        );

        return redirect()->route('komoditas.index')
            ->with('success', 'Data komoditas berhasil dihapus.');
    }
}
