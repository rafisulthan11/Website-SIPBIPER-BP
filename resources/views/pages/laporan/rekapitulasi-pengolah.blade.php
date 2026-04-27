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
                    <h3 class="text-white text-2xl font-bold">Rekapitulasi Pengolah</h3>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Total Pelaku Usaha Card -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border-l-4 border-purple-600 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-600 rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">Total Pelaku Usaha (RTP)</h4>
                                    <p class="text-2xl font-bold text-purple-700">{{ number_format($uniqueRTP ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Pengolah Terdaftar</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Produksi Card -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-600 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-600 rounded-full p-3">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">Total Produksi Keseluruhan</h4>
                                    <p class="text-2xl font-bold text-blue-700">{{ number_format($totalProduksiDisplay, $satuanProduksi == 'Ton' ? 2 : 0, ',', '.') }} {{ $satuanProduksi }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Dari {{ $uniqueRTP ?? 0 }} Pengolah</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter row (clean grid) -->
                    @include('pages.laporan._rekap_filters', [
                        'kecamatans' => $kecamatans, 
                        'komoditas' => $komoditas, 
                        'kategoris' => $kategoris, 
                        'jenis_kegiatan_usaha_list' => $jenis_kegiatan_usaha_list,
                        'title' => 'Data Pengolah',
                        'reset_route' => route('laporan.rekapitulasi.pengolah'),
                        'kategori_label' => 'Semua Skala Usaha',
                        'show_bulan' => true,
                        'show_tahun' => true,
                        'show_kecamatan' => false,
                        'show_komoditas' => false
                    ])
                    <!-- Table controls -->
                    <form method="GET" action="{{ route('laporan.rekapitulasi.pengolah') }}" id="searchForm">
                        <!-- Preserve existing filters -->
                        <input type="hidden" name="kecamatan" value="{{ request('kecamatan') }}">
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                        <input type="hidden" name="jenis_kegiatan_usaha" value="{{ request('jenis_kegiatan_usaha') }}">
                        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                        
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
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, usaha, jenis kegiatan..." class="border-0 focus:ring-0 px-2 h-8 text-sm w-full sm:w-80" />
                                </div>
                                <a href="{{ route('laporan.rekapitulasi.pengolah.export', request()->query()) }}" title="Unduh Excel — semua hasil filter" aria-label="Unduh Excel untuk semua hasil filter" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm w-full sm:w-auto"
                                   :style="isMobile ? 'width:100%;' : 'width:auto;'">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                    Unduh Excel (Semua)
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Mobile cards -->
                    <div class="md:hidden space-y-3 mb-4">
                        @forelse($pengolahs as $p)
                        <div class="rounded-lg border border-slate-200 p-4 bg-white shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $p->nama_lengkap }}</p>
                                    <p class="text-xs text-slate-500">Tahun: {{ $p->tahun_pendataan ?? '-' }}</p>
                                </div>
                                <p class="text-xs text-slate-600">{{ $p->skala_usaha ?? '-' }}</p>
                            </div>
                            <div class="mt-2 text-sm text-slate-700 space-y-1">
                                <p><span class="font-medium">Usaha:</span> {{ $p->nama_usaha ?? '-' }}</p>
                                <p><span class="font-medium">Jenis Kegiatan:</span> {{ $p->jenis_kegiatan_usaha ?? '-' }}</p>
                                <p><span class="font-medium">Total Produksi:</span> {{ number_format($p->total_produksi_pengolah ?? 0, ($p->satuan_produksi == 'Ton' ? 2 : 0), ',', '.') }} {{ $p->satuan_produksi ?? 'Kg' }}</p>
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('pengolah.show', ['pengolah' => $p->id_pengolah, 'from_report' => 1]) }}" class="inline-flex items-center rounded bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Lihat Detail</a>
                                <a href="{{ route('laporan.rekapitulasi.pengolah.pdf', $p->id_pengolah) }}" class="inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Unduh PDF</a>
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
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Tahun Pendataan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Nama Usaha</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Jenis Kegiatan</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Skala Usaha</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Total Produksi</th>
                                        <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengolahs as $p)
                                    <tr class="border-t border-slate-200">
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_lengkap }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->tahun_pendataan ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->nama_usaha ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->jenis_kegiatan_usaha ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">{{ $p->skala_usaha ?? '-' }}</td>
                                        <td class="px-4 py-3 align-top text-slate-700">
                                            {{ number_format($p->total_produksi_pengolah ?? 0, ($p->satuan_produksi == 'Ton' ? 2 : 0), ',', '.') }} {{ $p->satuan_produksi ?? 'Kg' }}
                                        </td>
                                        <td class="px-4 py-3 align-top">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('pengolah.show', ['pengolah' => $p->id_pengolah, 'from_report' => 1]) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ route('laporan.rekapitulasi.pengolah.pdf', $p->id_pengolah) }}" title="Unduh PDF" class="inline-flex items-center rounded bg-blue-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
                                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                                    Unduh PDF
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">Tidak ada data untuk ditampilkan.</td>
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
                                Menampilkan {{ $pengolahs->firstItem() ?: 0 }} - {{ $pengolahs->lastItem() ?: 0 }} dari {{ $pengolahs->total() }} entri
                            </div>
                            <div>
                                {{ $pengolahs->links() }}
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
