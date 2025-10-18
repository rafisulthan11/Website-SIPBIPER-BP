<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">Grafik Pendataan Wilayah</h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-600 py-3 rounded-t-lg shadow-md mb-0">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex gap-6 justify-center">
                        <a href="{{ route('grafik.tren.harga.komoditas') }}" class="px-4 py-2 {{ request()->routeIs('grafik.tren.harga.komoditas') ? 'bg-blue-700 text-white rounded-md' : 'text-white/90' }}">Harga Komoditas</a>
                        <a href="{{ route('grafik.harga.ikan.segar') }}" class="px-4 py-2 {{ request()->routeIs('grafik.harga.ikan.segar') ? 'bg-blue-700 text-white rounded-md' : 'text-white/90' }}">Harga Ikan Segar</a>
                        <a href="{{ route('grafik.pendataan.wilayah') }}" class="px-4 py-2 {{ request()->routeIs('grafik.pendataan.wilayah') ? 'bg-blue-700 text-white rounded-md' : 'text-white/90' }}">Jumlah Pendataan Wilayah</a>
                    </nav>
                </div>
            </div>

            <div class="max-w-7xl mx-auto bg-slate-50 rounded shadow-lg p-8 mt-0">
                <h3 class="text-3xl font-extrabold text-center mb-6">Grafik Pendataan Wilayah</h3>
                <div class="mb-4">
                    <div class="flex justify-end">
                        @php
                            $currentYear = date('Y');
                            $yearsList = $years ?? range($currentYear, $currentYear - 5);
                        @endphp
                        <form method="GET" action="{{ route('grafik.pendataan.wilayah') }}" class="flex items-center gap-3">
                            <div class="relative">
                                <select name="tahun" class="appearance-none px-4 h-9 min-w-[140px] border rounded bg-white text-sm pr-10">
                                    <option value="">Semua Tahun</option>
                                    @foreach($yearsList as $y)
                                        <option value="{{ $y }}" {{ request('tahun') == (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 text-slate-500 absolute top-1/2 right-3 -translate-y-1/2 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/></svg>
                            </div>
                            <button type="submit" class="h-9 min-w-[140px] bg-blue-700 hover:bg-blue-800 text-white rounded text-sm flex items-center justify-center">Terapkan</button>
                        </form>
                    </div>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <canvas id="chartPendataan" width="800" height="400"></canvas>
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
                        { label: '2020', data: [56, 88, 90, 78, 36, 25, 30, 68, 22, 40, 14, 66], backgroundColor: 'rgba(99,102,241,0.9)' },
                        { label: '2021', data: [88, 70, 95, 60, 35, 30, 32, 82, 46, 48, 16, 72], backgroundColor: 'rgba(107,114,128,0.9)' },
                        { label: '2022', data: [74, 54, 99, 70, 18, 15, 60, 98, 80, 36, 18, 78], backgroundColor: 'rgba(156,163,175,0.9)' },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { x: { stacked: false }, y: { beginAtZero: true } },
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>