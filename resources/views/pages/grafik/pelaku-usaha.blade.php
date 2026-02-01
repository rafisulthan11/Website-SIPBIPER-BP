<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">Statistik Pelaku Usaha</h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Card (header + content) -->
            <div class="rounded-xl shadow-lg overflow-hidden">
                <!-- Tab Navigation (peta lokasi style) -->
                <div class="bg-blue-600 text-white">
                    <div class="px-6 py-4 lg:px-8">
                        <h1 class="font-extrabold text-xl sm:text-2xl">Statistik Pelaku Usaha</h1>
                    </div>
                </div>

                <!-- Content Area (peta lokasi style) -->
                <div class="bg-white p-4 sm:p-6">
                    <!-- Ringkasan -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Ringkasan</h3>
                        <p class="text-gray-600 text-sm">Total pelaku usaha terdaftar: <span class="font-bold text-gray-900">{{ $data['total'] }}</span></p>
                    </div>

                    <!-- Grafik Container -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Pie Chart - Distribusi Pelaku Usaha -->
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Pelaku Usaha</h3>
                            <div class="w-full" style="height: 300px;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>

                        <!-- Bar Chart - Jumlah per Kecamatan -->
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Jumlah Pelaku Usaha per Kecamatan</h3>
                            <div class="w-full" style="height: 300px;">
                                <canvas id="kecamatanChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Horizontal Bar Chart - 5 Komoditas Teratas -->
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">5 Komoditas Teratas (Produksi Pembudidaya)</h3>
                        @if($komoditasData->count() > 0)
                            <div class="w-full" style="height: 400px;">
                                <canvas id="komoditasChart"></canvas>
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <p class="text-lg font-semibold">Belum Ada Data Komoditas</p>
                                <p class="text-sm mt-2">Data komoditas akan ditampilkan setelah ada data jenis ikan dan jumlah pada pembudidaya</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Pie Chart - Distribusi Pelaku Usaha
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            const pieChart = new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: ['Pembudidaya', 'Pengolah', 'Pemasar'],
                    datasets: [{
                        data: [{{ $data['pembudidaya'] }}, {{ $data['pengolah'] }}, {{ $data['pemasar'] }}],
                        backgroundColor: [
                            'rgba(30, 64, 175, 0.8)',   // Dark Blue for Pembudidaya
                            'rgba(59, 130, 246, 0.8)',  // Blue for Pengolah
                            'rgba(96, 165, 250, 0.8)'   // Light Blue for Pemasar
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: label,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // Bar Chart - Jumlah per Kecamatan
            const kecamatanCtx = document.getElementById('kecamatanChart').getContext('2d');
            const kecamatanChart = new Chart(kecamatanCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($kecamatanData->pluck('nama_kecamatan')) !!},
                    datasets: [{
                        label: 'Total Pelaku Usaha',
                        data: {!! json_encode($kecamatanData->pluck('total')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                },
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total: ' + context.parsed.y + ' pelaku usaha';
                                }
                            }
                        }
                    }
                }
            });

            // Horizontal Bar Chart - 5 Komoditas Teratas
            @if($komoditasData->count() > 0)
            const komoditasCtx = document.getElementById('komoditasChart').getContext('2d');
            const komoditasChart = new Chart(komoditasCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($komoditasData->pluck('jenis_ikan')) !!},
                    datasets: [{
                        label: 'Total Jumlah',
                        data: {!! json_encode($komoditasData->pluck('total_jumlah')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total Jumlah: ' + context.parsed.x.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            @endif

            // Debounced resize helper for Chart.js
            let resizeTimer = null;
            function scheduleChartResize() {
                if (resizeTimer) clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => { 
                    if (pieChart) pieChart.resize();
                    if (kecamatanChart) kecamatanChart.resize();
                    @if($komoditasData->count() > 0)
                    if (komoditasChart) komoditasChart.resize();
                    @endif
                }, 160);
            }

            window.addEventListener('resize', scheduleChartResize);
            window.addEventListener('orientationchange', scheduleChartResize);
            window.addEventListener('sincan.sidebarToggled', scheduleChartResize);
        });
    </script>
    @endpush
</x-app-layout>
