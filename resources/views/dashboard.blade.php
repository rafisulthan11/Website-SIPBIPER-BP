<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Welcome card -->
            <div class="bg-white/90 border border-slate-200 shadow-sm rounded-md p-5 sm:p-6 mb-6">
                <h3 class="text-xl font-extrabold text-slate-800 mb-2">Selamat Datang, Dinas Perikanan!</h3>
                <p class="text-slate-600">Anda telah masuk ke sistem informasi pendataan bidang perikanan budidaya dan pasca panen dinas perikanan jember.</p>
            </div>

            <!-- Grafik Statistik (placeholder cards) -->
            <h4 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-3">Grafik Statistik</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                    <div class="bg-indigo-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <p class="font-semibold text-slate-800">Pelaku Usaha</p>
                        <div class="mt-4 h-16 flex items-center justify-end text-slate-700">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('grafik.pelaku.usaha') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>

                <div class="bg-indigo-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <p class="font-semibold text-slate-800">Harga Ikan Segar</p>
                        <div class="mt-4 h-16 flex items-center justify-end text-slate-700">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9s3-4.5 9-4.5S21 9 21 9s-3 4.5-9 4.5S3 9 3 9zm0 0l6 6m12-6l-6 6"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('grafik.harga.ikan.segar') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>

                    <div class="bg-indigo-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <p class="font-semibold text-slate-800">Pendataan Wilayah</p>
                        <div class="mt-4 h-16 flex items-center justify-end text-slate-700">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l6-3 6 3 6-3v12l-6 3-6-3-6 3v-12z"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('grafik.pendataan.wilayah') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>
            </div>

            <!-- Ringkasan -->
            <h4 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-3">Ringkasan Jumlah Pembudidaya, Pemasar dan Pengolah</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-cyan-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <div class="text-slate-800 text-sm">{{ $pembudidayaCount }}</div>
                        <p class="font-semibold text-slate-800">Jumlah Pembudidaya</p>
                        <div class="mt-4 h-10 flex items-center justify-end text-slate-700">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25v-3.375A2.625 2.625 0 017.125 14.25h1.5A2.625 2.625 0 0111.25 16.875V20.25M8.25 7.5a2.25 2.25 0 110-4.5 2.25 2.25 0 010 4.5zM15.75 9.75h3.375a2.625 2.625 0 012.625 2.625V20.25M18.75 7.5a2.25 2.25 0 110-4.5 2.25 2.25 0 010 4.5z"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('pembudidaya.index') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>

                <div class="bg-cyan-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <div class="text-slate-800 text-sm">{{ $pemasarCount }}</div>
                        <p class="font-semibold text-slate-800">Jumlah Pemasar</p>
                        <div class="mt-4 h-10 flex items-center justify-end text-slate-700">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835L5.964 9.75m0 0l.808 3.232A2.25 2.25 0 008.957 15h7.293a2.25 2.25 0 002.121-1.5l1.5-4A2.25 2.25 0 0017.743 6H5.964m0 0H3.375M6 20.25a.75.75 0 100-1.5.75.75 0 000 1.5zm10.5 0a.75.75 0 100-1.5.75.75 0 000 1.5z"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('pemasar.index') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>

                <div class="bg-cyan-200/60 rounded-md shadow-[0_6px_0_rgba(15,23,42,0.3)]">
                    <div class="p-4">
                        <div class="text-slate-800 text-sm">{{ $pengolahCount }}</div>
                        <p class="font-semibold text-slate-800">Jumlah Pengolah</p>
                        <div class="mt-4 h-10 flex items-center justify-end text-slate-700">
                            <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3v3.75M14.25 3v3.75m-9 9h13.5M4.5 21h15a1.5 1.5 0 001.5-1.5v-6.75a1.5 1.5 0 00-1.5-1.5h-15a1.5 1.5 0 00-1.5 1.5V19.5A1.5 1.5 0 004.5 21z"/></svg>
                        </div>
                    </div>
                    <a href="{{ route('pengolah.index') }}" class="block bg-slate-800 text-white rounded-b-md px-4 py-2 text-sm font-semibold text-center">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
