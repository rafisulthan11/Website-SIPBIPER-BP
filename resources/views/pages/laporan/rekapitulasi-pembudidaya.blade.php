<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            Rekapitulasi Pembudidaya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Header bar -->
                <div class="bg-blue-600 px-4 sm:px-6 py-4">
                    <h3 class="text-white text-2xl font-bold">Rekapitulasi Pembudidaya</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Filter row (clean grid) -->
                    @include('pages.laporan._rekap_filters', [
                        'kecamatans' => $kecamatans, 
                        'komoditas' => $komoditas, 
                        'kategoris' => $kategoris, 
                        'kategori_label' => 'Jenis Kegiatan Usaha',
                        'show_bulan' => false,
                        'title' => 'Data Pembudidaya',
                        'reset_route' => route('laporan.rekapitulasi.pembudidaya')
                    ])
                    <!-- Table controls -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <label class="text-sm">Show</label>
                            <select class="px-3 h-9 w-20 border rounded bg-white text-sm">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span class="text-sm">entries</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="sr-only">Search</label>
                            <div class="flex items-center gap-2 border rounded px-2 py-1">
                                <label class="text-sm">Search:</label>
                                <input type="text" class="border-0 focus:ring-0 px-2 h-8 text-sm w-48" />
                            </div>
                            <a href="{{ route('laporan.rekapitulasi.pembudidaya.export', request()->query()) }}" title="Unduh Excel — semua hasil filter" aria-label="Unduh Excel untuk semua hasil filter" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Unduh Excel (Semua)
                            </a>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
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
                                    @forelse($pembudidayas as $p)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_lengkap }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_usaha ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($p->desa)->nama_desa ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($p->kecamatan)->nama_kecamatan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->jenis_kegiatan_usaha ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('pembudidaya.show', $p->id_pembudidaya) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('laporan.rekapitulasi.pembudidaya.pdf', $p->id_pembudidaya) }}" title="Unduh PDF" class="inline-flex items-center rounded bg-blue-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                                    Unduh PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">Tidak ada data untuk ditampilkan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $pembudidayas->firstItem() ?: 0 }} - {{ $pembudidayas->lastItem() ?: 0 }} dari {{ $pembudidayas->total() }} entri
                            </div>
                            <div>
                                {{ $pembudidayas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
