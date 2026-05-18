<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = in_array((int) $request->per_page, $allowedPerPage, true)
            ? (int) $request->per_page
            : 10;
        $q = trim((string) $request->q);

        $users = User::with('role')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('nama_lengkap', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%')
                        ->orWhere('nip', 'like', '%' . $q . '%')
                        ->orWhere('status', 'like', '%' . $q . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($q) {
                            $roleQuery->where('nama_role', 'like', '%' . $q . '%');
                        });
                });
            })
            ->latest()
            ->paginate($perPage)
            ->appends([
                'per_page' => $perPage,
                'q' => $q,
            ]);

        return view('pages.users.index', compact('users', 'perPage', 'q', 'allowedPerPage'));
    }

    public function create()
    {
        $roles = Role::whereIn('nama_role', ['admin', 'staff'])->get();
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'digits:18', 'unique:users,nip'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_role' => [
                'required',
                Rule::exists('roles', 'id_role')->whereIn('nama_role', ['admin', 'staff']),
            ],
            'status' => ['required', 'in:aktif,tidak aktif'],
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $request->id_role,
            'status' => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }
    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')->with('error', 'Akun super admin tidak dapat diubah.');
        }

        $roles = Role::whereIn('nama_role', ['admin', 'staff'])->get();
        return view('pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Mengupdate data user di database.
     */
    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('users.index')->with('error', 'Akun super admin tidak dapat diubah.');
        }

        // Validasi input
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'digits:18', 'unique:users,nip,'.$user->id_user.',id_user'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id_user.',id_user'],
            'id_role' => [
                'required',
                Rule::exists('roles', 'id_role')->whereIn('nama_role', ['admin', 'staff']),
            ],
            'status' => ['required', 'in:aktif,tidak aktif'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Update data user
        $user->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'email' => $request->email,
            'id_role' => $request->id_role,
            'status' => $request->status,
        ]);

        // Jika ada password baru, update passwordnya
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Akun super admin tidak dapat dihapus.');
        }

        // Tambahkan logika agar user tidak bisa menghapus dirinya sendiri
        if ($user->id_user === Auth::user()->id_user) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}