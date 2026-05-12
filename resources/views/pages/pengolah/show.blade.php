<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Detail Data Pengolah: ") . (isset($backupData) && $backupData ? $backupData->nama_lengkap : $pengolah->nama_lengkap) }}
        </h2>
    </x-slot>

    @php
        $displayData = (isset($backupData) && $backupData) ? $backupData : $pengolah;
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
                            <a href="{{ route('pengolah.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                                Kembali
                            </a>
                            @if(!$isReportView)
                            @if(auth()->user()->role->nama_role === 'staff')
                            <a href="{{ route('pengolah.edit', $displayData->id_pengolah) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                                Edit Data Ini
                            </a>
                            @endif
                            @if(auth()->user()->role->nama_role === 'admin' && $pengolah->status === 'pending')
                            <form action="{{ route('pengolah.verify', $pengolah->id_pengolah) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-block rounded bg-blue-600 px-4 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                    Verifikasi
                                </button>
                            </form>
                            <form action="{{ route('pengolah.reject', $pengolah->id_pengolah) }}" method="POST" class="inline form-reject-catatan" data-entity="data pengolah ini">
                                @csrf
                                <input type="hidden" name="catatan_perbaikan" value="">
                                <button type="submit" class="inline-block rounded bg-orange-600 px-4 py-2 text-xs font-medium text-white hover:bg-orange-700">
                                    Tolak
                                </button>
                            </form>
                            @endif
                            @if(auth()->user()->isAdminOrSuperAdmin())
                            <form action="{{ route('pengolah.destroy', $pengolah->id_pengolah) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
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

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $displayData->jenis_kegiatan_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pemilik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Lengkap:</strong><p>{{ $displayData->nama_lengkap ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NIK:</strong><p>{{ $displayData->nik_pengolah ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Kelamin:</strong><p>{{ $displayData->jenis_kelamin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tempat Lahir:</strong><p>{{ $displayData->tempat_lahir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tanggal Lahir:</strong><p>{{ $displayData->tanggal_lahir ? \Carbon\Carbon::parse($displayData->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pendidikan Terakhir:</strong><p>{{ $displayData->pendidikan_terakhir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Perkawinan:</strong><p>{{ $displayData->status_perkawinan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Tanggungan:</strong><p>{{ $displayData->jumlah_tanggungan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $displayData->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $displayData->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $displayData->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $displayData->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $displayData->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $displayData->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Izin Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">NIB:</strong><p>{{ $displayData->nib ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP:</strong><p>{{ $displayData->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">KUSUKA:</strong><p>{{ $displayData->kusuka ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pengesahan MENKUMHAM:</strong><p>{{ $displayData->pengesahan_menkumham ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">TDU-PHP:</strong><p>{{ $displayData->tdu_php ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">AKTA Pendirian Usaha:</strong><p>{{ $displayData->akta_pendirian_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">IMB:</strong><p>{{ $displayData->imb ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perikanan:</strong><p>{{ $displayData->siup_perikanan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perdagangan:</strong><p>{{ $displayData->siup_perdagangan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SPPL:</strong><p>{{ $displayData->sppl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">UKL-UPL:</strong><p>{{ $displayData->ukl_upl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">AMDAL:</strong><p>{{ $displayData->amdal ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>

                        <h4 class="text-base font-semibold text-slate-700 mb-3">Informasi Umum</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $displayData->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nama Kelompok:</strong><p>{{ $displayData->nama_kelompok ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Komoditas:</strong><p>{{ $displayData->komoditas ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Skala Usaha:</strong><p>{{ $displayData->skala_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $displayData->status_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $displayData->tahun_mulai_usaha ?? '-' }}</p></div>
                        </div>

                        <h4 class="text-base font-semibold text-slate-700 mb-3">Lokasi Usaha</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Kecamatan Usaha:</strong><p>{{ $displayData->kecamatanUsaha->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa Usaha:</strong><p>{{ $displayData->desaUsaha->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Latitude:</strong><p>{{ $displayData->latitude ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude:</strong><p>{{ $displayData->longitude ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap Usaha:</strong><p>{{ $displayData->alamat_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Produksi</h3>

                        @if($displayData->produksi_data && is_array($displayData->produksi_data) && count($displayData->produksi_data) > 0)
                            @foreach($displayData->produksi_data as $index => $produksi)
                                @php
                                    $hasProductionData = collect($produksi)->filter(function ($value) {
                                        return !is_null($value) && $value !== '' && $value !== [];
                                    })->isNotEmpty();
                                @endphp

                                @if($hasProductionData)
                                    <div class="@if(!$loop->first) mt-6 @endif p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                        <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-200">
                                            <h4 class="font-semibold text-slate-800">Produk {{ $index + 1 }}</h4>
                                            @if(!empty($produksi['komoditas']))
                                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $produksi['komoditas'] }}</span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-4">
                                            <div><strong class="font-medium text-gray-500 block">Nama Merk:</strong><p>{{ $produksi['nama_merk'] ?? '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Komoditas:</strong><p>{{ $produksi['komoditas'] ?? '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Kapasitas Terpasang:</strong><p>{{ isset($produksi['kapasitas_terpasang']) ? number_format($produksi['kapasitas_terpasang'], 2) . ' Kg' : '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Jumlah Hari Produksi/Bulan:</strong><p>{{ $produksi['jumlah_hari_produksi'] ?? '-' }} hari</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Harga Jual:</strong><p>Rp {{ isset($produksi['harga_jual']) ? number_format($produksi['harga_jual'], 0, ',', '.') : '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Pemasaran:</strong><p>{{ $produksi['pemasaran'] ?? '-' }}</p></div>
                                        </div>

                                        @if(isset($produksi['bulan_produksi']) && is_array($produksi['bulan_produksi']) && count($produksi['bulan_produksi']) > 0)
                                            <div class="mb-4">
                                                <strong class="font-medium text-gray-500 block mb-2">Bulan Produksi:</strong>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($produksi['bulan_produksi'] as $bulan)
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ $bulan }}</span>
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

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-4">
                                            <div><strong class="font-medium text-gray-500 block">Biaya Produksi:</strong><p>Rp {{ isset($produksi['biaya_produksi']) ? number_format($produksi['biaya_produksi'], 0, ',', '.') : '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Biaya Lain-lain:</strong><p>Rp {{ isset($produksi['biaya_lain']) ? number_format($produksi['biaya_lain'], 0, ',', '.') : '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Harga Jual/pack:</strong><p>Rp {{ isset($produksi['harga_jual_pack']) ? number_format($produksi['harga_jual_pack'], 0, ',', '.') : '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Jumlah Produk:</strong><p>{{ isset($produksi['jumlah_produk_qty']) ? number_format($produksi['jumlah_produk_qty'], 2) . ' Kg' : '-' }} - {{ $produksi['jumlah_produk_pack'] ?? '-' }} pack</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Hasil Produksi:</strong><p>{{ isset($produksi['harga_produksi_qty']) ? number_format($produksi['harga_produksi_qty'], 2) . ' Kg' : '-' }} - Rp {{ isset($produksi['harga_produksi_harga']) ? number_format($produksi['harga_produksi_harga'], 0, ',', '.') : '-' }}</p></div>
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
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-slate-600">Belum ada data produksi.</p>
                        @endif
                    </div>

                    <!-- Kontainer Tenaga Kerja -->
                    @if($displayData->tenaga_kerja_data && is_array($displayData->tenaga_kerja_data))
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tenaga Kerja</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- WNI -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNI</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->tenaga_kerja_data['wni_laki_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->tenaga_kerja_data['wni_laki_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->tenaga_kerja_data['wni_laki_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->tenaga_kerja_data['wni_perempuan_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->tenaga_kerja_data['wni_perempuan_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->tenaga_kerja_data['wni_perempuan_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNA</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->tenaga_kerja_data['wna_laki_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->tenaga_kerja_data['wna_laki_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->tenaga_kerja_data['wna_laki_keluarga'] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->tenaga_kerja_data['wna_perempuan_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->tenaga_kerja_data['wna_perempuan_tidak_tetap'] ?? 0 }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->tenaga_kerja_data['wna_perempuan_keluarga'] ?? 0 }}</div>
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
                                    @if($displayData->$key)
                                        @php
                                            $extension = pathinfo($displayData->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
                                            // Remove 'storage/' prefix if it exists
                                            $filePath = str_starts_with($displayData->$key, 'storage/') 
                                                ? substr($displayData->$key, 8) 
                                                : $displayData->$key;
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
