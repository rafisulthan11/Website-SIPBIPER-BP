<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Master Data') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg overflow-hidden shadow-md">
                <!-- Blue Header Container -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h3 class="text-2xl font-bold">Kelola Komoditas</h3>
                </div>
                
                <!-- Card container -->
                <div class="bg-white border-x border-b border-slate-200">
                    <!-- Top controls row -->
                    <div class="p-5">
                        <!-- Title -->
                        <div class="mb-4">
                            <h4 class="text-slate-800 font-semibold text-lg">Daftar Komoditas</h4>
                        </div>
                    
                    <!-- Show entries, Search and Add Button -->
                    <form method="GET" action="{{ route('komoditas.index') }}" class="space-y-3">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                             x-data="{ isMobile: window.innerWidth < 768 }"
                             x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })"
                             :style="isMobile
                                ? 'display:flex; flex-direction:column; align-items:stretch; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'
                                : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'">
                        <div class="flex items-center gap-2 text-sm text-slate-700"
                             :style="isMobile
                                ? 'display:flex; align-items:center; gap:0.5rem; width:100%;'
                                : 'display:flex; align-items:center; gap:0.5rem;'">
                            <span>Show</span>
                            <select name="per_page" class="border border-gray-300 rounded px-4 py-1.5 pr-8 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                                @foreach($allowedPerPage as $n)
                                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3"
                             :style="isMobile
                                ? 'display:flex; flex-direction:column; align-items:stretch; gap:0.75rem; margin-left:0; width:100%;'
                            : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; column-gap:0.5rem; row-gap:0.5rem; margin-left:auto; width:auto;'">
                            <div class="flex items-center gap-2 text-sm text-slate-700"
                                 :style="isMobile
                                    ? 'display:flex; align-items:center; gap:0.5rem; width:100%;'
                                    : 'display:flex; align-items:center; gap:0.5rem;'">
                                <label>Search:</label>
                                <div class="relative"
                                     :style="isMobile
                                        ? 'position:relative; width:100%; max-width:100%;'
                                    : 'position:relative; width:16rem; max-width:16rem;'">
                                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari" class="border border-gray-300 rounded-lg pl-10 pr-4 py-1.5 text-sm placeholder:text-gray-400 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64" />
                                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                </div>
                            </div>
                            <a href="{{ route('komoditas.create') }}" class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg px-4 py-1.5 shadow whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Tambah Komoditas
                            </a>
                        </div>
                        </div>
                    </form>
                </div>

                <!-- Data list -->
                <div class="px-5 pb-5">
                    <!-- Mobile cards -->
                    <div class="md:hidden space-y-3">
                        @forelse ($komoditas as $k)
                            <div class="rounded-lg border border-slate-200 p-4 bg-white shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $k->nama_komoditas ?? '-' }}</p>
                                        <p class="text-sm text-slate-600">Tipe: {{ $k->tipe ?? '-' }}</p>
                                        <p class="text-sm text-slate-600">Kode: {{ $k->kode ?? '-' }}</p>
                                    </div>
                                    <div>
                                        @if($k->status == 'aktif')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('komoditas.show', $k->id_komoditas) }}" class="inline-flex items-center rounded bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Lihat</a>
                                    <a href="{{ route('komoditas.edit', $k->id_komoditas) }}" class="inline-flex items-center rounded bg-yellow-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route('komoditas.destroy', $k->id_komoditas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-slate-200 p-4 text-center text-slate-500">Belum ada data komoditas.</div>
                        @endforelse
                    </div>

                    <!-- Desktop table -->
                    <div class="hidden md:block overflow-x-auto">
                    <div class="rounded-md border border-slate-300 overflow-hidden">
                        <table class="min-w-full text-base">
                            <thead class="bg-slate-100 text-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Komoditas</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Tipe</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Kode</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($komoditas as $k)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $k->nama_komoditas ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $k->tipe ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $k->kode ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">
                                        @if($k->status == 'aktif')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('komoditas.show', $k->id_komoditas) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                Lihat
                                            </a>
                                            <a href="{{ route('komoditas.edit', $k->id_komoditas) }}" class="inline-flex items-center rounded bg-yellow-500 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('komoditas.destroy', $k->id_komoditas) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded bg-red-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-500">Belum ada data komoditas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $komoditas->onEachSide(1)->links('components.pagination.custom') }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
