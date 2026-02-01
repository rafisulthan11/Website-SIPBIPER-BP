<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Data Harga Ikan Segar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg overflow-hidden shadow-md">
                <!-- Blue Header Container -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h3 class="text-2xl font-bold">Data Harga Ikan Segar</h3>
                </div>
                
                <!-- Card container -->
                <div class="bg-white border-x border-b border-slate-200">
                    <!-- Top controls row -->
                    <div class="p-5">
                        <!-- Title -->
                        <div class="mb-4">
                            <h4 class="text-slate-800 font-semibold text-lg">Daftar Harga Ikan Segar</h4>
                        </div>
                    
                    <!-- Show entries, Search and Add Button -->
                    <form method="GET" action="{{ route('harga-ikan-segar.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2 text-sm text-slate-700">
                            <span>Show</span>
                            <select name="per_page" class="border border-gray-300 rounded px-4 py-1.5 pr-8 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="this.form.submit()">
                                @foreach($allowedPerPage as $n)
                                    <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <label>Search:</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari" class="border border-gray-300 rounded-lg pl-10 pr-4 py-1.5 text-sm placeholder:text-gray-400 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64" />
                                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                </div>
                            </div>
                            <a href="{{ route('harga-ikan-segar.create') }}" class="inline-flex items-center justify-center bg-blue-700 hover:bg-blue-800 text-white font-medium text-sm rounded-lg px-4 py-1.5 shadow whitespace-nowrap">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Tambah Data
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="px-5 pb-5 overflow-x-auto">
                    <div class="rounded-md border border-slate-300 overflow-hidden">
                        <table class="min-w-full text-base">
                            <thead class="bg-slate-100 text-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Jenis Ikan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Ukuran</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Harga Produsen</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Harga Konsumen</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Satuan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Lokasi</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hargaIkanSegars as $harga)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3 align-top text-slate-700">{{ \Carbon\Carbon::parse($harga->tanggal_input)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->jenis_ikan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->ukuran ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->harga_produsen ? 'Rp ' . number_format($harga->harga_produsen, 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->harga_konsumen ? 'Rp ' . number_format($harga->harga_konsumen, 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->satuan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->desa->nama_desa ?? '-' }}, {{ $harga->kecamatan->nama_kecamatan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('harga-ikan-segar.show', $harga->id_harga) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                Lihat
                                            </a>
                                            <a href="{{ route('harga-ikan-segar.edit', $harga->id_harga) }}" class="inline-flex items-center rounded bg-yellow-500 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form action="{{ route('harga-ikan-segar.destroy', $harga->id_harga) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
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
                                    <td colspan="8" class="px-4 py-6 text-center text-slate-500">Belum ada data harga ikan segar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $hargaIkanSegars->onEachSide(1)->links('components.pagination.custom') }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
