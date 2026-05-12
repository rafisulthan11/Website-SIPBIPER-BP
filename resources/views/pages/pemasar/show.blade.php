<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Detail Data Pemasar: ") . (isset($backupData) && $backupData ? $backupData->nama_lengkap : $pemasar->nama_lengkap) }}
        </h2>
    </x-slot>

    @php
        $displayData = (isset($backupData) && $backupData) ? $backupData : $pemasar;
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
                            <a href="{{ route('pemasar.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                                Kembali
                            </a>
                            @if(!$isReportView)
                            @if(auth()->user()->role->nama_role === 'staff')
                            <a href="{{ route('pemasar.edit', $displayData->id_pemasar) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                                Edit Data Ini
                            </a>
                            @endif
                            @if(auth()->user()->role->nama_role === 'admin' && $pemasar->status === 'pending')
                            <form action="{{ route('pemasar.verify', $pemasar->id_pemasar) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-block rounded bg-blue-600 px-4 py-2 text-xs font-medium text-white hover:bg-blue-700">
                                    Verifikasi
                                </button>
                            </form>
                            <form action="{{ route('pemasar.reject', $pemasar->id_pemasar) }}" method="POST" class="inline form-reject-catatan" data-entity="data pemasar ini">
                                @csrf
                                <input type="hidden" name="catatan_perbaikan" value="">
                                <button type="submit" class="inline-block rounded bg-orange-600 px-4 py-2 text-xs font-medium text-white hover:bg-orange-700">
                                    Tolak
                                </button>
                            </form>
                            @endif
                            @if(auth()->user()->isAdminOrSuperAdmin())
                            <form action="{{ route('pemasar.destroy', $pemasar->id_pemasar) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
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

                    <!-- Jenis Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $displayData->jenis_kegiatan_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Profil Pemilik -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pemilik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Lengkap:</strong><p>{{ $displayData->nama_lengkap ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NIK:</strong><p>{{ $displayData->nik_pemasar ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Kelamin:</strong><p>{{ $displayData->jenis_kelamin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tempat Lahir:</strong><p>{{ $displayData->tempat_lahir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tanggal Lahir:</strong><p>{{ $displayData->tanggal_lahir ? \Carbon\Carbon::parse($displayData->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pendidikan Terakhir:</strong><p>{{ $displayData->pendidikan_terakhir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Perkawinan:</strong><p>{{ $displayData->status_perkawinan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $displayData->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Aset Pribadi:</strong><p>{{ $displayData->aset_pribadi ? 'Rp. ' . number_format($displayData->aset_pribadi, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jumlah Tanggungan:</strong><p>{{ $displayData->jumlah_tanggungan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $displayData->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $displayData->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $displayData->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $displayData->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $displayData->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $displayData->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Profil Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>
                        
                        <!-- Informasi Umum -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Informasi Umum</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $displayData->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nama Kelompok:</strong><p>{{ $displayData->nama_kelompok ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP Usaha:</strong><p>{{ $displayData->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon Usaha:</strong><p>{{ $displayData->telp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email Usaha:</strong><p>{{ $displayData->email_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Skala Usaha:</strong><p>{{ $displayData->skala_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $displayData->status_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $displayData->tahun_mulai_usaha ?? '-' }}</p></div>
                        </div>

                        <!-- Lokasi Usaha -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Lokasi Usaha</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $displayData->kecamatanUsaha->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $displayData->desaUsaha->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Latitude:</strong><p>{{ $displayData->latitude ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude:</strong><p>{{ $displayData->longitude ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap Usaha:</strong><p>{{ $displayData->alamat_usaha ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Izin Usaha -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Izin Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">NIB:</strong><p>{{ $displayData->nib ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP:</strong><p>{{ $displayData->npwp_izin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">KUSUKA:</strong><p>{{ $displayData->kusuka ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pengesahan MENKUMHAM:</strong><p>{{ $displayData->pengesahan_menkumham ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">TDU/PHP:</strong><p>{{ $displayData->tdu_php ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SPPL:</strong><p>{{ $displayData->sppl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perdagangan:</strong><p>{{ $displayData->siup_perdagangan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Akta Pendiri Usaha:</strong><p>{{ $displayData->akta_pendiri_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">IMB:</strong><p>{{ $displayData->imb ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SIUP Perikanan:</strong><p>{{ $displayData->siup_perikanan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">UKL/UPL:</strong><p>{{ $displayData->ukl_upl ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">AMDAL:</strong><p>{{ $displayData->amdal ?? '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Investasi -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Investasi</h3>
                        
                        <!-- Mesin/Peralatan -->
                        @if($displayData->mesin_peralatan)
                            @php
                                $mesinPeralatan = json_decode($displayData->mesin_peralatan, true);
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
                            <div><strong class="font-medium text-gray-500 block">Tanah:</strong><p>{{ $displayData->investasi_tanah ? 'Rp. ' . number_format($displayData->investasi_tanah, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Gedung:</strong><p>{{ $displayData->investasi_gedung ? 'Rp. ' . number_format($displayData->investasi_gedung, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Mesin/Peralatan:</strong><p>{{ $displayData->investasi_mesin_peralatan ? 'Rp. ' . number_format($displayData->investasi_mesin_peralatan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kendaraan:</strong><p>{{ $displayData->investasi_kendaraan ? 'Rp. ' . number_format($displayData->investasi_kendaraan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Lain-lain:</strong><p>{{ $displayData->investasi_lain_lain ? 'Rp. ' . number_format($displayData->investasi_lain_lain, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sub Jumlah:</strong><p>{{ $displayData->investasi_sub_jumlah ? 'Rp. ' . number_format($displayData->investasi_sub_jumlah, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Nilai Investasi (Modal Kerja / MK) -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Nilai Investasi (Modal Kerja / MK)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">1 Bulan:</strong><p>{{ $displayData->modal_kerja_1_bulan ? 'Rp. ' . number_format($displayData->modal_kerja_1_bulan, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sub Jumlah:</strong><p>{{ $displayData->modal_kerja_sub_jumlah ? 'Rp. ' . number_format($displayData->modal_kerja_sub_jumlah, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sumber Pembiayaan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sumber Pembiayaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Modal Sendiri:</strong><p>{{ $displayData->modal_sendiri ? 'Rp. ' . number_format($displayData->modal_sendiri, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Laba Ditanam:</strong><p>{{ $displayData->laba_ditanam ? 'Rp. ' . number_format($displayData->laba_ditanam, 2, ',', '.') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Modal Pinjaman:</strong><p>{{ $displayData->modal_pinjam ? 'Rp. ' . number_format($displayData->modal_pinjam, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sertifikat Lahan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sertifikat Lahan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div>
                                <strong class="font-medium text-gray-500 block">Jenis Sertifikat:</strong>
                                @if($displayData->sertifikat_lahan)
                                    @php
                                        $sertifikatLahan = json_decode($displayData->sertifikat_lahan, true);
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
                            <div><strong class="font-medium text-gray-500 block">Luas Lahan:</strong><p>{{ $displayData->luas_lahan ? $displayData->luas_lahan . ' m2' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nilai:</strong><p>{{ $displayData->nilai_lahan ? 'Rp. ' . number_format($displayData->nilai_lahan, 2, ',', '.') : '-' }}</p></div>
                        </div>

                        <!-- Sertifikat Bangunan -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Sertifikat Bangunan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div>
                                <strong class="font-medium text-gray-500 block">Jenis Sertifikat:</strong>
                                @if($displayData->sertifikat_bangunan)
                                    @php
                                        $sertifikatBangunan = json_decode($displayData->sertifikat_bangunan, true);
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
                            <div><strong class="font-medium text-gray-500 block">Luas Bangunan:</strong><p>{{ $displayData->luas_bangunan ? $displayData->luas_bangunan . ' m2' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nilai:</strong><p>{{ $displayData->nilai_bangunan ? 'Rp. ' . number_format($displayData->nilai_bangunan, 2, ',', '.') : '-' }}</p></div>
                        </div>
                    </div>

                    <!-- Pemasaran -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Pemasaran</h3>
                        <!-- PEMASARAN Table -->
                        @php
                            $sourceData = $backupData ?? $displayData ?? $pemasar;
                            $dataPemasaran = collect();
                            if (method_exists($sourceData, 'relationLoaded') && $sourceData->relationLoaded('pemasaran')) {
                                $dataPemasaran = $sourceData->pemasaran;
                            }

                            $pemasaranSections = $dataPemasaran->groupBy(function ($row) {
                                return $row->section_index ?? 0;
                            });
                        @endphp
                        @if($pemasaranSections->count() > 0)
                            @foreach($pemasaranSections as $sectionIndex => $rows)
                                @php
                                    $firstRow = $rows->first();
                                    $bulanPemasaran = $firstRow->bulan_produksi ? json_decode($firstRow->bulan_produksi, true) : [];
                                @endphp
                                <div class="mb-6 rounded-lg border border-blue-200 bg-white p-4">
                                    <div class="mb-4 flex items-center justify-between">
                                        <h4 class="text-base font-semibold text-blue-700">Data Pemasaran #{{ (int) $sectionIndex + 1 }}</h4>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 mb-4 text-sm">
                                        <div><strong class="text-gray-500 block">Kapasitas Terpasang:</strong><p>{{ $firstRow->kapasitas_terpasang ? number_format($firstRow->kapasitas_terpasang, 2, ',', '.') . ' Kg' : '-' }}</p></div>
                                        <div><strong class="text-gray-500 block">Hasil Pemasaran:</strong><p>{{ $firstRow->hasil_produksi_kg ? number_format($firstRow->hasil_produksi_kg, 2, ',', '.') . ' Kg' : '-' }}{{ $firstRow->hasil_produksi_rp ? ' | Rp. ' . number_format($firstRow->hasil_produksi_rp, 2, ',', '.') : '' }}</p></div>
                                        <div class="md:col-span-2">
                                            <strong class="text-gray-500 block">Bulan Pemasaran:</strong>
                                            @if(is_array($bulanPemasaran) && count($bulanPemasaran) > 0)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach($bulanPemasaran as $bulan)
                                                        <span class="inline-flex items-center rounded-full border border-blue-200 bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">{{ $bulan }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p>-</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="overflow-x-auto mb-4">
                                        <table class="w-full border-collapse border border-gray-300 text-sm">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border border-gray-300 px-3 py-2 text-left">Komoditas Ikan</th>
                                                    <th class="border border-gray-300 px-3 py-2 text-left">Asal Ikan</th>
                                                    <th class="border border-gray-300 px-3 py-2 text-left">Jumlah / Volume Ikan</th>
                                                    <th class="border border-gray-300 px-3 py-2 text-left">Harga Beli /kg</th>
                                                    <th class="border border-gray-300 px-3 py-2 text-left">Harga Jual/kg</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rows as $row)
                                                    <tr>
                                                        <td class="border border-gray-300 px-3 py-2">{{ $row->komoditas ?? '-' }}</td>
                                                        <td class="border border-gray-300 px-3 py-2">{{ $row->asal_ikan ?? '-' }}</td>
                                                        <td class="border border-gray-300 px-3 py-2 text-right">{{ $row->jumlah_volume !== null ? number_format($row->jumlah_volume, 2, ',', '.') : '-' }}</td>
                                                        <td class="border border-gray-300 px-3 py-2 text-right">{{ $row->harga_beli !== null ? 'Rp. ' . number_format($row->harga_beli, 2, ',', '.') : '-' }}</td>
                                                        <td class="border border-gray-300 px-3 py-2 text-right">{{ $row->harga_jual !== null ? 'Rp. ' . number_format($row->harga_jual, 2, ',', '.') : '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-sm">
                                        <strong class="font-medium text-gray-500 block mb-2">Distribusi / Pemasaran:</strong>
                                        <p class="whitespace-pre-wrap">{{ $firstRow->distribusi_pemasaran ?? '-' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->wni_laki_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->wni_laki_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->wni_laki_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->wni_perempuan_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->wni_perempuan_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->wni_perempuan_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA -->
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">WNA</h4>
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->wna_laki_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->wna_laki_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->wna_laki_keluarga ?? '0' }}</div>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                    <div class="grid grid-cols-3 gap-2 text-sm">
                                        <div><strong class="text-gray-600">Tetap:</strong> {{ $displayData->wna_perempuan_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $displayData->wna_perempuan_tidak_tetap ?? '0' }}</div>
                                        <div><strong class="text-gray-600">Keluarga:</strong> {{ $displayData->wna_perempuan_keluarga ?? '0' }}</div>
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
                                    @if($displayData->$key)
                                        @php
                                            $extension = pathinfo($displayData->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
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

