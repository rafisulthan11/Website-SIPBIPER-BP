<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use App\Models\MasterKecamatan;
use App\Models\MasterDesa;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PasarController extends Controller
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
            'module' => 'pasar',
            'module_id' => $moduleId,
            'url' => $moduleId ? route('pasar.show', $moduleId) : route('pasar.index'),
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

        $pasar = Pasar::query()
            ->when($q, function ($query, $q) {
                return $query->where('nama_pasar', 'like', '%' . $q . '%')
                    ->orWhere('kode_pasar', 'like', '%' . $q . '%')
                    ->orWhere('kecamatan', 'like', '%' . $q . '%')
                    ->orWhere('desa', 'like', '%' . $q . '%')
                    ->orWhere('alamat', 'like', '%' . $q . '%')
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
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        return view('pages.pasar.create', compact('kecamatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pasar' => 'required|string|max:255',
            'kode_pasar' => ['required', 'string', 'max:50', Rule::unique('pasar', 'kode_pasar')],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,tidak aktif'
        ], [
            'kode_pasar.unique' => 'kode yang anda masukkan sudah terdaftar',
        ]);

        $kecamatan = MasterKecamatan::findOrFail($validated['id_kecamatan']);
        $desa = MasterDesa::query()
            ->where('id_desa', $validated['id_desa'])
            ->where('id_kecamatan', $validated['id_kecamatan'])
            ->first();

        if (!$desa) {
            return back()
                ->withErrors(['id_desa' => 'Desa tidak sesuai dengan kecamatan yang dipilih.'])
                ->withInput();
        }

        $pasar = Pasar::create(array_merge($validated, [
            'kecamatan' => $kecamatan->nama_kecamatan,
            'desa' => $desa->nama_desa,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id()
        ]));

        $this->notifySuperAdmins(
            'create',
            'Data Pasar Ditambahkan',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menambahkan data pasar: ' . $pasar->nama_pasar,
            $pasar->id_pasar
        );

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pasar = Pasar::with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('pages.pasar.show', compact('pasar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pasar = Pasar::findOrFail($id);
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();
        $selectedKecamatanId = old('id_kecamatan', $pasar->id_kecamatan);
        $desas = MasterDesa::query()
            ->where('id_kecamatan', $selectedKecamatanId)
            ->orderBy('nama_desa')
            ->get();

        return view('pages.pasar.edit', compact('pasar', 'kecamatans', 'desas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_pasar' => 'required|string|max:255',
            'kode_pasar' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pasar', 'kode_pasar')->ignore($id, 'id_pasar'),
            ],
            'id_kecamatan' => 'required|exists:master_kecamatans,id_kecamatan',
            'id_desa' => 'required|exists:master_desas,id_desa',
            'alamat' => 'required|string',
            'status' => 'required|in:aktif,tidak aktif'
        ], [
            'kode_pasar.unique' => 'kode yang anda masukkan sudah terdaftar',
        ]);

        $kecamatan = MasterKecamatan::findOrFail($validated['id_kecamatan']);
        $desa = MasterDesa::query()
            ->where('id_desa', $validated['id_desa'])
            ->where('id_kecamatan', $validated['id_kecamatan'])
            ->first();

        if (!$desa) {
            return back()
                ->withErrors(['id_desa' => 'Desa tidak sesuai dengan kecamatan yang dipilih.'])
                ->withInput();
        }

        $pasar = Pasar::findOrFail($id);
        $pasar->update(array_merge($validated, [
            'kecamatan' => $kecamatan->nama_kecamatan,
            'desa' => $desa->nama_desa,
            'updated_by' => auth()->id()
        ]));

        $this->notifySuperAdmins(
            'update',
            'Data Pasar Diperbarui',
            'Pengguna ' . auth()->user()->nama_lengkap . ' memperbarui data pasar: ' . $pasar->nama_pasar,
            $pasar->id_pasar
        );

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pasar = Pasar::findOrFail($id);
        $namaPasar = $pasar->nama_pasar;
        $idPasar = $pasar->id_pasar;
        $pasar->delete();

        $this->notifySuperAdmins(
            'delete',
            'Data Pasar Dihapus',
            'Pengguna ' . auth()->user()->nama_lengkap . ' menghapus data pasar: ' . $namaPasar,
            $idPasar
        );

        return redirect()->route('pasar.index')
            ->with('success', 'Data pasar berhasil dihapus.');
    }
}
