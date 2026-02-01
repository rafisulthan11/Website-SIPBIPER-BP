<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Pembudidaya: ') . $pembudidaya->nama_lengkap }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex items-center justify-end mb-6 gap-2">
                         <a href="{{ route('pembudidaya.index') }}" class="inline-block rounded bg-gray-400 px-4 py-2 text-xs font-medium text-white hover:bg-gray-500">
                            Kembali
                        </a>
                        <a href="{{ route('pembudidaya.edit', $pembudidaya->id_pembudidaya) }}" class="inline-block rounded bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600">
                            Edit Data Ini
                        </a>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Jenis Usaha</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Jenis Kegiatan Usaha:</strong><p>{{ $pembudidaya->jenis_kegiatan_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Budidaya:</strong><p>{{ $pembudidaya->jenis_budidaya ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Pemilik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Lengkap:</strong><p>{{ $pembudidaya->nama_lengkap ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NIK:</strong><p>{{ $pembudidaya->nik_pembudidaya ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Jenis Kelamin:</strong><p>{{ $pembudidaya->jenis_kelamin ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tempat Lahir:</strong><p>{{ $pembudidaya->tempat_lahir ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tanggal Lahir:</strong><p>{{ $pembudidaya->tanggal_lahir ? \Carbon\Carbon::parse($pembudidaya->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Perkawinan:</strong><p>{{ $pembudidaya->status_perkawinan ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap:</strong><p>{{ $pembudidaya->alamat ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Kecamatan:</strong><p>{{ $pembudidaya->kecamatan->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa/Kelurahan:</strong><p>{{ $pembudidaya->desa->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon/HP:</strong><p>{{ $pembudidaya->kontak ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email:</strong><p>{{ $pembudidaya->email ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. NPWP:</strong><p>{{ $pembudidaya->no_npwp ?? '-' }}</p></div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Profil Usaha</h3>
                        
                        <!-- Informasi Umum -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Informasi Umum</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm mb-6">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $pembudidaya->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nama Kelompok:</strong><p>{{ $pembudidaya->nama_kelompok ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP Usaha:</strong><p>{{ $pembudidaya->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon Usaha:</strong><p>{{ $pembudidaya->telp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email Usaha:</strong><p>{{ $pembudidaya->email_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pembudidaya->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $pembudidaya->status_usaha ?? '-' }}</p></div>
                        </div>

                        <!-- Lokasi Usaha -->
                        <h4 class="text-base font-semibold text-slate-700 mb-3">Lokasi Usaha</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Kecamatan Usaha:</strong><p>{{ $pembudidaya->kecamatanUsaha->nama_kecamatan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Desa Usaha:</strong><p>{{ $pembudidaya->desaUsaha->nama_desa ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Latitude Usaha:</strong><p>{{ $pembudidaya->latitude_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Longitude Usaha:</strong><p>{{ $pembudidaya->longitude_usaha ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Lengkap Usaha:</strong><p>{{ $pembudidaya->alamat_lengkap_usaha ?? ($pembudidaya->alamat_usaha ?? '-') }}</p></div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Izin Usaha</h3>
                        @php $iz = $pembudidaya->izin; @endphp
                        @if($iz)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">NIB:</strong><p>{{ $iz->nib ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP:</strong><p>{{ $iz->npwp ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">KUSUKA:</strong><p>{{ $iz->kusuka ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pengesahan MENKUMHAM:</strong><p>{{ $iz->pengesahan_menkumham ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">CBIB:</strong><p>{{ $iz->cbib ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SKAI:</strong><p>{{ $iz->skai ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Surat Ijin Pembudidayaan Ikan:</strong><p>{{ $iz->surat_ijin_pembudidayaan_ikan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">AKTA PENDIRIAN USAHA:</strong><p>{{ $iz->akta_pendirian_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">IMB:</strong><p>{{ $iz->imb ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SUP Perikanan:</strong><p>{{ $iz->sup_perikanan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">SUP Perdagangan:</strong><p>{{ $iz->sup_perdagangan ?? '-' }}</p></div>
                        </div>
                        @else
                            <p class="text-slate-600">Belum ada data izin usaha.</p>
                        @endif
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Investasi</h3>
                        @php $inv = $pembudidaya->investasi; @endphp
                        @if($inv)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nilai Asset:</strong><p>Rp. {{ number_format($inv->nilai_asset ?? 0, 0, ',', '.') }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Laba Ditanam:</strong><p>Rp. {{ number_format($inv->laba_ditanam ?? 0, 0, ',', '.') }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sewa:</strong><p>Rp. {{ number_format($inv->sewa ?? 0, 0, ',', '.') }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Pinjaman:</strong><p>{{ is_null($inv->pinjaman) ? '-' : ($inv->pinjaman ? 'Ada' : 'Tidak') }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Modal Sendiri:</strong><p>Rp. {{ number_format($inv->modal_sendiri ?? 0, 0, ',', '.') }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Lahan (Status Kepemilikan):</strong><p>
                                @php 
                                    $ls = $inv->lahan_status ?? []; 
                                    if (is_string($ls)) { $ls = json_decode($ls, true) ?? []; }
                                    if (!is_array($ls)) { $ls = []; }
                                @endphp
                                {{ $ls ? implode(', ', $ls) : '-' }}
                            </p></div>
                            <div><strong class="font-medium text-gray-500 block">Luas:</strong><p>{{ $inv->luas_m2 ? $inv->luas_m2 . ' m2' : '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Nilai Bangunan:</strong><p>Rp. {{ number_format($inv->nilai_bangunan ?? 0, 0, ',', '.') }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Bangunan:</strong><p>{{ $inv->bangunan ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Sertifikat:</strong><p>{{ $inv->sertifikat === 'IMB' ? 'IMB' : ($inv->sertifikat === 'NON_IMB' ? 'Non IMB' : '-') }}</p></div>
                        </div>
                        @else
                            <p class="text-slate-600">Belum ada data investasi.</p>
                        @endif
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Produksi</h3>
                        @php $prod = $pembudidaya->produksi; @endphp
                        
                        @if($prod)
                            <div class="mb-6">
                                <h4 class="font-semibold text-slate-800 mb-3">Total Keseluruhan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                    <div><strong class="font-medium text-gray-500 block">Total Luas Kolam:</strong><p>{{ $prod->total_luas_kolam ?? '-' }} m²</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Total Produksi:</strong><p>{{ $prod->total_produksi ?? '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Satuan:</strong><p>{{ $prod->satuan_produksi ?? '-' }}</p></div>
                                    <div><strong class="font-medium text-gray-500 block">Harga per Satuan:</strong><p>Rp. {{ number_format($prod->harga_per_satuan ?? 0, 0, ',', '.') }}</p></div>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-600 mb-6">Belum ada data produksi.</p>
                        @endif

                        @if($pembudidaya->kolam->count() > 0)
                            <div class="mb-6">
                                <h4 class="font-semibold text-slate-800 mb-3">Data Kolam</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm border border-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left border-b">Jenis Kolam</th>
                                                <th class="px-4 py-2 text-left border-b">Ukuran (m²)</th>
                                                <th class="px-4 py-2 text-left border-b">Jumlah</th>
                                                <th class="px-4 py-2 text-left border-b">Komoditas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pembudidaya->kolam as $kolam)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 border-b">{{ $kolam->jenis_kolam }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $kolam->ukuran ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $kolam->jumlah ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $kolam->komoditas ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($pembudidaya->ikan->count() > 0)
                            <div>
                                <h4 class="font-semibold text-slate-800 mb-3">Data Ikan</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm border border-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left border-b">Jenis Ikan</th>
                                                <th class="px-4 py-2 text-left border-b">Jenis Indukan</th>
                                                <th class="px-4 py-2 text-left border-b">Jumlah</th>
                                                <th class="px-4 py-2 text-left border-b">Asal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pembudidaya->ikan as $ikan)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-2 border-b">{{ $ikan->jenis_ikan }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $ikan->jenis_indukan ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $ikan->jumlah ?? '-' }}</td>
                                                    <td class="px-4 py-2 border-b">{{ $ikan->asal ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-lg font-semibold border-b pb-2 mb-4">Tenaga Kerja</h3>
                        @php $tk = $pembudidaya->tenagaKerja; @endphp
                        
                        @if($tk)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- WNI -->
                                <div>
                                    <h4 class="font-semibold text-slate-800 mb-3">WNI</h4>
                                    <div class="mb-4">
                                        <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div><strong class="text-gray-600">Tetap:</strong> {{ $tk->wni_laki_tetap }}</div>
                                            <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $tk->wni_laki_tidak_tetap }}</div>
                                            <div><strong class="text-gray-600">Keluarga:</strong> {{ $tk->wni_laki_keluarga }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div><strong class="text-gray-600">Tetap:</strong> {{ $tk->wni_perempuan_tetap }}</div>
                                            <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $tk->wni_perempuan_tidak_tetap }}</div>
                                            <div><strong class="text-gray-600">Keluarga:</strong> {{ $tk->wni_perempuan_keluarga }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- WNA -->
                                <div>
                                    <h4 class="font-semibold text-slate-800 mb-3">WNA</h4>
                                    <div class="mb-4">
                                        <h5 class="font-medium text-slate-700 mb-2 text-sm">Laki-laki</h5>
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div><strong class="text-gray-600">Tetap:</strong> {{ $tk->wna_laki_tetap }}</div>
                                            <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $tk->wna_laki_tidak_tetap }}</div>
                                            <div><strong class="text-gray-600">Keluarga:</strong> {{ $tk->wna_laki_keluarga }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-slate-700 mb-2 text-sm">Perempuan</h5>
                                        <div class="grid grid-cols-3 gap-2 text-sm">
                                            <div><strong class="text-gray-600">Tetap:</strong> {{ $tk->wna_perempuan_tetap }}</div>
                                            <div><strong class="text-gray-600">Tidak Tetap:</strong> {{ $tk->wna_perempuan_tidak_tetap }}</div>
                                            <div><strong class="text-gray-600">Keluarga:</strong> {{ $tk->wna_perempuan_keluarga }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-600">Belum ada data tenaga kerja.</p>
                        @endif
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
                                    @if($pembudidaya->$key)
                                        @php
                                            $extension = pathinfo($pembudidaya->$key, PATHINFO_EXTENSION);
                                            $isPdf = strtolower($extension) === 'pdf';
                                            // Remove 'storage/' prefix if it exists
                                            $filePath = str_starts_with($pembudidaya->$key, 'storage/') 
                                                ? substr($pembudidaya->$key, 8) 
                                                : $pembudidaya->$key;
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
                                <p class="text-gray-700">{{ $pembudidaya->created_at ? $pembudidaya->created_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                            <div>
                                <strong class="font-medium text-blue-700 block">Terakhir diubah:</strong>
                                <p class="text-gray-700">{{ $pembudidaya->updated_at ? $pembudidaya->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>