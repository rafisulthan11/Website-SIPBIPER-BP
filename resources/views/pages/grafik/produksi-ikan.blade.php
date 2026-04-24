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
                    <h1 class="font-extrabold text-xl sm:text-2xl">Statistik & Analisis Produksi Ikan</h1>
                    <p class="text-blue-100 text-sm mt-1">Data produksi untuk mendukung pengambilan kebijakan bidang perikanan</p>
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
                    <form method="GET" action="{{ route('grafik.produksi.ikan') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                            <!-- Filter Komoditas -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Komoditas Ikan</label>
                                <select name="komoditas" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="">Semua Komoditas</option>
                                    @foreach($komoditasList as $k)
                                        <option value="{{ $k }}" {{ request('komoditas') == $k ? 'selected' : '' }}>{{ $k }}</option>
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
                            <a href="{{ route('grafik.produksi.ikan') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold text-sm transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>

                        <!-- Active Filters Display -->
                        @if($tahun || $kecamatan || $komoditas)
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-semibold text-gray-600">Filter Aktif:</span>
                                @if($tahun)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Tahun: {{ $tahun }}
                                    </span>
                                @endif
                                @if($kecamatan)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $kecamatanList->firstWhere('id_kecamatan', $kecamatan)->nama_kecamatan ?? 'Kecamatan' }}
                                    </span>
                                @endif
                                @if($komoditas)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $komoditas }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- Statistik Ringkasan -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- Total Produksi Keseluruhan -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-xs font-semibold uppercase">Total Produksi</p>
                                <p class="text-2xl font-bold mt-1">{{ number_format($statistics['total_produksi_keseluruhan'], 2) }}</p>
                                <p class="text-blue-100 text-xs mt-1">Kg/Tahun</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(96, 165, 250, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Produksi Pembudidaya -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #22c55e, #16a34a);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-xs font-semibold uppercase">Pembudidaya</p>
                                <p class="text-2xl font-bold mt-1">{{ number_format($statistics['total_produksi_pembudidaya'], 2) }}</p>
                                <p class="text-green-100 text-xs mt-1">Kg ({{ $statistics['jumlah_pembudidaya'] }} pelaku)</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(74, 222, 128, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Produksi Pengolah -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #a855f7, #9333ea);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-xs font-semibold uppercase">Pengolah</p>
                                <p class="text-2xl font-bold mt-1">{{ number_format($statistics['total_produksi_pengolah'], 2) }}</p>
                                <p class="text-purple-100 text-xs mt-1">Kg ({{ $statistics['jumlah_pengolah'] }} pelaku)</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(196, 181, 253, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm1 8a1 1 0 100 2h6a1 1 0 100-2H7zm1 4a1 1 0 011-1h4a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Rata-rata per Bulan -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-orange-100 text-xs font-semibold uppercase">Rata-rata/Bulan</p>
                                <p class="text-2xl font-bold mt-1">{{ number_format($statistics['rata_rata_per_bulan'], 2) }}</p>
                                <p class="text-orange-100 text-xs mt-1">Kg</p>
                            </div>
                            <div class="rounded-full p-3" style="background-color: rgba(251, 146, 60, 0.5);">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Puncak Produksi -->
                    <div class="rounded-lg p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-xs font-semibold uppercase">Puncak Produksi</p>
                                <p class="text-lg font-bold mt-1">{{ $bulanNames[$statistics['bulan_tertinggi']] ?? '-' }}</p>
                                <p class="text-red-100 text-xs mt-1">{{ number_format($statistics['produksi_tertinggi'], 2) }} Kg</p>
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
                @if($statistics['total_produksi_keseluruhan'] > 0)
                <div class="mb-6 border-l-4 p-4 rounded" style="background-color: #eff6ff; border-left-color: #3b82f6;">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" style="color: #2563eb;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold mb-1" style="color: #1e3a8a;">Analisis untuk Kebijakan</h4>
                            <p class="text-sm" style="color: #1e40af;">
                                <strong>Total produksi {{ number_format($statistics['total_produksi_keseluruhan'], 2) }} Kg</strong> dari 
                                {{ $statistics['jumlah_pembudidaya'] }} pembudidaya dan {{ $statistics['jumlah_pengolah'] }} pengolah.
                                @php
                                    $persenPembudidaya = $statistics['total_produksi_keseluruhan'] > 0 
                                        ? round(($statistics['total_produksi_pembudidaya'] / $statistics['total_produksi_keseluruhan']) * 100, 1) 
                                        : 0;
                                @endphp
                                Pembudidaya berkontribusi <strong>{{ $persenPembudidaya }}%</strong> dari total produksi.
                                Puncak produksi terjadi pada bulan <strong>{{ $bulanNames[$statistics['bulan_tertinggi']] ?? '-' }}</strong>.
                                @if($statistics['produksi_tertinggi'] > $statistics['rata_rata_per_bulan'] * 1.5)
                                    Terdapat fluktuasi produksi yang signifikan, pertimbangkan strategi stabilisasi produksi.
                                @else
                                    Produksi relatif stabil sepanjang tahun, fokus pada peningkatan kualitas dan nilai tambah.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold text-yellow-900 mb-1">Data Produksi Tidak Ditemukan</h4>
                            <p class="text-sm text-yellow-800">
                                @if($tahun || $kecamatan || $komoditas)
                                    Tidak ada data produksi untuk filter yang dipilih 
                                    @if($tahun)<strong>(Tahun: {{ $tahun }})</strong>@endif
                                    @if($kecamatan)<strong>(Kecamatan: {{ $kecamatanList->firstWhere('id_kecamatan', $kecamatan)->nama_kecamatan ?? '-' }})</strong>@endif
                                    @if($komoditas)<strong>(Komoditas: {{ $komoditas }})</strong>@endif.
                                    <br>Silakan coba filter lain atau klik <strong>Reset Filter</strong> untuk melihat semua data.
                                @else
                                    Belum ada data produksi yang tersedia dalam sistem. Silakan tambahkan data produksi terlebih dahulu pada menu Data Pembudidaya atau Data Pengolah.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Grafik -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-center mb-4 text-slate-900">
                        Grafik Produksi Ikan Per Bulan
                        @if($tahun || $kecamatan || $komoditas)
                            <span class="block text-sm font-normal text-gray-600 mt-1">
                                Filtered by: 
                                @if($tahun) Tahun {{ $tahun }} @endif
                                @if($kecamatan) • {{ $kecamatanList->firstWhere('id_kecamatan', $kecamatan)->nama_kecamatan ?? 'Kecamatan' }} @endif
                                @if($komoditas) • {{ $komoditas }} @endif
                            </span>
                        @endif
                    </h3>
                    
                    <div class="w-full h-96 md:h-[500px] mx-auto">
                        <canvas id="chartProduksi" class="w-full h-full"></canvas>
                    </div>

                    <!-- Legend Kustom -->
                    <div class="flex flex-wrap justify-center gap-6 mt-6 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #22c55e;"></div>
                            <span class="font-semibold">Pembudidaya (Kg)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #a855f7;"></div>
                            <span class="font-semibold">Pengolah (Kg)</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded mr-2" style="background-color: #3b82f6;"></div>
                            <span class="font-semibold">Total Produksi (Kg)</span>
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
            const ctx = document.getElementById('chartProduksi').getContext('2d');
            
            // Calculate combined data
            const pembudidayaData = {!! json_encode(array_values($pembudidayaPerBulan)) !!};
            const pengolahData = {!! json_encode(array_values($pengolahPerBulan)) !!};
            const combinedData = pembudidayaData.map((val, idx) => val + pengolahData[idx]);
            
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                    datasets: [
                        { 
                            label: 'Pembudidaya', 
                            data: pembudidayaData,
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 2,
                            borderRadius: 4,
                        },
                        { 
                            label: 'Pengolah', 
                            data: pengolahData,
                            backgroundColor: 'rgba(168, 85, 247, 0.8)',
                            borderColor: 'rgba(168, 85, 247, 1)',
                            borderWidth: 2,
                            borderRadius: 4,
                        },
                        { 
                            label: 'Total Produksi', 
                            data: combinedData,
                            type: 'line',
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
                                    return value.toLocaleString('id-ID') + ' Kg';
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
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y.toLocaleString('id-ID') + ' Kg';
                                    return label;
                                },
                                footer: function(tooltipItems) {
                                    if (tooltipItems.length > 0) {
                                        const index = tooltipItems[0].dataIndex;
                                        const pembudidaya = pembudidayaData[index];
                                        const pengolah = pengolahData[index];
                                        const total = pembudidaya + pengolah;
                                        
                                        if (total > 0) {
                                            const persenPembudidaya = ((pembudidaya / total) * 100).toFixed(1);
                                            const persenPengolah = ((pengolah / total) * 100).toFixed(1);
                                            return [
                                                '',
                                                'Pembudidaya: ' + persenPembudidaya + '%',
                                                'Pengolah: ' + persenPengolah + '%'
                                            ];
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
