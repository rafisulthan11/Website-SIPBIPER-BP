    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
                {{ __('Akun & Keamanan') }}
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="rounded-lg overflow-hidden shadow-md">
                    <!-- Blue Header Container -->
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <h3 class="text-2xl font-bold">Manajemen Akun</h3>
                    </div>
                    
                    <!-- Card container -->
                    <div class="bg-white border-x border-b border-slate-200">
                        <div class="p-6 text-gray-900">

                            <!-- Title -->
                            <div class="mb-4">
                                <h4 class="text-slate-800 font-semibold text-lg">Daftar Akun</h4>
                            </div>
                            
                            <!-- Show entries, Search and Add Button -->
                                <form method="GET" action="{{ route('users.index') }}" class="mb-5"
                                      x-data="{ isMobile: window.innerWidth < 768 }"
                                      x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3"
                                     :style="isMobile
                                        ? 'display:flex; flex-direction:column; align-items:stretch; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'
                                        : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'">
                                <div class="flex items-center gap-2 text-sm text-slate-700"
                                     :style="isMobile
                                        ? 'display:flex; align-items:center; gap:0.5rem; width:100%;'
                                        : 'display:flex; align-items:center; gap:0.5rem;'">
                                    <span>Tampilkan</span>
                                    <select name="per_page" class="border border-gray-300 rounded px-4 py-1.5 pr-8 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                    <span>data</span>
                                </div>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full lg:w-auto"
                                         :style="isMobile
                                            ? 'display:flex; flex-direction:column; align-items:stretch; gap:0.75rem; margin-left:0; width:100%;'
                                            : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; column-gap:0.5rem; row-gap:0.5rem; margin-left:auto; width:auto;'">
                                        <div class="flex items-center gap-2 text-sm text-slate-700 w-full sm:w-auto"
                                             :style="isMobile
                                                ? 'display:flex; align-items:center; gap:0.5rem; width:100%;'
                                                : 'display:flex; align-items:center; gap:0.5rem;'">
                                        <label>Cari:</label>
                                            <div class="relative w-full sm:w-auto"
                                                 :style="isMobile
                                                    ? 'position:relative; width:100%; max-width:100%;'
                                                    : 'position:relative; width:16rem; max-width:16rem;'">
                                                    <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Cari nama, email, NIP" class="border border-gray-300 rounded-lg pl-10 pr-4 py-1.5 text-sm placeholder:text-gray-400 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64" />
                                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                        </div>
                                    </div>
                                        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg px-4 py-1.5 shadow whitespace-nowrap w-full sm:w-auto"
                                           :style="isMobile ? 'width:100%;' : 'width:auto;'">
                                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        Tambah User Baru
                                    </a>
                                </div>
                            </form>

                            <div class="md:hidden space-y-3 mb-4" style="margin-top:0.5rem;">
                                @forelse ($users as $user)
                                <div class="rounded-lg border border-slate-200 p-4 bg-white shadow-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $user->nama_lengkap }}</p>
                                            <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-700">{{ $user->status }}</span>
                                    </div>
                                    <div class="mt-2 text-sm text-slate-700 space-y-1">
                                        <p><span class="font-medium">NIP:</span> {{ $user->nip ?? '-' }}</p>
                                        <p><span class="font-medium">Role:</span> {{ $user->role->nama_role ?? '-' }}</p>
                                    </div>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <a href="{{ route('users.edit', $user->id_user) }}" class="inline-block rounded bg-yellow-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-yellow-600">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block rounded bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                @empty
                                <div class="rounded-lg border border-slate-200 p-4 text-center text-slate-500">Tidak ada data user.</div>
                                @endforelse
                            </div>

                            <div class="hidden md:block overflow-x-auto" style="width:100%; max-width:100%; margin-top:0.5rem;">
                            <div class="rounded-md border border-slate-300 overflow-hidden" style="width:100%;">
                                <table class="min-w-full text-base" style="width:100%; table-layout:auto;">
                                    <thead class="bg-slate-100 text-slate-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">No</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Lengkap</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">NIP</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Email</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Role</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Status</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($users as $user)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-4 py-3 align-top text-slate-700">{{ ($users->firstItem() ?? 0) + $loop->index }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->nama_lengkap }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->nip ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->email }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">
                                            {{-- Cek role untuk styling --}}
                                            @if($user->role->nama_role == 'super admin')
                                                <span class="inline-block whitespace-nowrap rounded-[0.27rem] bg-red-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-red-700">
                                                    {{ $user->role->nama_role }}
                                                </span>
                                            @elseif($user->role->nama_role == 'admin')
                                                <span style="display:inline-block; white-space:nowrap; border-radius:0.27rem; background-color:#fef9c3; padding:0.35em 0.65em 0.25em; text-align:center; vertical-align:baseline; font-size:0.75em; font-weight:700; line-height:1; color:#a16207;">
                                                    {{ $user->role->nama_role }}
                                                </span>
                                            @elseif($user->role->nama_role == 'staff')
                                                <span class="inline-block whitespace-nowrap rounded-[0.27rem] bg-blue-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-blue-700">
                                                    {{ $user->role->nama_role }}
                                                </span>
                                            @else
                                                <span class="inline-block whitespace-nowrap rounded-[0.27rem] bg-gray-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-gray-700">
                                                    {{ $user->role->nama_role }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->status }}</td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex items-center gap-2">
                                                
                                                <a href="{{ route('users.edit', $user->id_user) }}" class="inline-block rounded bg-yellow-500 px-2 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                                                    Edit
                                                </a>

                                                <form action="{{ route('users.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-block rounded bg-red-600 px-2 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="border-t border-slate-200">
                                        <td colspan="7" class="px-4 py-3 text-center text-slate-500">
                                            Tidak ada data user.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        </div>

                        </div>

                        <div class="mt-4">
                            {{ $users->onEachSide(1)->links('components.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>