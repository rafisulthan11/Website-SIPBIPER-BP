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
                         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div><strong class="font-medium text-gray-500 block">Nama Usaha:</strong><p>{{ $pembudidaya->nama_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">NPWP Usaha:</strong><p>{{ $pembudidaya->npwp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">No. Telepon Usaha:</strong><p>{{ $pembudidaya->telp_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Email Usaha:</strong><p>{{ $pembudidaya->email_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Tahun Mulai Usaha:</strong><p>{{ $pembudidaya->tahun_mulai_usaha ?? '-' }}</p></div>
                            <div><strong class="font-medium text-gray-500 block">Status Usaha:</strong><p>{{ $pembudidaya->status_usaha ?? '-' }}</p></div>
                            <div class="lg:col-span-3"><strong class="font-medium text-gray-500 block">Alamat Usaha:</strong><p>{{ $pembudidaya->alamat_usaha ?? '-' }}</p></div>
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>