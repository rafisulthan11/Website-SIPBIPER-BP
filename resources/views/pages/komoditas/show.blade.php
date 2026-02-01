<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Detail Komoditas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-md shadow-md">
                <div class="p-5 sm:p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Komoditas</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Komoditas -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Nama Komoditas</label>
                                <p class="text-base text-slate-900">{{ $komoditas->nama_komoditas ?? '-' }}</p>
                            </div>

                            <!-- Tipe -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Tipe</label>
                                <p class="text-base text-slate-900">{{ $komoditas->tipe ?? '-' }}</p>
                            </div>

                            <!-- Kode -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Kode</label>
                                <p class="text-base text-slate-900">{{ $komoditas->kode ?? '-' }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                                <p class="text-base text-slate-900">
                                    @if($komoditas->status == 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 mt-6">
                        <a href="{{ route('komoditas.edit', $komoditas->id_komoditas) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                            Edit
                        </a>
                        <a href="{{ route('komoditas.index') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
