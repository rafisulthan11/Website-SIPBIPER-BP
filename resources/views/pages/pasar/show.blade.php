<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Detail Pasar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-md shadow-md">
                <div class="p-5 sm:p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Informasi Pasar</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nama Pasar -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Nama Pasar</label>
                                <p class="text-base text-slate-900">{{ $pasar->nama_pasar ?? '-' }}</p>
                            </div>

                            <!-- Kecamatan -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Kecamatan</label>
                                <p class="text-base text-slate-900">{{ $pasar->kecamatan ?? '-' }}</p>
                            </div>

                            <!-- Desa -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Desa</label>
                                <p class="text-base text-slate-900">{{ $pasar->desa ?? '-' }}</p>
                            </div>

                            <!-- Latitude -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Latitude</label>
                                <p class="text-base text-slate-900">{{ $pasar->latitude ?? '-' }}</p>
                            </div>

                            <!-- Longitude -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Longitude</label>
                                <p class="text-base text-slate-900">{{ $pasar->longitude ?? '-' }}</p>
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 mb-1">Alamat</label>
                                <p class="text-base text-slate-900 whitespace-pre-line">{{ $pasar->alamat ?? '-' }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                                <p class="text-base text-slate-900">
                                    @if($pasar->status == 'aktif')
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
                        <a href="{{ route('pasar.edit', $pasar->id_pasar) }}" class="text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                            Edit
                        </a>
                        <a href="{{ route('pasar.index') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
