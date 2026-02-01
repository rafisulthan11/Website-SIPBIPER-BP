    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Akun') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <span>Show</span>
                                    <select name="per_page" class="border border-gray-300 rounded px-4 py-1.5 pr-8 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>entries</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2 text-sm text-slate-700">
                                        <label>Search:</label>
                                        <div class="relative">
                                            <input type="text" name="q" value="" placeholder="Cari" class="border border-gray-300 rounded-lg pl-10 pr-4 py-1.5 text-sm placeholder:text-gray-400 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64" />
                                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                        </div>
                                    </div>
                                    <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg px-4 py-1.5 shadow whitespace-nowrap">
                                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        Tambah User Baru
                                    </a>
                                </div>
                            </form>

                        <div class="overflow-x-auto">
                            <div class="rounded-md border border-slate-300 overflow-hidden">
                                <table class="min-w-full text-base">
                                    <thead class="bg-slate-100 text-slate-800">
                                        <tr>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">No</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Lengkap</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Email</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Role</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Status</th>
                                            <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($users as $user)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->nama_lengkap }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $user->email }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">
                                            {{-- Cek role untuk styling --}}
                                            @if($user->role->nama_role == 'admin')
                                                <span class="inline-block whitespace-nowrap rounded-[0.27rem] bg-red-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-red-700">
                                                    {{ $user->role->nama_role }}
                                                </span>
                                            @else
                                                <span class="inline-block whitespace-nowrap rounded-[0.27rem] bg-blue-100 px-[0.65em] pb-[0.25em] pt-[0.35em] text-center align-baseline text-[0.75em] font-bold leading-none text-blue-700">
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
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="border-t border-slate-200">
                                        <td colspan="6" class="px-4 py-3 text-center text-slate-500">
                                            Tidak ada data user.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>