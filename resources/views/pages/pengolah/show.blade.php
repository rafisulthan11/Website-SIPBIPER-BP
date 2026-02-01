<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Pengolah: ') . $pengolah->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex items-center justify-end mb-6 gap-2">
                         <a href="{{ route('pengolah.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('pengolah.edit', $pengolah->id_pengolah) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                            Edit Data Ini
                        </a>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $pengolah->jenis_kegiatan_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pemilik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Lengkap:</strong><p>{{ $pengolah->nama_lengkap ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NIK:</strong><p>{{ $pengolah->nik_pengolah ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Kelamin:</strong><p>{{ $pengolah->jenis_kelamin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tempat Lahir:</strong><p>{{ $pengolah->tempat_lahir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tanggal Lahir:</strong><p>{{ $pengolah->tanggal_lahir ? \Carbon\Carbon::parse($pengolah->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pendidikan Terakhir:</strong><p>{{ $pengolah->pendidikan_terakhir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Perkawinan:</strong><p>{{ $pengolah->status_perkawinan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Tanggungan:</strong><p>{{ $pengolah->jumlah_tanggungan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $pengolah->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $pengolah->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $pengolah->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $pengolah->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $pengolah->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $pengolah->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>
                        
                        <!-- Informasi Umum -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Informasi Umum</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $pengolah->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nama Kelompok:</strong><p>{{ $pengolah->nama_kelompok ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Skala Usaha:</strong><p>{{ $pengolah->skala_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $pengolah->status_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pengolah->tahun_mulai_usaha ?? '-' }}</p></div>
                        </div>

                        <!-- Lokasi Usaha -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Lokasi Usaha</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Kecamatan Usaha:</strong><p>{{ $pengolah->kecamatanUsaha->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa Usaha:</strong><p>{{ $pengolah->desaUsaha->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Latitude:</strong><p>{{ $pengolah->latitude ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude:</strong><p>{{ $pengolah->longitude ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap Usaha:</strong><p>{{ $pengolah->alamat_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Produksi</h3>
                        
                        @if($pengolah->produksi_data && is_array($pengolah->produksi_data) && count($pengolah->produksi_data) > 0)
                        @foreach($pengolah->produksi_data as $index => $produksi)
                            @if(isset($produksi['nama_merk']) || isset($produksi['periode']))
                            <div class="@if(!$loop->first) mt-6 @endif">
                                <h4 class="font-semibold text-slate-800 mb-3">Produk {{ $index + 1 }}</h4>
                                
                                <!-- Informasi Produk -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-4">
                                    <div><strong class="font-medium text-gray-500 block">Nama Merk:</strong><p>{{ $produksi['nama_merk'] ?? '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Periode:</strong><p>{{ $produksi['periode'] ?? '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Kapasitas Terpasang:</strong><p>{{ isset($produksi['kapasitas_terpasang']) ? number_format($produksi['kapasitas_terpasang'], 2) . ' Kg' : '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Jumlah Hari Produksi/Bulan:</strong><p>{{ $produksi['jumlah_hari_produksi'] ?? '-' }} hari</p></div>
                                </div>
                                    
                                @if(isset($produksi['bulan_produksi']) && is_array($produksi['bulan_produksi']) && count($produksi['bulan_produksi']) > 0)
                                <div class="mb-4">
                                    <strong class="font-medium text-gray-500 block mb-2">Bulan Produksi:</strong>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                        @endphp
                                        @foreach($produksi['bulan_produksi'] as $bulan)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ $bulanNames[$bulan - 1] ?? $bulan }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if(isset($produksi['sertifikat_lahan']) && is_array($produksi['sertifikat_lahan']) && count($produksi['sertifikat_lahan']) > 0)
                                <div class="mb-4">
                                    <strong class="font-medium text-gray-500 block mb-2">Sertifikat Lahan:</strong>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($produksi['sertifikat_lahan'] as $sertifikat)
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">{{ $sertifikat }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Informasi Biaya dan Harga -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-4">
                                    <div><strong class="font-medium text-gray-500 block">Biaya Produksi:</strong><p>Rp {{ isset($produksi['biaya_produksi']) ? number_format($produksi['biaya_produksi'], 0, ',', '.') : '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Biaya Lain-lain:</strong><p>Rp {{ isset($produksi['biaya_lain']) ? number_format($produksi['biaya_lain'], 0, ',', '.') : '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Harga Jual:</strong><p>Rp {{ isset($produksi['harga_jual']) ? number_format($produksi['harga_jual'], 0, ',', '.') : '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Harga Produksi:</strong><p>{{ isset($produksi['harga_produksi_qty']) ? number_format($produksi['harga_produksi_qty'], 2) . ' Kg' : '-' }} - Rp {{ isset($produksi['harga_produksi_harga']) ? number_format($produksi['harga_produksi_harga'], 0, ',', '.') : '-' }}</p></div>
                                </div>

                                @if(isset($produksi['bahan_baku']) && is_array($produksi['bahan_baku']) && count($produksi['bahan_baku']) > 0)
                                <div class="mb-4">
                                    <strong class="font-medium text-gray-500 block mb-2">Bahan Baku:</strong>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm border border-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 text-left border-b">No</th>
                                                    <th class="px-4 py-2 text-left border-b">Bahan</th>
                                                    <th class="px-4 py-2 text-left border-b">Asal Bahan Baku</th>
                                                    <th class="px-4 py-2 text-left border-b">Harga Bahan Baku</th>
                                                    <th class="px-4 py-2 text-left border-b">Qty (kg)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($produksi['bahan_baku'] as $bahanIndex => $bahan)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 border-b">{{ $bahanIndex + 1 }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $bahan['bahan'] ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $bahan['asal'] ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">Rp {{ isset($bahan['harga']) ? number_format($bahan['harga'], 0, ',', '.') : '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ isset($bahan['qty']) ? number_format($bahan['qty'], 2) : '-' }} kg</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <!-- Pemasaran -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                                    <div class="lg:col-span-2"><strong class="font-medium text-gray-500 block">Pemasaran:</strong><p>{{ $produksi['pemasaran'] ?? '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Jumlah Produk:</strong><p>{{ isset($produksi['jumlah_produk_qty']) ? number_format($produksi['jumlah_produk_qty'], 2) . ' Kg' : '-' }} - {{ $produksi['jumlah_produk_pack'] ?? '-' }} pack</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Harga Jual/pack:</strong><p>Rp {{ isset($produksi['harga_jual_pack']) ? number_format($produksi['harga_jual_pack'], 0, ',', '.') : '-' }}</p></div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @else
                            <p class="text-slate-600">Belum ada data produksi.</p>
                        @endif
                    </div>

                    <!-- Kontainer Tenaga Kerja -->
                    @if($pengolah->tenaga_kerja_data && is_array($pengolah->tenaga_kerja_data))
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tenaga Kerja</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- WNI -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNI</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wni_laki_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wni_laki_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pengolah->tenaga_kerja_data['wni_laki_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wni_perempuan_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wni_perempuan_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pengolah->tenaga_kerja_data['wni_perempuan_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNA</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wna_laki_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wna_laki_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pengolah->tenaga_kerja_data['wna_laki_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wna_perempuan_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pengolah->tenaga_kerja_data['wna_perempuan_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pengolah->tenaga_kerja_data['wna_perempuan_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tenaga Kerja</h3>
                        <p class="text-slate-600">Belum ada data tenaga kerja.</p>
                    </div>
                    @endif

                    <!-- Kontainer Lampiran -->
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
                                    'foto_sertifikat_pirt' => 'Foto Sertifikat PIRT',
                                    'foto_sertifikat_halal' => 'Foto Sertifikat Halal',
                                ];
                            @endphp
                            @foreach($lampiran as $key => $label)
                                <div class="border rounded-lg p-3 bg-white">
                                    <strong class="font-medium text-gray-700 block mb-2 text-sm">{{ $label }}</strong>
                                    @if($pengolah->$key)
                                        @php
                                            $extension = pathinfo($pengolah->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
                                            // Remove 'storage/' prefix if it exists
                                            $filePath = str_starts_with($pengolah->$key, 'storage/') 
                                                ? substr($pengolah->$key, 8) 
                                                : $pengolah->$key;
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

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-blue-700 block">Dibuat pada:</strong>
                                <p class="text-gray-700">{{ $pengolah->created_at ? $pengolah->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-blue-700 block">Terakhir diubah:</strong>
                                <p class="text-gray-700">{{ $pengolah->updated_at ? $pengolah->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
