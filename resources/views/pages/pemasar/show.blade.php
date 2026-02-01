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

                    <!-- Jenis Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $pemasar->jenis_kegiatan_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Profil Pemilik -->
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
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pemasar->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Aset Pribadi:</strong><p>{{ $pemasar->aset_pribadi ? 'Rp. ' . number_format($pemasar->aset_pribadi, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Tanggungan:</strong><p>{{ $pemasar->jumlah_tanggungan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $pemasar->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $pemasar->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $pemasar->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $pemasar->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $pemasar->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $pemasar->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Profil Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>
                        
                        <!-- Informasi Umum -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Informasi Umum</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $pemasar->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nama Kelompok:</strong><p>{{ $pemasar->nama_kelompok ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP Usaha:</strong><p>{{ $pemasar->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon Usaha:</strong><p>{{ $pemasar->telp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email Usaha:</strong><p>{{ $pemasar->email_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Skala Usaha:</strong><p>{{ $pemasar->skala_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $pemasar->status_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pemasar->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Komoditas:</strong><p>{{ $pemasar->komoditas ?? '-' }}</p></div>
                        </div>

                        <!-- Lokasi Usaha -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Lokasi Usaha</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $pemasar->kecamatanUsaha->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $pemasar->desaUsaha->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Latitude:</strong><p>{{ $pemasar->latitude ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude:</strong><p>{{ $pemasar->longitude ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap Usaha:</strong><p>{{ $pemasar->alamat_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Izin Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Izin Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">NIB:</strong><p>{{ $pemasar->nib ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP:</strong><p>{{ $pemasar->npwp_izin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">KUSUKA:</strong><p>{{ $pemasar->kusuka ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pengesahan MENKUMHAM:</strong><p>{{ $pemasar->pengesahan_menkumham ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">TDU/PHP:</strong><p>{{ $pemasar->tdu_php ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SPPL:</strong><p>{{ $pemasar->sppl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perdagangan:</strong><p>{{ $pemasar->siup_perdagangan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Akta Pendiri Usaha:</strong><p>{{ $pemasar->akta_pendiri_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">IMB:</strong><p>{{ $pemasar->imb ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perikanan:</strong><p>{{ $pemasar->siup_perikanan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">UKL/UPL:</strong><p>{{ $pemasar->ukl_upl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">AMDAL:</strong><p>{{ $pemasar->amdal ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Investasi -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Investasi</h3>
                        
                        <!-- Mesin/Peralatan -->
                        @if($pemasar->mesin_peralatan)
                            @php
                                $mesinPeralatan = json_decode($pemasar->mesin_peralatan, true);
                            @endphp
                            @if(is_array($mesinPeralatan) && count($mesinPeralatan) > 0)
                                <h4 class="text-base font-semibold text-slate-700 mb-3">Mesin/Peralatan</h4>
                                @foreach($mesinPeralatan as $index => $mesin)
                                    <div class="mb-4 p-3 bg-white rounded border">
                                        <h5 class="font-medium text-slate-700 mb-2">Mesin/Peralatan {{ $index + 1 }}</h5>
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                                            <div><strong class="font-medium text-gray-500 block">Jenis Mesin:</strong><p>{{ $mesin['jenis_mesin'] ?? '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Kapasitas:</strong><p>{{ $mesin['kapasitas'] ?? '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Jumlah:</strong><p>{{ $mesin['jumlah'] ?? '-' }}</p></div>
                                            <div><strong class="font-medium text-gray-500 block">Asal:</strong><p>{{ $mesin['asal'] ?? '-' }}</p></div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif

                        <!-- Nilai Investasi (Modal Tetap / MT) -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3 mt-6">Nilai Investasi (Modal Tetap / MT)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Tanah:</strong><p>{{ $pemasar->investasi_tanah ? 'Rp. ' . number_format($pemasar->investasi_tanah, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Gedung:</strong><p>{{ $pemasar->investasi_gedung ? 'Rp. ' . number_format($pemasar->investasi_gedung, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Mesin/Peralatan:</strong><p>{{ $pemasar->investasi_mesin_peralatan ? 'Rp. ' . number_format($pemasar->investasi_mesin_peralatan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kendaraan:</strong><p>{{ $pemasar->investasi_kendaraan ? 'Rp. ' . number_format($pemasar->investasi_kendaraan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Lain-lain:</strong><p>{{ $pemasar->investasi_lain_lain ? 'Rp. ' . number_format($pemasar->investasi_lain_lain, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sub Jumlah:</strong><p>{{ $pemasar->investasi_sub_jumlah ? 'Rp. ' . number_format($pemasar->investasi_sub_jumlah, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Nilai Investasi (Modal Kerja / MK) -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Nilai Investasi (Modal Kerja / MK)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">1 Bulan:</strong><p>{{ $pemasar->modal_kerja_1_bulan ? 'Rp. ' . number_format($pemasar->modal_kerja_1_bulan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sub Jumlah:</strong><p>{{ $pemasar->modal_kerja_sub_jumlah ? 'Rp. ' . number_format($pemasar->modal_kerja_sub_jumlah, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sumber Pembiayaan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sumber Pembiayaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Modal Sendiri:</strong><p>{{ $pemasar->modal_sendiri ? 'Rp. ' . number_format($pemasar->modal_sendiri, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Laba Ditanam:</strong><p>{{ $pemasar->laba_ditanam ? 'Rp. ' . number_format($pemasar->laba_ditanam, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Modal Pinjaman:</strong><p>{{ $pemasar->modal_pinjam ? 'Rp. ' . number_format($pemasar->modal_pinjam, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sertifikat Lahan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sertifikat Lahan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div>
                                <strong class="font-medium text-gray-500 block">Jenis Sertifikat:</strong>
                                @if($pemasar->sertifikat_lahan)
                                    @php
                                        $sertifikatLahan = json_decode($pemasar->sertifikat_lahan, true);
                                    @endphp
                                    @if(is_array($sertifikatLahan))
                                        <p>{{ implode(', ', $sertifikatLahan) }}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            <div><strong class="font-medium text-gray-500 block">Luas Lahan:</strong><p>{{ $pemasar->luas_lahan ? $pemasar->luas_lahan . ' m2' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nilai:</strong><p>{{ $pemasar->nilai_lahan ? 'Rp. ' . number_format($pemasar->nilai_lahan, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sertifikat Bangunan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sertifikat Bangunan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div>
                                <strong class="font-medium text-gray-500 block">Jenis Sertifikat:</strong>
                                @if($pemasar->sertifikat_bangunan)
                                    @php
                                        $sertifikatBangunan = json_decode($pemasar->sertifikat_bangunan, true);
                                    @endphp
                                    @if(is_array($sertifikatBangunan))
                                        <p>{{ implode(', ', $sertifikatBangunan) }}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            <div><strong class="font-medium text-gray-500 block">Luas Bangunan:</strong><p>{{ $pemasar->luas_bangunan ? $pemasar->luas_bangunan . ' m2' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nilai:</strong><p>{{ $pemasar->nilai_bangunan ? 'Rp. ' . number_format($pemasar->nilai_bangunan, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Produksi -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Kapasitas & Produksi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Kapasitas Terpasang Setahun:</strong><p>{{ $pemasar->kapasitas_terpasang_setahun ? $pemasar->kapasitas_terpasang_setahun . ' Kg' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Hari Produksi/bulan:</strong><p>{{ $pemasar->jumlah_hari_produksi ? $pemasar->jumlah_hari_produksi . ' hari' : '-' }}</p></div>
                            <div>
                                <strong class="font-medium text-gray-500 block">Bulan Produksi:</strong>
                                @if($pemasar->bulan_produksi)
                                    @php
                                        $bulanProduksi = json_decode($pemasar->bulan_produksi, true);
                                    @endphp
                                    @if(is_array($bulanProduksi))
                                        <p>Bulan: {{ implode(', ', $bulanProduksi) }}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            <div><strong class="font-medium text-gray-500 block">Distribusi Pemasaran:</strong><p>{{ $pemasar->distribusi_pemasaran ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Tenaga Kerja -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tenaga Kerja</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- WNI -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNI</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pemasar->wni_laki_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pemasar->wni_laki_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pemasar->wni_laki_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pemasar->wni_perempuan_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pemasar->wni_perempuan_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pemasar->wni_perempuan_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNA</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pemasar->wna_laki_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pemasar->wna_laki_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pemasar->wna_laki_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $pemasar->wna_perempuan_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $pemasar->wna_perempuan_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $pemasar->wna_perempuan_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Lampiran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $lampiran = [
                                    'foto_ktp' => 'Foto KTP',
                                    'foto_sertifikat' => 'Foto Sertifikat',
                                    'foto_cpib_cbib' => 'Foto CPIB/CBIB',
                                    'foto_unit_usaha' => 'Foto Unit Usaha',
                                    'foto_npwp' => 'Foto NPWP',
                                    'foto_izin_usaha' => 'Foto Izin Usaha',
                                    'foto_produk' => 'Foto Produk',
                                    'foto_sertifikat_pirt' => 'Foto Sertifikat PIRT',
                                    'foto_sertifikat_halal' => 'Foto Sertifikat Halal',
                                ];
                            @endphp
                            @foreach($lampiran as $key => $label)
                                <div class="border rounded-lg p-3 bg-white">
                                    <strong class="font-medium text-gray-700 block mb-2 text-sm">{{ $label }}</strong>
                                    @if($pemasar->$key)
                                        @php
                                            $extension = pathinfo($pemasar->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
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

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <strong class="font-medium text-blue-700 block">Dibuat pada:</strong>
                                <p class="text-gray-700">{{ $pemasar->created_at ? $pemasar->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-blue-700 block">Terakhir diubah:</strong>
                                <p class="text-gray-700">{{ $pemasar->updated_at ? $pemasar->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

