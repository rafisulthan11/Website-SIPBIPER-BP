<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Harga Ikan') }}
        </h2>
    </x-slot>
    @php
        $displayData = (isset($backupData) && $backupData) ? $backupData : $hargaIkanSegar;
        $isReportView = request()->boolean('from_report');
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            @if(isset($backupData) && $backupData)
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-lg border border-green-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium text-sm">Menampilkan Versi Terakhir Diverifikasi</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-2 justify-end">
                            <a href="{{ route('harga-ikan-segar.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                                Kembali
                            </a>
                            @if(!$isReportView)
                            @if(auth()->user()->role->nama_role === 'staff')
                            <a href="{{ route('harga-ikan-segar.edit', $displayData->id_harga) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                                Edit Data Ini
                            </a>
                            @endif
                            @if(auth()->user()->role->nama_role === 'admin' && $hargaIkanSegar->status === 'pending')
                            <form action="{{ route('harga-ikan-segar.verify', $hargaIkanSegar->id_harga) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-block rounded bg-blue-600 px-4 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                    Verifikasi
                                </button>
                            </form>
                            <form action="{{ route('harga-ikan-segar.reject', $hargaIkanSegar->id_harga) }}" method="POST" class="inline form-reject-catatan" data-entity="data harga ikan ini">
                                @csrf
                                <input type="hidden" name="catatan_perbaikan" value="">
                                <button type="submit" class="inline-block rounded bg-orange-600 px-4 py-2 text-xs font-medium text-white hover:bg-orange-700">
                                    Tolak
                                </button>
                            </form>
                            @endif
                            @if(auth()->user()->isAdminOrSuperAdmin())
                            <form action="{{ route('harga-ikan-segar.destroy', $hargaIkanSegar->id_harga) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                            @endif
                            @endif
                        </div>
                    </div>

                    @if(isset($backupData) && $backupData)
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-semibold text-yellow-800 mb-1">Informasi Penting</h3>
                                <p class="text-sm text-yellow-700">
                                    Data ini sedang dalam proses verifikasi pembaruan. Halaman ini menampilkan <strong>versi terakhir yang telah diverifikasi</strong>. 
                                    Data yang diperbarui sedang menunggu verifikasi admin.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Profil Pasar -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pasar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-gray-500 block">Tanggal Input:</strong>
                                <p>{{ \Carbon\Carbon::parse($displayData->tanggal_input)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Nama Pasar:</strong>
                                <p>{{ $displayData->nama_pasar ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Nama Pedagang:</strong>
                                <p>{{ $displayData->nama_pedagang ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">NIK Pedagang:</strong>
                                <p>{{ $displayData->nik_pedagang ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Kecamatan:</strong>
                                <p>{{ $displayData->kecamatan->nama_kecamatan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong>
                                <p>{{ $displayData->desa->nama_desa ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Asal Ikan:</strong>
                                <p>{{ $displayData->asal_ikan ?? '-' }}</p>
                            </div>
                            @if($displayData->keterangan)
                            <div class="md:col-span-2 lg:col-span-3">
                                <strong class="font-medium text-gray-500 block">Keterangan/Catatan Pasar:</strong>
                                <p>{{ $displayData->keterangan }}</p>
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
                                <p>{{ $displayData->jenis_ikan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Ukuran:</strong>
                                <p>{{ $displayData->ukuran ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Satuan:</strong>
                                <p>{{ $displayData->satuan ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Harga Produsen:</strong>
                                <p>{{ $displayData->harga_produsen ? 'Rp ' . number_format($displayData->harga_produsen, 0, ',', '.') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Harga Konsumen:</strong>
                                <p>{{ $displayData->harga_konsumen ? 'Rp ' . number_format($displayData->harga_konsumen, 0, ',', '.') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Kuantitas Perminggu:</strong>
                                <p>{{ $displayData->kuantitas_perminggu ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-blue-700 block">Dibuat pada:</strong>
                                <p class="text-gray-700">{{ $displayData->created_at ? $displayData->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                                @if($displayData->createdBy)
                                    <div class="mt-2">
                                        <strong class="font-medium text-blue-700 block">Dibuat oleh:</strong>
                                        <p class="text-gray-700">{{ $displayData->createdBy->nama_lengkap }}</p>
                                        <p class="text-gray-500 text-xs">NIP: {{ $displayData->createdBy->nip ?? '-' }}</p>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <strong class="font-medium text-blue-700 block">Terakhir diubah:</strong>
                                <p class="text-gray-700">{{ $displayData->updated_at ? $displayData->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                                @if($displayData->updatedBy)
                                    <div class="mt-2">
                                        <strong class="font-medium text-blue-700 block">Terakhir diedit oleh:</strong>
                                        <p class="text-gray-700">{{ $displayData->updatedBy->nama_lengkap }}</p>
                                        <p class="text-gray-500 text-xs">NIP: {{ $displayData->updatedBy->nip ?? '-' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
