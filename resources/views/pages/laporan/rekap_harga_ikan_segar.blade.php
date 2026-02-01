<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            Rekap Harga Ikan Segar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Header bar -->
                <div class="bg-blue-600 px-4 sm:px-6 py-4">
                    <h3 class="text-white text-2xl font-bold">Laporan Harga Ikan Segar</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Filter Form -->
                    <form method="GET" class="mb-4 bg-gray-100 p-4 rounded border border-gray-200">
                        <div class="grid grid-cols-1 lg:grid-cols-12 items-start gap-3">
                            <div class="lg:col-span-3">
                                <span class="font-semibold">Data Harga Ikan Segar</span>
                            </div>
                            <div class="lg:col-span-9">
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2">
                                    <select name="kecamatan" class="px-3 h-9 min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Kecamatan</option>
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>

                                    <select name="jenis_ikan" class="px-3 h-9 min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Jenis Ikan</option>
                                        @foreach($jenisIkans as $ikan)
                                            <option value="{{ $ikan }}" {{ request('jenis_ikan') == $ikan ? 'selected' : '' }}>{{ $ikan }}</option>
                                        @endforeach
                                    </select>

                                    <select name="nama_pasar" class="px-3 h-9 min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Pasar</option>
                                        @foreach($namaPasars as $pasar)
                                            <option value="{{ $pasar }}" {{ request('nama_pasar') == $pasar ? 'selected' : '' }}>{{ $pasar }}</option>
                                        @endforeach
                                    </select>

                                    <select name="bulan" class="px-3 h-9 min-w-[140px] border rounded bg-white text-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $label)
                                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded text-sm">Filter</button>
                                        <a href="{{ route('laporan.harga.ikan.segar') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

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
                            <a href="{{ route('laporan.harga.ikan.segar.export', request()->query()) }}" title="Unduh Excel — semua hasil filter" aria-label="Unduh Excel untuk semua hasil filter" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
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
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Tanggal Input</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Pasar</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Pedagang</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Jenis Ikan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Desa</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Kecamatan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-4 py-3 align-top text-slate-700">{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->nama_pasar ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->nama_pedagang ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->jenis_ikan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($item->desa)->nama_desa ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($item->kecamatan)->nama_kecamatan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('harga-ikan-segar.show', $item->id_harga) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('laporan.harga.ikan.segar.pdf', $item->id_harga) }}" title="Unduh PDF" class="inline-flex items-center rounded bg-blue-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                                    Unduh PDF
                                                </a>
                                            </div>
                                        </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Menampilkan {{ $items->firstItem() ?: 0 }} - {{ $items->lastItem() ?: 0 }} dari {{ $items->total() }} entri
                            </div>
                            <div>
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
