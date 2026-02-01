<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">Grafik Tren Jumlah Pendataan Wilayah</h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Card (header + content) -->
            <div class="rounded-xl shadow-lg overflow-hidden">
            <!-- Tab Navigation (peta lokasi style) -->
            <div class="bg-blue-600 text-white">
                <div class="px-6 py-4 lg:px-8">
                    <h1 class="font-extrabold text-xl sm:text-2xl">Statistik Pendataan Wilayah</h1>
                </div>
            </div>

            <!-- Content Area (peta lokasi style) -->
            <div class="bg-white p-4 sm:p-6">
                <h3 class="text-3xl font-extrabold text-center mb-6 text-slate-900">Grafik Jumlah Pendataan Wilayah</h3>
                    <div class="mb-4">
                        <div class="flex justify-center sm:justify-end">
                            @php
                                $currentYear = date('Y');
                                $yearsList = $years ?? range($currentYear, $currentYear - 5);
                            @endphp
                            <form method="GET" action="{{ route('grafik.pendataan.wilayah') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                                <div class="relative w-full sm:w-auto z-0">
                                    <select name="tahun" class="appearance-none px-4 h-9 w-full sm:min-w-[140px] border rounded bg-white text-sm pr-10 relative z-0 text-slate-700">
                                        <option value="">Semua Tahun</option>
                                        @foreach($yearsList as $y)
                                            <option value="{{ $y }}" {{ request('tahun') == (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="w-4 h-4 text-slate-500 absolute top-1/2 right-3 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <div class="w-full sm:w-auto">
                                    <button type="submit" class="w-full sm:min-w-[140px] h-9 bg-blue-700 hover:bg-blue-800 text-white rounded text-sm flex items-center justify-center">Terapkan</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="w-full h-64 sm:h-80 md:h-[420px] mx-auto">
                        <canvas id="chartPendataan" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
            </div>
        </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartPendataan').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                    datasets: [
                        { 
                            label: 'Pembudidaya', 
                            data: {!! json_encode(array_values($pembudidayaPerBulan)) !!},
                            backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1
                        },
                        { 
                            label: 'Pengolah', 
                            data: {!! json_encode(array_values($pengolahPerBulan)) !!},
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        { 
                            label: 'Pemasar', 
                            data: {!! json_encode(array_values($pemasarPerBulan)) !!},
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: 'rgba(249, 115, 22, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { 
                        x: { 
                            stacked: false,
                            grid: { display: false }
                        }, 
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: { 
                        legend: { 
                            position: 'top',
                            labels: {
                                font: { size: 12 },
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' pendataan';
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