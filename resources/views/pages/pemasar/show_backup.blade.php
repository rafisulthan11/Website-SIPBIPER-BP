<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Pemasar: ') . $pemasar->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex items-center justify-end mb-6 gap-2">
                         <a href="{{ route('pemasar.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('pemasar.edit', $pemasar->id_pemasar) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                            Edit Data Ini
                        </a>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $pemasar->jenis_kegiatan_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Pemasaran:</strong><p>{{ $pemasar->jenis_pemasaran ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Komoditas:</strong><p>{{ $pemasar->komoditas ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Wilayah Pemasaran:</strong><p>{{ $pemasar->wilayah_pemasaran ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pemilik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Lengkap:</strong><p>{{ $pemasar->nama_lengkap ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NIK:</strong><p>{{ $pemasar->nik_pemasar ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Kelamin:</strong><p>{{ $pemasar->jenis_kelamin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tempat Lahir:</strong><p>{{ $pemasar->tempat_lahir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tanggal Lahir:</strong><p>{{ $pemasar->tanggal_lahir ? \Carbon\Carbon::parse($pemasar->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pendidikan Terakhir:</strong><p>{{ $pemasar->pendidikan_terakhir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Perkawinan:</strong><p>{{ $pemasar->status_perkawinan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Tanggungan:</strong><p>{{ $pemasar->jumlah_tanggungan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $pemasar->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $pemasar->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $pemasar->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $pemasar->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $pemasar->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $pemasar->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $pemasar->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP Usaha:</strong><p>{{ $pemasar->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon Usaha:</strong><p>{{ $pemasar->telp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email Usaha:</strong><p>{{ $pemasar->email_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Skala Usaha:</strong><p>{{ $pemasar->skala_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $pemasar->status_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pemasar->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Usaha:</strong><p>{{ $pemasar->alamat_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Lokasi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Latitude:</strong><p>{{ $pemasar->latitude ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude:</strong><p>{{ $pemasar->longitude ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Lampiran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $lampiran = [
                                    'foto_ktp' => 'Foto KTP',
                                    'foto_sertifikat' => 'Foto Sertifikat',
                                    'foto_cpib_cbib' => 'Foto CPIB/CBIB',
                                    'foto_unit_usaha' => 'Foto Unit Usaha',
                                    'foto_kusuka' => 'Foto KUSUKA',
                                    'foto_nib' => 'Foto NIB',
                                ];
                            @endphp
                            @foreach($lampiran as $key => $label)
                                <div class="border rounded-lg p-3 bg-white">
                                    <strong class="font-medium text-gray-700 block mb-2 text-sm">{{ $label }}</strong>
                                    @if($pemasar->$key)
                                        @php
                                            $extension = pathinfo($pemasar->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
                                            // Remove 'storage/' prefix if it exists
                                            $filePath = str_starts_with($pemasar->$key, 'storage/') 
                                                ? substr($pemasar->$key, 8) 
                                                : $pemasar->$key;
                                        @endphp
                                        @if($isPdf)
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                                <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"/>
                                                </svg>
                                                <span class="text-sm">Lihat PDF</span>
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $label }}" class="w-full h-32 object-cover rounded hover:opacity-75 transition">
                                            </a>
                                        @endif
                                    @else
                                        <p class="text-gray-400 text-sm italic">Tidak ada file</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
