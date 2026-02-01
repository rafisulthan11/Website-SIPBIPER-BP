<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Pembudidaya: ') . $pembudidaya->nama_lengkap }}
        </h2>
    </x-slot>

    @php
        $stepMap = [
            'jenis_kegiatan_usaha' => 0,
            'jenis_budidaya' => 0,
            'nama_lengkap' => 1,
            'nik_pembudidaya' => 1,
            'id_kecamatan' => 1,
            'id_desa' => 1,
        ];
        $initialStep = 0;
        if ($errors->any()) {
            foreach ($errors->keys() as $key) {
                if (array_key_exists($key, $stepMap)) { $initialStep = $stepMap[$key]; break; }
            }
        }
    @endphp

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div x-data="{ step: {{ $initialStep }}, maxStep: 7 }" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-bold">Data Pembudidaya</h2>
                </div>
                
                <!-- Edit Title -->
                <div class="px-6 pt-4 pb-2">
                    <h3 class="text-lg font-semibold text-slate-800">Edit Pembudidaya</h3>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-3">
                    <div class="flex flex-wrap gap-2">
                        @php $tabs = ['Jenis Usaha','Profil Pemilik','Izin Usaha','Profil Usaha','Investasi','Produksi','Tenaga Kerja','Lampiran']; @endphp
                        @foreach($tabs as $i => $tab)
                            <button type="button" @click="step={{ $i }}" :class="step==={{ $i }} ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-gray-300'" class="px-4 py-2 rounded text-sm font-medium hover:bg-blue-50 transition">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('pembudidaya.update', $pembudidaya->id_pembudidaya) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Step panels -->
                    <div class="px-6 pb-6">
                        <!-- Step 0: Jenis Usaha -->
                        <div x-show="step===0" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Jenis Usaha</h3>
                            
                            <div class="space-y-6">
                                <!-- Jenis Kegiatan Usaha -->
                                <div>
                                    <x-input-label for="jenis_kegiatan_usaha" :value="__('Jenis Kegiatan Usaha')" />
                                    <select name="jenis_kegiatan_usaha" id="jenis_kegiatan_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Kegiatan Usaha</option>
                                        <option value="Pembenihan" {{ (old('jenis_kegiatan_usaha', $pembudidaya->jenis_kegiatan_usaha) == 'Pembenihan') ? 'selected' : '' }}>Pembenihan</option>
                                        <option value="Pembenihan/Pembenih" {{ (old('jenis_kegiatan_usaha', $pembudidaya->jenis_kegiatan_usaha) == 'Pembenihan/Pembenih') ? 'selected' : '' }}>Pembenihan/Pembenih</option>
                                        <option value="Pembesaran" {{ (old('jenis_kegiatan_usaha', $pembudidaya->jenis_kegiatan_usaha) == 'Pembesaran') ? 'selected' : '' }}>Pembesaran</option>
                                        <option value="Tambak" {{ (old('jenis_kegiatan_usaha', $pembudidaya->jenis_kegiatan_usaha) == 'Tambak') ? 'selected' : '' }}>Tambak</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kegiatan_usaha')" class="mt-2" />
                                </div>

                                <!-- Jenis Budidaya -->
                                <div>
                                    <x-input-label for="jenis_budidaya" :value="__('Jenis Budidaya')" />
                                    <select name="jenis_budidaya" id="jenis_budidaya" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Budidaya</option>
                                        <option value="Kolam" {{ (old('jenis_budidaya', $pembudidaya->jenis_budidaya) == 'Kolam') ? 'selected' : '' }}>Kolam</option>
                                        <option value="Mina Padi" {{ (old('jenis_budidaya', $pembudidaya->jenis_budidaya) == 'Mina Padi') ? 'selected' : '' }}>Mina Padi</option>
                                        <option value="Keramba" {{ (old('jenis_budidaya', $pembudidaya->jenis_budidaya) == 'Keramba') ? 'selected' : '' }}>Keramba</option>
                                        <option value="Tambak" {{ (old('jenis_budidaya', $pembudidaya->jenis_budidaya) == 'Tambak') ? 'selected' : '' }}>Tambak</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_budidaya')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Profil Pemilik -->
                        <div x-show="step===1" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Profil Pemilik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (Sesuai KTP)*')" />
                                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $pembudidaya->nama_lengkap)" required />
                                </div>
                                <div>
                                    <x-input-label for="nik_pembudidaya" :value="__('NIK (Sesuai KTP)*')" />
                                    <x-text-input id="nik_pembudidaya" class="block mt-1 w-full" type="text" name="nik_pembudidaya" :value="old('nik_pembudidaya', $pembudidaya->nik_pembudidaya)" required />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $pembudidaya->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $pembudidaya->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $pembudidaya->tempat_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $pembudidaya->tanggal_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select name="status_perkawinan" id="status_perkawinan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $opt)
                                            <option value="{{ $opt }}" {{ old('status_perkawinan', $pembudidaya->status_perkawinan)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap (Sesuai KTP)')" />
                                    <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat', $pembudidaya->alamat) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $pembudidaya->id_kecamatan)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="id_desa" :value="__('Desa/Kelurahan*')" />
                                    <select name="id_desa" id="id_desa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Loading...</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="kontak" :value="__('No. Telepon / HP')" />
                                    <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak" :value="old('kontak', $pembudidaya->kontak)" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $pembudidaya->email)" />
                                </div>
                                <div>
                                    <x-input-label for="no_npwp" :value="__('No. NPWP')" />
                                    <x-text-input id="no_npwp" class="block mt-1 w-full" type="text" name="no_npwp" :value="old('no_npwp', $pembudidaya->no_npwp)" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Izin Usaha -->
                        <!-- Step 2: Izin Usaha -->
                        <div x-show="step===2" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Izin Usaha</h3>
                            @php $iz = $pembudidaya->izin; @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $izinFields = [
                                        'nib' => 'NIB',
                                        'npwp' => 'NPWP',
                                        'kusuka' => 'KUSUKA',
                                        'pengesahan_menkumham' => 'Pengesahan MENKUMHAM',
                                        'cbib' => 'CBIB',
                                        'skai' => 'SKAI (Surat Keterangan Asal Ikan)',
                                        'surat_ijin_pembudidayaan_ikan' => 'Surat Ijin Pembudidayaan Ikan',
                                        'akta_pendirian_usaha' => 'AKTA PENDIRIAN USAHA',
                                        'imb' => 'IMB',
                                        'sup_perikanan' => 'SUP Perikanan',
                                        'sup_perdagangan' => 'SUP Perdagangan',
                                    ];
                                @endphp
                                @foreach($izinFields as $name => $label)
                                    <div class="md:col-span-2">
                                        <x-input-label :for="'izin_' . $name" :value="$label" />
                                        <x-text-input :id="'izin_' . $name" class="block mt-1 w-full" type="text" :name="'izin['.$name.']'" :value="old('izin.'.$name, optional($iz)->{$name})" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Profil Usaha -->
                        <div x-show="step===3" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Profil Usaha</h3>
                            
                            <!-- Informasi Umum Section -->
                            <div class="mb-6">
                                <h4 class="text-base font-semibold text-slate-700 mb-4">Informasi Umum</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div>
                                        <x-input-label for="nama_usaha" :value="__('Nama Usaha')" />
                                        <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha', $pembudidaya->nama_usaha)" />
                                    </div>
                                    <div>
                                        <x-input-label for="nama_kelompok" :value="__('Nama Kelompok (opsional)')" />
                                        <x-text-input id="nama_kelompok" class="block mt-1 w-full" type="text" name="nama_kelompok" :value="old('nama_kelompok', $pembudidaya->nama_kelompok)" />
                                    </div>
                                    <div>
                                        <x-input-label for="npwp_usaha" :value="__('NPWP Usaha')" />
                                        <x-text-input id="npwp_usaha" class="block mt-1 w-full" type="text" name="npwp_usaha" :value="old('npwp_usaha', $pembudidaya->npwp_usaha)" />
                                    </div>
                                    <div>
                                        <x-input-label for="telp_usaha" :value="__('No. Telepon Usaha')" />
                                        <x-text-input id="telp_usaha" class="block mt-1 w-full" type="text" name="telp_usaha" :value="old('telp_usaha', $pembudidaya->telp_usaha)" />
                                    </div>
                                    <div>
                                        <x-input-label for="email_usaha" :value="__('Email Usaha')" />
                                        <x-text-input id="email_usaha" class="block mt-1 w-full" type="email" name="email_usaha" :value="old('email_usaha', $pembudidaya->email_usaha)" />
                                    </div>
                                    <div>
                                        <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                        <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pembudidaya->tahun_mulai_usaha)" placeholder="Contoh: 2020" />
                                    </div>
                                    <div>
                                        <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                        <select id="status_usaha" name="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="Aktif" {{ old('status_usaha', $pembudidaya->status_usaha)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Tidak Aktif" {{ old('status_usaha', $pembudidaya->status_usaha)=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi Usaha Section -->
                            <div class="mb-6">
                                <h4 class="text-base font-semibold text-slate-700 mb-4">Lokasi Usaha</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <x-input-label for="kecamatan_usaha" :value="__('Kecamatan Usaha*')" />
                                        <select name="kecamatan_usaha" id="kecamatan_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Kecamatan</option>
                                            @foreach ($kecamatans as $kecamatan)
                                                <option value="{{ $kecamatan->id_kecamatan }}" {{ old('kecamatan_usaha', $pembudidaya->kecamatan_usaha)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="desa_usaha" :value="__('Desa Usaha*')" />
                                        <select name="desa_usaha" id="desa_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Loading...</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-input-label for="alamat_lengkap_usaha" :value="__('Alamat Lengkap Usaha')" />
                                        <textarea id="alamat_lengkap_usaha" name="alamat_lengkap_usaha" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat_lengkap_usaha', $pembudidaya->alamat_lengkap_usaha) }}</textarea>
                                    </div>
                                </div>

                                <!-- Peta Lokasi Usaha -->
                                <div class="mb-4">
                                    <x-input-label :value="__('Peta Lokasi Usaha')" class="mb-2" />
                                    <p class="text-sm text-slate-600 mb-3">Klik pada peta untuk menandai lokasi usaha Anda atau izinkan akses lokasi browser agar tidak otomatis mengikuti posisi Anda.</p>
                                    
                                    <!-- Tombol Gunakan Lokasi Saya -->
                                    <button type="button" id="btnLokasiSayaEdit" class="mb-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">
                                        Gunakan Lokasi Saya
                                    </button>

                                    <!-- Peta Interaktif -->
                                    <div id="mapUsahaEdit" class="w-full h-64 bg-gray-200 rounded-md border border-gray-300 mb-3 relative overflow-hidden z-0"></div>

                                    <!-- Input Latitude & Longitude -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="latitude_usaha" :value="__('Latitude')" />
                                            <x-text-input id="latitude_usaha" class="block mt-1 w-full" type="text" name="latitude_usaha" :value="old('latitude_usaha', $pembudidaya->latitude_usaha)" placeholder="-8.188723" />
                                        </div>
                                        <div>
                                            <x-input-label for="longitude_usaha" :value="__('Longitude')" />
                                            <x-text-input id="longitude_usaha" class="block mt-1 w-full" type="text" name="longitude_usaha" :value="old('longitude_usaha', $pembudidaya->longitude_usaha)" placeholder="113.702568" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Investasi -->
                        <div x-show="step===4" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Investasi</h3>
                            @php
                                $inv = $pembudidaya->investasi;
                                $lahanChecked = [];
                                if ($inv && $inv->lahan_status) {
                                    if (is_string($inv->lahan_status)) {
                                        $decoded = json_decode($inv->lahan_status, true);
                                        if (is_array($decoded)) { $lahanChecked = $decoded; }
                                    } elseif (is_array($inv->lahan_status)) {
                                        $lahanChecked = $inv->lahan_status;
                                    }
                                }
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="investasi_nilai_asset" :value="__('Nilai Asset (Rp)')" />
                                    <x-text-input id="investasi_nilai_asset" class="block mt-1 w-full" type="number" step="0.01" name="investasi[nilai_asset]" :value="old('investasi.nilai_asset', optional($inv)->nilai_asset)" />
                                </div>
                                <div>
                                    <x-input-label for="investasi_laba_ditanam" :value="__('Laba Ditanam (Rp)')" />
                                    <x-text-input id="investasi_laba_ditanam" class="block mt-1 w-full" type="number" step="0.01" name="investasi[laba_ditanam]" :value="old('investasi.laba_ditanam', optional($inv)->laba_ditanam)" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_sewa" :value="__('Sewa (Rp)')" />
                                    <x-text-input id="investasi_sewa" class="block mt-1 w-full" type="number" step="0.01" name="investasi[sewa]" :value="old('investasi.sewa', optional($inv)->sewa)" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Pinjaman')" />
                                    <div class="flex items-center gap-6 mt-2">
                                        @php $pin = old('investasi.pinjaman', is_null(optional($inv)->pinjaman) ? '' : (optional($inv)->pinjaman ? '1' : '0')); @endphp
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[pinjaman]" value="1" {{ $pin==='1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Ada</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[pinjaman]" value="0" {{ $pin==='0' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_modal_sendiri" :value="__('Modal Sendiri (Rp)')" />
                                    <x-text-input id="investasi_modal_sendiri" class="block mt-1 w-full" type="number" step="0.01" name="investasi[modal_sendiri]" :value="old('investasi.modal_sendiri', optional($inv)->modal_sendiri)" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Lahan (Status Kepemilikan)')" />
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-2">
                                        @php $ops = ['LHSM','SHRS','SHGB','Girik/Petok']; @endphp
                                        @foreach($ops as $opsi)
                                            @php $checked = in_array($opsi, old('investasi.lahan_status', $lahanChecked)); @endphp
                                            <label class="inline-flex items-center gap-2">
                                                <input type="checkbox" name="investasi[lahan_status][]" value="{{ $opsi }}" {{ $checked ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span>{{ $opsi }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="investasi_luas_m2" :value="__('Luas (m2)')" />
                                    <x-text-input id="investasi_luas_m2" class="block mt-1 w-full" type="number" step="0.01" name="investasi[luas_m2]" :value="old('investasi.luas_m2', optional($inv)->luas_m2)" />
                                </div>
                                <div>
                                    <x-input-label for="investasi_nilai_bangunan" :value="__('Nilai Bangunan (Rp)')" />
                                    <x-text-input id="investasi_nilai_bangunan" class="block mt-1 w-full" type="number" step="0.01" name="investasi[nilai_bangunan]" :value="old('investasi.nilai_bangunan', optional($inv)->nilai_bangunan)" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_bangunan" :value="__('Bangunan (Keterangan)')" />
                                    <x-text-input id="investasi_bangunan" class="block mt-1 w-full" type="text" name="investasi[bangunan]" :value="old('investasi.bangunan', optional($inv)->bangunan)" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Sertifikat')" />
                                    @php $sert = old('investasi.sertifikat', optional($inv)->sertifikat); @endphp
                                    <div class="flex items-center gap-6 mt-2">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[sertifikat]" value="IMB" {{ $sert==='IMB' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>IMB</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[sertifikat]" value="NON_IMB" {{ $sert==='NON_IMB' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Non IMB</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Produksi -->
                        <div x-show="step===5" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6" x-data="{ 
                            kolams: {{ json_encode($pembudidaya->kolam->map(fn($k) => ['id' => $k->id_kolam, 'jenis' => $k->jenis_kolam, 'ukuran' => $k->ukuran, 'jumlah' => $k->jumlah, 'komoditas' => $k->komoditas])) }}, 
                            ikans: {{ json_encode($pembudidaya->ikan->map(fn($i) => ['id' => $i->id_ikan, 'jenis' => $i->jenis_ikan, 'jenis_indukan' => $i->jenis_indukan, 'jumlah' => $i->jumlah, 'asal' => $i->asal])) }} 
                        }">
                            <h3 class="text-lg font-semibold mb-4">Produksi</h3>
                            @php $prod = $pembudidaya->produksi; @endphp
                            
                            <!-- Total Keseluruhan Section -->
                            <div class="mb-6 bg-white rounded-lg p-4 border border-gray-300">
                                <h4 class="text-base font-semibold text-slate-700 mb-4">Total Keseluruhan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="total_luas_kolam" :value="__('Total Luas Kolam (m²)')" />
                                        <x-text-input id="total_luas_kolam" class="block mt-1 w-full" type="number" step="0.01" name="produksi[total_luas_kolam]" :value="old('produksi.total_luas_kolam', $prod->total_luas_kolam ?? '0.00')" placeholder="0.00" />
                                    </div>
                                    <div>
                                        <x-input-label for="total_produksi" :value="__('Total Produksi')" />
                                        <x-text-input id="total_produksi" class="block mt-1 w-full" type="number" step="0.01" name="produksi[total_produksi]" :value="old('produksi.total_produksi', $prod->total_produksi ?? '0.00')" placeholder="0.00" />
                                    </div>
                                    <div>
                                        <x-input-label for="satuan_produksi" :value="__('Satuan Produksi')" />
                                        <select id="satuan_produksi" name="produksi[satuan_produksi]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Satuan</option>
                                            @foreach(['Kg', 'Ton', 'Ekor'] as $satuan)
                                                <option value="{{ $satuan }}" {{ old('produksi.satuan_produksi', $prod->satuan_produksi ?? '')==$satuan ? 'selected' : '' }}>{{ $satuan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="harga_per_satuan" :value="__('Harga per Satuan')" />
                                        <x-text-input id="harga_per_satuan" class="block mt-1 w-full" type="number" step="0.01" name="produksi[harga_per_satuan]" :value="old('produksi.harga_per_satuan', $prod->harga_per_satuan ?? '0.00')" placeholder="0.00" />
                                    </div>
                                </div>
                            </div>

                            <!-- Data Kolam Section -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-base font-semibold text-slate-700">Data Kolam</h4>
                                    <button type="button" @click="kolams.push({ id: Date.now(), jenis: '', ukuran: '', jumlah: '', komoditas: '' })" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition">
                                        Tambah Kolam
                                    </button>
                                </div>

                                <template x-for="(kolam, index) in kolams" :key="kolam.id">
                                    <div class="bg-white rounded-lg border border-gray-300 p-4 mb-3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <x-input-label :value="__('Jenis Kolam')" />
                                                <input type="text" :name="'kolam['+index+'][jenis_kolam]'" x-model="kolam.jenis" placeholder="Misal: Terpal, Beton" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Ukuran (m²)')" />
                                                <input type="number" step="0.01" :name="'kolam['+index+'][ukuran_m2]'" x-model="kolam.ukuran" placeholder="0.00" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Jumlah')" />
                                                <input type="number" :name="'kolam['+index+'][jumlah]'" x-model="kolam.jumlah" placeholder="0" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Komoditas')" />
                                                <select :name="'kolam['+index+'][komoditas]'" x-model="kolam.komoditas" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                    <option value="">Pilih Komoditas</option>
                                                    @foreach($komoditas as $k)
                                                        <option value="{{ $k->nama_komoditas }}">{{ $k->nama_komoditas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-3 text-right">
                                            <button type="button" @click="kolams.splice(index, 1)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="kolams.length === 0" class="text-center py-8 text-slate-500 bg-white rounded-lg border border-dashed border-gray-300">
                                    <p class="text-sm">Belum ada data kolam. Klik tombol "Tambah Kolam" untuk menambahkan.</p>
                                </div>
                            </div>

                            <!-- Data Ikan Section -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-base font-semibold text-slate-700">Data Ikan</h4>
                                    <button type="button" @click="ikans.push({ id: Date.now(), jenis: '', jenis_indukan: '', jumlah: '', asal: '' })" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition">
                                        Tambah Ikan
                                    </button>
                                </div>

                                <template x-for="(ikan, index) in ikans" :key="ikan.id">
                                    <div class="bg-white rounded-lg border border-gray-300 p-4 mb-3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div>
                                                <x-input-label :value="__('Jenis Ikan *')" />
                                                <select :name="'ikan['+index+'][jenis_ikan]'" x-model="ikan.jenis" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                    <option value="">Pilih Jenis Ikan</option>
                                                    @foreach($komoditas as $k)
                                                        <option value="{{ $k->nama_komoditas }}">{{ $k->nama_komoditas }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Jenis Indukan')" />
                                                <input type="text" :name="'ikan['+index+'][jenis_indukan]'" x-model="ikan.jenis_indukan" placeholder="Misal: Lokal, Unggul" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Jumlah')" />
                                                <input type="number" :name="'ikan['+index+'][jumlah]'" x-model="ikan.jumlah" placeholder="0" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Asal Indukan')" />
                                                <input type="text" :name="'ikan['+index+'][asal]'" x-model="ikan.asal" placeholder="Misal: BBPBAT, Lokal" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                            </div>
                                        </div>
                                        <div class="mt-3 text-right">
                                            <button type="button" @click="ikans.splice(index, 1)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="ikans.length === 0" class="text-center py-8 text-slate-500 bg-white rounded-lg border border-dashed border-gray-300">
                                    <p class="text-sm">Belum ada data ikan. Klik tombol "Tambah Ikan" untuk menambahkan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6: Tenaga Kerja -->
                        <div x-show="step===6" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Tenaga Kerja</h3>
                            @php $tk = $pembudidaya->tenagaKerja; @endphp
                            
                            <!-- WNI Section -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">WNI</h4>
                                
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-3">Laki-laki</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wni_laki_tetap" :value="__('Tetap :')" />
                                            <input id="wni_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_laki_tetap]" value="{{ old('tenaga_kerja.wni_laki_tetap', $tk->wni_laki_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_laki_tidak_tetap]" value="{{ old('tenaga_kerja.wni_laki_tidak_tetap', $tk->wni_laki_tidak_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_laki_keluarga]" value="{{ old('tenaga_kerja.wni_laki_keluarga', $tk->wni_laki_keluarga ?? 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wni_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wni_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_perempuan_tetap]" value="{{ old('tenaga_kerja.wni_perempuan_tetap', $tk->wni_perempuan_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_perempuan_tidak_tetap]" value="{{ old('tenaga_kerja.wni_perempuan_tidak_tetap', $tk->wni_perempuan_tidak_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wni_perempuan_keluarga]" value="{{ old('tenaga_kerja.wni_perempuan_keluarga', $tk->wni_perempuan_keluarga ?? 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA Section -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">WNA</h4>
                                
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-3">Laki-laki</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wna_laki_tetap" :value="__('Tetap :')" />
                                            <input id="wna_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_laki_tetap]" value="{{ old('tenaga_kerja.wna_laki_tetap', $tk->wna_laki_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_laki_tidak_tetap]" value="{{ old('tenaga_kerja.wna_laki_tidak_tetap', $tk->wna_laki_tidak_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_laki_keluarga]" value="{{ old('tenaga_kerja.wna_laki_keluarga', $tk->wna_laki_keluarga ?? 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wna_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wna_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_perempuan_tetap]" value="{{ old('tenaga_kerja.wna_perempuan_tetap', $tk->wna_perempuan_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_perempuan_tidak_tetap]" value="{{ old('tenaga_kerja.wna_perempuan_tidak_tetap', $tk->wna_perempuan_tidak_tetap ?? 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja[wna_perempuan_keluarga]" value="{{ old('tenaga_kerja.wna_perempuan_keluarga', $tk->wna_perempuan_keluarga ?? 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 7: Lampiran -->
                        <div x-show="step===7" x-transition class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-6">Lampiran</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto KTP -->
                                <div>
                                    <x-input-label for="foto_ktp" :value="__('Foto KTP')" />
                                    @if($pembudidaya->foto_ktp)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_ktp) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_ktp')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat -->
                                <div>
                                    <x-input-label for="foto_sertifikat" :value="__('Foto Sertifikat')" />
                                    @if($pembudidaya->foto_sertifikat)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_sertifikat) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat" id="foto_sertifikat" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat')" class="mt-2" />
                                </div>

                                <!-- Foto CPIB/CBIB -->
                                <div>
                                    <x-input-label for="foto_cpib_cbib" :value="__('Foto CPIB/CBIB')" />
                                    @if($pembudidaya->foto_cpib_cbib)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_cpib_cbib) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_cpib_cbib" id="foto_cpib_cbib" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_cpib_cbib')" class="mt-2" />
                                </div>

                                <!-- Foto Unit Usaha -->
                                <div>
                                    <x-input-label for="foto_unit_usaha" :value="__('Foto Unit Usaha')" />
                                    @if($pembudidaya->foto_unit_usaha)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_unit_usaha) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_unit_usaha" id="foto_unit_usaha" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_unit_usaha')" class="mt-2" />
                                </div>

                                <!-- Foto KUSUKA -->
                                <div>
                                    <x-input-label for="foto_kusuka" :value="__('Foto KUSUKA')" />
                                    @if($pembudidaya->foto_kusuka)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_kusuka) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_kusuka" id="foto_kusuka" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_kusuka')" class="mt-2" />
                                </div>

                                <!-- Foto NIB -->
                                <div>
                                    <x-input-label for="foto_nib" :value="__('Foto NIB')" />
                                    @if($pembudidaya->foto_nib)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pembudidaya->foto_nib) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_nib" id="foto_nib" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_nib')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="px-6 pb-6 flex items-center justify-between border-t border-gray-200 pt-4">
                        <a href="{{ route('pembudidaya.index') }}" class="text-base text-slate-700 hover:text-slate-900 hover:underline">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-5 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-slate-700 text-sm font-medium transition" @click="if(step>0) step--" x-show="step>0">Sebelumnya</button>
                            <button type="button" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition" @click="if(step<maxStep) step++" x-show="step<maxStep">Berikutnya</button>
                            <button type="submit" x-show="step===maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                                {{ __('Update Data') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    @endpush

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil nilai latitude dan longitude dari data yang ada
            const existingLat = parseFloat('{{ $pembudidaya->latitude_usaha }}') || -8.188723;
            const existingLng = parseFloat('{{ $pembudidaya->longitude_usaha }}') || 113.688576;
            
            // Inisialisasi peta dengan center dari data yang ada atau default Jember
            const map = L.map('mapUsahaEdit').setView([existingLat, existingLng], 13);
            
            // Tambahkan tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Marker yang bisa dipindah - set ke posisi existing data
            let marker = L.marker([existingLat, existingLng], {draggable: true}).addTo(map);
            
            // Update input latitude & longitude saat marker dipindah
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                document.getElementById('latitude_usaha').value = position.lat.toFixed(6);
                document.getElementById('longitude_usaha').value = position.lng.toFixed(6);
            });
            
            // Saat user klik peta, pindahkan marker
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude_usaha').value = e.latlng.lat.toFixed(6);
                document.getElementById('longitude_usaha').value = e.latlng.lng.toFixed(6);
            });
            
            // Tombol "Gunakan Lokasi Saya"
            const btnLokasiSaya = document.getElementById('btnLokasiSayaEdit');
            if (btnLokasiSaya) {
                btnLokasiSaya.addEventListener('click', function() {
                    if (navigator.geolocation) {
                        btnLokasiSaya.textContent = 'Mencari lokasi...';
                        btnLokasiSaya.disabled = true;
                        
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Update peta dan marker
                            map.setView([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            
                            // Update input
                            document.getElementById('latitude_usaha').value = lat.toFixed(6);
                            document.getElementById('longitude_usaha').value = lng.toFixed(6);
                            
                            btnLokasiSaya.textContent = 'Gunakan Lokasi Saya';
                            btnLokasiSaya.disabled = false;
                        }, function(error) {
                            alert('Tidak dapat mengakses lokasi. Pastikan Anda mengizinkan akses lokasi di browser.');
                            btnLokasiSaya.textContent = 'Gunakan Lokasi Saya';
                            btnLokasiSaya.disabled = false;
                        });
                    } else {
                        alert('Browser Anda tidak mendukung geolocation.');
                    }
                });
            }
            
            // Update marker saat user input manual latitude/longitude
            document.getElementById('latitude_usaha').addEventListener('change', function() {
                const lat = parseFloat(this.value);
                const lng = parseFloat(document.getElementById('longitude_usaha').value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng]);
                }
            });
            
            document.getElementById('longitude_usaha').addEventListener('change', function() {
                const lat = parseFloat(document.getElementById('latitude_usaha').value);
                const lng = parseFloat(this.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    marker.setLatLng([lat, lng]);
                    map.setView([lat, lng]);
                }
            });
        });

        // Dependent dropdown: Kecamatan -> Desa
        const kecamatanSelect = document.getElementById('id_kecamatan');
        const desaSelect = document.getElementById('id_desa');
        const oldKecamatanValue = '{{ old("id_kecamatan", $pembudidaya->id_kecamatan) }}';
        const oldDesaValue = '{{ old("id_desa", $pembudidaya->id_desa) }}';

        function loadDesaOptions(idKecamatan, selectedDesaId = null) {
            desaSelect.innerHTML = '<option value="">Loading...</option>';
            desaSelect.disabled = true;
            desaSelect.classList.add('bg-gray-100');

            if (idKecamatan) {
                fetch(`/api/desa-by-kecamatan/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        desaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa;
                            option.textContent = desa.nama_desa;
                            if (selectedDesaId && selectedDesaId == desa.id_desa) {
                                option.selected = true;
                            }
                            desaSelect.appendChild(option);
                        });
                        desaSelect.disabled = false;
                        desaSelect.classList.remove('bg-gray-100');
                    })
                    .catch(error => {
                        console.error('Error fetching desa:', error);
                        desaSelect.innerHTML = '<option value="">Error loading desa</option>';
                    });
            } else {
                desaSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
            }
        }

        kecamatanSelect.addEventListener('change', function() {
            const idKecamatan = this.value;
            loadDesaOptions(idKecamatan);
        });

        // Load desa on page load with current kecamatan
        if (oldKecamatanValue) {
            loadDesaOptions(oldKecamatanValue, oldDesaValue);
        }

        // Dependent dropdown untuk Profil Usaha: Kecamatan Usaha -> Desa Usaha
        const kecamatanUsahaSelect = document.getElementById('kecamatan_usaha');
        const desaUsahaSelect = document.getElementById('desa_usaha');
        const oldKecamatanUsahaValue = '{{ old("kecamatan_usaha", $pembudidaya->kecamatan_usaha) }}';
        const oldDesaUsahaValue = '{{ old("desa_usaha", $pembudidaya->desa_usaha) }}';

        function loadDesaUsahaOptions(idKecamatan, selectedDesaId = null) {
            desaUsahaSelect.innerHTML = '<option value="">Loading...</option>';
            desaUsahaSelect.disabled = true;
            desaUsahaSelect.classList.add('bg-gray-100');

            if (idKecamatan) {
                fetch(`/api/desa-by-kecamatan/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        desaUsahaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa;
                            option.textContent = desa.nama_desa;
                            if (selectedDesaId && selectedDesaId == desa.id_desa) {
                                option.selected = true;
                            }
                            desaUsahaSelect.appendChild(option);
                        });
                        desaUsahaSelect.disabled = false;
                        desaUsahaSelect.classList.remove('bg-gray-100');
                    })
                    .catch(error => {
                        console.error('Error fetching desa usaha:', error);
                        desaUsahaSelect.innerHTML = '<option value="">Error loading desa</option>';
                    });
            } else {
                desaUsahaSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
            }
        }

        kecamatanUsahaSelect.addEventListener('change', function() {
            const idKecamatan = this.value;
            loadDesaUsahaOptions(idKecamatan);
        });

        // Load desa usaha on page load with current kecamatan usaha
        if (oldKecamatanUsahaValue) {
            loadDesaUsahaOptions(oldKecamatanUsahaValue, oldDesaUsahaValue);
        }
    </script>
    @endpush
</x-app-layout>
