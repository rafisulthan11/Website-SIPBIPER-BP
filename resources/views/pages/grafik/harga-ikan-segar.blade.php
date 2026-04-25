<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">Grafik Statistik</h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Card (header + content) -->
            <div class="rounded-xl shadow-lg overflow-hidden">
            <!-- Tab Navigation -->
            <div class="bg-blue-600 text-white">
                <div class="px-6 py-4 lg:px-8">
                    <h1 class="font-extrabold text-xl sm:text-2xl">Statistik & Analisis Harga Ikan</h1>
                    <p class="text-blue-100 text-sm mt-1">Data untuk mendukung pengambilan kebijakan bidang perikanan</p>
                </div>
            </div>

            <!-- Content Area -->
            <div class="bg-white p-4 sm:p-6">
                
                <!-- Filter Section -->
                <div class="mb-6 p-5 rounded-lg border" style="background: linear-gradient(to right, #eff6ff, #eef2ff); border-color: #bfdbfe;">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter Data
                    </h3>
                    <form method="GET" action="{{ route('grafik.harga.ikan.segar') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                                <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ request('tahun') == (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Jenis Ikan -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Ikan</label>
                                <select name="jenis_ikan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua Jenis Ikan</option>
                                    @foreach($jenisIkanList as $ikan)
                                        <option value="{{ $ikan }}" {{ request('jenis_ikan') == $ikan ? 'selected' : '' }}>{{ $ikan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Kecamatan -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Kecamatan</label>
                                <select name="kecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua Kecamatan</option>
                                    @foreach($kecamatanList as $kec)
                                        <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                            {{ $kec->nama_kecamatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Pasar -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pasar</label>
                                <select name="pasar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua Pasar</option>
                                    @foreach($pasarList as $p)
                                        <option value="{{ $p }}" {{ request('pasar') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-sm transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Terapkan Filter
                            </button>
                            <a href="{{ route('grafik.harga.ikan.segar') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold text-sm transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Statistik Ringkasan -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- Total Data -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-xs font-semibold uppercase">Total Data</p>
                                <p class="text-2xl font-bold mt-1">{{ number_format($statistics['total_data']) }}</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(96, 165, 250, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Harga Konsumen -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-xs font-semibold uppercase">Harga Konsumen</p>
                                <p class="text-2xl font-bold mt-1">Rp {{ number_format($statistics['rata_harga_konsumen']) }}</p>
                                <p class="text-green-100 text-xs mt-1">Rata-rata</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(74, 222, 128, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata Harga Produsen -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #a855f7, #9333ea);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-xs font-semibold uppercase">Harga Produsen</p>
                                <p class="text-2xl font-bold mt-1">Rp {{ number_format($statistics['rata_harga_produsen']) }}</p>
                                <p class="text-purple-100 text-xs mt-1">Rata-rata</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(196, 181, 253, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Margin/Selisih -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-xs font-semibold uppercase">Margin/Selisih</p>
                                <p class="text-2xl font-bold mt-1">Rp {{ number_format($statistics['margin']) }}</p>
                                <p class="text-orange-100 text-xs mt-1">({{ $statistics['margin_persen'] }}%)</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(251, 146, 60, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Fluktuasi Harga -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-xs font-semibold uppercase">Fluktuasi</p>
                                <p class="text-lg font-bold mt-1">Rp {{ number_format($statistics['harga_terendah']) }}</p>
                                <p class="text-xs">-</p>
                                <p class="text-lg font-bold">Rp {{ number_format($statistics['harga_tertinggi']) }}</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(248, 113, 113, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Kebijakan -->
                @if($statistics['total_data'] > 0)
                <div class="mb-6 border-l-4 p-4 rounded" style="background-color: #eff6ff; border-left-color: #3b82f6;">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" style="color: #2563eb;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold mb-1" style="color: #1e3a8a;">Analisis untuk Kebijakan</h4>
                            <p class="text-sm" style="color: #1e40af;">
                                <strong>Margin harga {{ $statistics['margin_persen'] }}%</strong> menunjukkan 
                                @if($statistics['margin_persen'] > 50)
                                    selisih yang cukup besar antara harga produsen dan konsumen. Pertimbangkan untuk mengkaji rantai distribusi guna memberikan nilai tambah optimal bagi pembudidaya.
                                @elseif($statistics['margin_persen'] > 30)
                                    selisih yang wajar dalam rantai distribusi. Tetap monitor fluktuasi harga untuk stabilitas pasar.
                                @else
                                    efisiensi distribusi yang baik. Kebijakan dapat difokuskan pada peningkatan kualitas dan produksi.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Grafik -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-center mb-4 text-slate-900">
                        Grafik Tren Harga Ikan Per Bulan
                        @if($tahun || $jenisIkan || $kecamatan || $pasar)
                            <span class="block text-sm font-normal text-gray-600 mt-1">
                                Filtered by: 
                                @if($tahun) Tahun {{ $tahun }} @endif
                                @if($jenisIkan) • {{ $jenisIkan }} @endif
                                @if($kecamatan) • {{ $kecamatanList->firstWhere('id_kecamatan', $kecamatan)->nama_kecamatan ?? 'Kecamatan' }} @endif
                                @if($pasar) • {{ $pasar }} @endif
                            </span>
                        @endif
                    </h3>
                    
                    <div class="w-full h-96 md:h-[500px] mx-auto">
                        <canvas id="chartTrenIkan" class="w-full h-full"></canvas>
                    </div>

                    <!-- Legend Kustom -->
                    <div class="flex flex-wrap justify-center gap-6 mt-6 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="width: 1rem; height: 1rem; border-radius: 0.25rem; background-color: #3b82f6;"></div>
                            <span class="font-semibold">Harga Konsumen (Rp)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="width: 1rem; height: 1rem; border-radius: 0.25rem; background-color: #22c55e;"></div>
                            <span class="font-semibold">Harga Produsen (Rp)</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartTrenIkan').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                    datasets: [
                        { 
                            label: 'Harga Konsumen', 
                            data: {!! json_encode(array_values($dataBulanKonsumen)) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(59, 130, 246, 1)',
                            pointHoverBorderWidth: 3
                        },
                        { 
                            label: 'Harga Produsen', 
                            data: {!! json_encode(array_values($dataBulanProdusen)) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(16, 185, 129, 1)',
                            pointHoverBorderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: { 
                        x: { 
                            grid: { 
                                display: false 
                            },
                            ticks: {
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }, 
                        y: { 
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                },
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: { 
                        legend: { 
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                },
                                afterBody: function(context) {
                                    if (context.length === 2) {
                                        const konsumen = context[0].parsed.y;
                                        const produsen = context[1].parsed.y;
                                        if (konsumen > 0 && produsen > 0) {
                                            const margin = konsumen - produsen;
                                            const marginPersen = ((margin / produsen) * 100).toFixed(1);
                                            return ['', 'Margin: Rp ' + margin.toLocaleString('id-ID') + ' (' + marginPersen + '%)'];
                                        }
                                    }
                                    return '';
                                }
                            }
                        }
                    }
                }
            });

            let resizeTimer = null;
            function scheduleChartResize() {
                if (resizeTimer) clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => { if (chart) chart.resize(); }, 160);
            }

            window.addEventListener('resize', scheduleChartResize);
            window.addEventListener('orientationchange', scheduleChartResize);
            window.addEventListener('sincan.sidebarToggled', scheduleChartResize);
        });
    </script>
    @endpush
</x-app-layout>