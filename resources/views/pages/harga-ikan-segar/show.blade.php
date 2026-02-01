<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Harga Ikan Segar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex items-center justify-end mb-6 gap-2">
                         <a href="{{ route('harga-ikan-segar.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('harga-ikan-segar.edit', $hargaIkanSegar->id_harga) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                            Edit Data Ini
                        </a>
                    </div>

                    <!-- Profil Pasar -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pasar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-gray-500 block">Tanggal Input:</strong>
                                <p>{{ \Carbon\Carbon::parse($hargaIkanSegar->tanggal_input)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Nama Pasar:</strong>
                                <p>{{ $hargaIkanSegar->nama_pasar ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Nama Pedagang:</strong>
                                <p>{{ $hargaIkanSegar->nama_pedagang ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Kecamatan:</strong>
                                <p>{{ $hargaIkanSegar->kecamatan->nama_kecamatan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong>
                                <p>{{ $hargaIkanSegar->desa->nama_desa ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Asal Ikan:</strong>
                                <p>{{ $hargaIkanSegar->asal_ikan ?? '-' }}</p>
                            </div>
                            @if($hargaIkanSegar->keterangan)
                            <div class="md:col-span-2 lg:col-span-3">
                                <strong class="font-medium text-gray-500 block">Keterangan/Catatan Pasar:</strong>
                                <p>{{ $hargaIkanSegar->keterangan }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Ikan -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Detail Ikan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-gray-500 block">Jenis Ikan:</strong>
                                <p>{{ $hargaIkanSegar->jenis_ikan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Ukuran:</strong>
                                <p>{{ $hargaIkanSegar->ukuran ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Satuan:</strong>
                                <p>{{ $hargaIkanSegar->satuan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Harga Produsen:</strong>
                                <p>{{ $hargaIkanSegar->harga_produsen ? 'Rp ' . number_format($hargaIkanSegar->harga_produsen, 0, ',', '.') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Harga Konsumen:</strong>
                                <p>{{ $hargaIkanSegar->harga_konsumen ? 'Rp ' . number_format($hargaIkanSegar->harga_konsumen, 0, ',', '.') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Kuantitas Perminggu:</strong>
                                <p>{{ $hargaIkanSegar->kuantitas_perminggu ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-blue-700 block">Dibuat pada:</strong>
                                <p class="text-gray-700">{{ $hargaIkanSegar->created_at ? $hargaIkanSegar->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-blue-700 block">Terakhir diubah:</strong>
                                <p class="text-gray-700">{{ $hargaIkanSegar->updated_at ? $hargaIkanSegar->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
