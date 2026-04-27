<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            Laporan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <!-- Header bar -->
                <div class="bg-blue-600 px-4 sm:px-6 py-4">
                    <h3 class="text-white text-2xl font-bold">Rekapitulasi Harga Ikan</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-600 rounded-lg p-4 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-600 rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12A2.25 2.25 0 0020.25 14.25V3.75M3.75 3.75h16.5M7.5 8.25h9m-9 3h6" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">Total Data Harga Ikan</h4>
                                    <p class="text-2xl font-bold text-purple-700">{{ number_format($totalDataHarga ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($totalJenisIkan ?? 0, 0, ',', '.') }} jenis ikan tercatat</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-600 rounded-lg p-4 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-600 rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l3-3m-3 3l-3-3M3.75 12a8.25 8.25 0 1116.5 0 8.25 8.25 0 01-16.5 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">Rata-rata Harga Produsen</h4>
                                    <p class="text-2xl font-bold text-blue-700">Rp {{ number_format($avgHargaProdusen ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Dari {{ number_format($totalPasarAktif ?? 0, 0, ',', '.') }} pasar aktif</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-600 rounded-lg p-4 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-600 rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m0 0l3-3m-3 3l-3-3M3.75 12a8.25 8.25 0 1116.5 0 8.25 8.25 0 01-16.5 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">Rata-rata Harga Konsumen</h4>
                                    <p class="text-2xl font-bold text-green-700">Rp {{ number_format($avgHargaKonsumen ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Total kuantitas mingguan {{ number_format($totalKuantitasMingguan ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-4 bg-gray-100 p-4 rounded border border-gray-200">
                        <div class="grid grid-cols-1 lg:grid-cols-12 items-start gap-3">
                            <div class="lg:col-span-3">
                                <span class="font-semibold">Data Harga Ikan</span>
                            </div>
                            <div class="lg:col-span-9">
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:justify-end gap-2">
                                    <select name="kecamatan" class="px-3 h-9 w-full sm:w-auto sm:min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Kecamatan</option>
                                        @foreach($kecamatans as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>

                                    <select name="jenis_ikan" class="px-3 h-9 w-full sm:w-auto sm:min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Jenis Ikan</option>
                                        @foreach($jenisIkans as $ikan)
                                            <option value="{{ $ikan }}" {{ request('jenis_ikan') == $ikan ? 'selected' : '' }}>{{ $ikan }}</option>
                                        @endforeach
                                    </select>

                                    <select name="nama_pasar" class="px-3 h-9 w-full sm:w-auto sm:min-w-[140px] border rounded bg-white text-sm pr-8">
                                        <option value="">Semua Pasar</option>
                                        @foreach($namaPasars as $pasar)
                                            <option value="{{ $pasar }}" {{ request('nama_pasar') == $pasar ? 'selected' : '' }}>{{ $pasar }}</option>
                                        @endforeach
                                    </select>

                                    <select name="bulan" class="px-3 h-9 w-full sm:w-auto sm:min-w-[140px] border rounded bg-white text-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $num => $label)
                                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>

                                    <div class="grid grid-cols-2 sm:flex items-center gap-2 w-full sm:w-auto">
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded text-sm">Filter</button>
                                        <a href="{{ route('laporan.harga.ikan.segar') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Table controls -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-3"
                         x-data="{ isMobile: window.innerWidth < 768 }"
                         x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })"
                         :style="isMobile
                            ? 'display:flex; flex-direction:column; align-items:stretch; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'
                            : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'">
                        <div class="flex items-center gap-3"
                             :style="isMobile
                                ? 'display:flex; align-items:center; gap:0.75rem; width:100%;'
                                : 'display:flex; align-items:center; gap:0.75rem;'">
                            <label class="text-sm">Show</label>
                            <select class="px-3 h-9 w-20 border rounded bg-white text-sm">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select>
                            <span class="text-sm">entries</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2"
                             :style="isMobile
                                ? 'display:flex; flex-direction:column; align-items:stretch; gap:0.75rem; margin-left:0; width:100%;'
                                : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; column-gap:0.5rem; row-gap:0.5rem; margin-left:auto; width:auto;'">
                            <label class="sr-only">Search</label>
                            <div class="flex items-center gap-2 border rounded px-2 py-1 w-full sm:w-auto"
                                 :style="isMobile
                                    ? 'display:flex; align-items:center; gap:0.5rem; width:100%;'
                                    : 'display:flex; align-items:center; gap:0.5rem; width:30rem; max-width:30rem;'">
                                <label class="text-sm">Search:</label>
                                <input type="text" class="border-0 focus:ring-0 px-2 h-8 text-sm w-full sm:w-48" />
                            </div>
                            <a href="{{ route('laporan.harga.ikan.segar.export', request()->query()) }}" title="Unduh Excel — semua hasil filter" aria-label="Unduh Excel untuk semua hasil filter" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm w-full sm:w-auto"
                               :style="isMobile ? 'width:100%;' : 'width:auto;'">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Unduh Excel (Semua)
                            </a>
                        </div>
                    </div>

                    <!-- Mobile cards -->
                    <div class="md:hidden space-y-3 mb-4">
                        @forelse($items as $item)
                        <div class="rounded-lg border border-slate-200 p-4 bg-white shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $item->jenis_ikan ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</p>
                                </div>
                                <p class="text-xs text-slate-600">{{ $item->tahun_pendataan ?? '-' }}</p>
                            </div>
                            <div class="mt-2 text-sm text-slate-700 space-y-1">
                                <p><span class="font-medium">Pasar:</span> {{ $item->nama_pasar ?? '-' }}</p>
                                <p><span class="font-medium">Pedagang:</span> {{ $item->nama_pedagang ?? '-' }}</p>
                                <p><span class="font-medium">Desa:</span> {{ optional($item->desa)->nama_desa ?? '-' }}</p>
                                <p><span class="font-medium">Kecamatan:</span> {{ optional($item->kecamatan)->nama_kecamatan ?? '-' }}</p>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('harga-ikan-segar.show', ['harga_ikan_segar' => $item->id_harga, 'from_report' => 1]) }}" class="inline-flex items-center rounded bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Lihat Detail</a>
                                <a href="{{ route('laporan.harga.ikan.segar.pdf', $item->id_harga) }}" class="inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Unduh PDF</a>
                            </div>
                        </div>
                        @empty
                        <div class="rounded-lg border border-slate-200 p-4 text-center text-slate-500">Tidak ada data untuk ditampilkan.</div>
                        @endforelse
                    </div>

                    <!-- Desktop table -->
                    <div class="hidden md:block overflow-x-auto">
                        <div class="rounded-md border border-slate-300 overflow-hidden">
                            <table class="min-w-full text-base">
                                <thead class="bg-slate-100 text-slate-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Tanggal Input</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Tahun Pendataan</th>
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
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->tahun_pendataan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->nama_pasar ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->nama_pedagang ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $item->jenis_ikan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($item->desa)->nama_desa ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ optional($item->kecamatan)->nama_kecamatan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('harga-ikan-segar.show', ['harga_ikan_segar' => $item->id_harga, 'from_report' => 1]) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
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
                                    <td colspan="8" class="px-6 py-6 text-center text-sm text-gray-500">Tidak ada data untuk ditampilkan.</td>
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

    @if(session('error_export'))
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Tidak Dapat Mengunduh Excel',
                html: '<p class="text-gray-600">{{ session('error_export') }}</p>',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#dc2626',
                backdrop: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                },
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-2xl font-bold text-gray-800',
                    confirmButton: 'px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-200'
                }
            });
        });
    </script>
    @endif
</x-app-layout>
