<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Data Pembudidaya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Card container -->
            <div class="bg-white border border-slate-200 rounded-md shadow-md">
                <!-- Top controls row -->
                <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h4 class="text-slate-800 font-semibold text-lg">Daftar Budidaya</h4>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('pembudidaya.create') }}" class="inline-flex items-center bg-blue-700 hover:bg-blue-800 text-white font-semibold text-base rounded px-3.5 py-2 shadow">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Tambah Budidaya
                        </a>
                        <form method="GET" action="{{ route('pembudidaya.index') }}" class="flex items-center gap-3 ml-auto">
                            <label class="flex items-center text-base text-slate-700">Show
                                <select name="per_page" class="ml-2 border rounded px-2 py-1.5 text-base" onchange="this.form.submit()">
                                    @foreach($allowedPerPage as $n)
                                        <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                                <span class="ml-2">entries</span>
                            </label>
                            <div class="flex items-center text-base text-slate-700">
                                <label class="mr-2">Search:</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari" class="border rounded-full ps-9 pe-3 py-2 text-base placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <svg class="absolute left-3 top-2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="px-5 pb-5 overflow-x-auto">
                    <div class="rounded-md border border-slate-300 overflow-hidden">
                        <table class="min-w-full text-base">
                            <thead class="bg-slate-100 text-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Kelompok</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Desa</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Kecamatan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Komoditas</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembudidayas as $p)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_usaha ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $p->desa->nama_desa ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $p->kecamatan->nama_kecamatan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $p->jenis_budidaya ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('pembudidaya.show', $p->id_pembudidaya) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                Lihat
                                            </a>
                                            <a href="{{ route('pembudidaya.edit', $p->id_pembudidaya) }}" class="inline-flex items-center rounded bg-yellow-500 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('pembudidaya.destroy', $p->id_pembudidaya) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
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
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada data pembudidaya.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $pembudidayas->onEachSide(1)->links('components.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
