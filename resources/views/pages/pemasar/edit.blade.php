<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Pemasar: ') . $pemasar->nama_lengkap }}
        </h2>
    </x-slot>

    @php
        $stepMap = [
            'jenis_kegiatan_usaha' => 0,
            'nama_lengkap' => 1,
            'nik_pemasar' => 1,
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
            <div x-data="{ step: {{ $initialStep }}, maxStep: 6 }" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-bold">Data Pemasar</h2>
                </div>
                
                <!-- Edit Title -->
                <div class="px-6 pt-4 pb-2">
                    <h3 class="text-lg font-semibold text-slate-800">Edit Pemasar</h3>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-3">
                    <div class="flex flex-wrap gap-2">
                        @php $tabs = ['Jenis Usaha','Profil Pemilik','Izin Usaha','Profil Usaha','Investasi','Tenaga Kerja','Lampiran']; @endphp
                        @foreach($tabs as $i => $tab)
                            <button type="button" @click="step={{ $i }}" :class="step==={{ $i }} ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-gray-300'" class="px-4 py-2 rounded text-sm font-medium hover:bg-blue-50 transition">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('pemasar.update', $pemasar->id_pemasar) }}" enctype="multipart/form-data" novalidate>
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
                                        <option value="Pemasar Ikan Segar Pengecer" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Pemasar Ikan Segar Pengecer' ? 'selected' : '' }}>Pemasar Ikan Segar Pengecer</option>
                                        <option value="Pemasar Ikan Segar Pedagang Besar" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Pemasar Ikan Segar Pedagang Besar' ? 'selected' : '' }}>Pemasar Ikan Segar Pedagang Besar</option>
                                        <option value="Pemasar Ikan Pindang/Asap" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Pemasar Ikan Pindang/Asap' ? 'selected' : '' }}>Pemasar Ikan Pindang/Asap</option>
                                        <option value="Pemasar Ikan Hias" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Pemasar Ikan Hias' ? 'selected' : '' }}>Pemasar Ikan Hias</option>
                                        <option value="Pemasar Ikan Asin" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Pemasar Ikan Asin' ? 'selected' : '' }}>Pemasar Ikan Asin</option>
                                        <option value="Lainnya" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)=='Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kegiatan_usaha')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Profil Pemilik -->
                        <div x-show="step===1" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Profil Pemilik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (Sesuai KTP)*')" />
                                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $pemasar->nama_lengkap)" required />
                                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="nik_pemasar" :value="__('NIK (Sesuai KTP)*')" />
                                    <x-text-input id="nik_pemasar" class="block mt-1 w-full" type="text" name="nik_pemasar" :value="old('nik_pemasar', $pemasar->nik_pemasar)" required maxlength="16" />
                                    <x-input-error :messages="$errors->get('nik_pemasar')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $pemasar->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $pemasar->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $pemasar->tempat_lahir)" />
                                    <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $pemasar->tanggal_lahir)" />
                                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pendidikan_terakhir" :value="__('Pendidikan Terakhir')" />
                                    <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Pendidikan</option>
                                        @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $opt)
                                            <option value="{{ $opt }}" {{ old('pendidikan_terakhir', $pemasar->pendidikan_terakhir)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('pendidikan_terakhir')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select name="status_perkawinan" id="status_perkawinan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Status</option>
                                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $opt)
                                            <option value="{{ $opt }}" {{ old('status_perkawinan', $pemasar->status_perkawinan)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('status_perkawinan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                    <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pemasar->tahun_mulai_usaha)" min="1900" max="2099" />
                                    <x-input-error :messages="$errors->get('tahun_mulai_usaha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="aset_pribadi" :value="__('Aset Pribadi')" />
                                    <x-text-input id="aset_pribadi" class="block mt-1 w-full" type="number" name="aset_pribadi" :value="old('aset_pribadi', $pemasar->aset_pribadi)" step="0.01" placeholder="0.00" />
                                    <x-input-error :messages="$errors->get('aset_pribadi')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jumlah_tanggungan" :value="__('Jumlah Tanggungan')" />
                                    <x-text-input id="jumlah_tanggungan" class="block mt-1 w-full" type="number" name="jumlah_tanggungan" :value="old('jumlah_tanggungan', $pemasar->jumlah_tanggungan)" min="0" />
                                    <x-input-error :messages="$errors->get('jumlah_tanggungan')" class="mt-2" />
                                </div>
                                <div class="md:col-span-2 lg:col-span-2 flex items-center mt-7">
                                    <p class="text-xs text-gray-500">(Jumlah anggota keluarga yang ditanggung, tidak termasuk diri sendiri)</p>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap (Sesuai KTP)*')" />
                                    <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3" required>{{ old('alamat', $pemasar->alamat) }}</textarea>
                                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $pemasar->id_kecamatan)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_kecamatan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="id_desa" :value="__('Desa/Kelurahan*')" />
                                    <select name="id_desa" id="id_desa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Loading...</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('id_desa')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="kontak" :value="__('No. Telepon / HP*')" />
                                    <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak" :value="old('kontak', $pemasar->kontak)" required />
                                    <x-input-error :messages="$errors->get('kontak')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $pemasar->email)" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="no_npwp" :value="__('No. NPWP')" />
                                    <x-text-input id="no_npwp" class="block mt-1 w-full" type="text" name="no_npwp" :value="old('no_npwp', $pemasar->no_npwp)" />
                                    <x-input-error :messages="$errors->get('no_npwp')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Izin Usaha -->
                        <div x-show="step===2" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Izin Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nib" :value="__('NIB (Nomor Induk Berusaha)')" />
                                    <x-text-input id="nib" class="block mt-1 w-full" type="text" name="nib" :value="old('nib', $pemasar->nib)" />
                                    <x-input-error :messages="$errors->get('nib')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="npwp_izin" :value="__('NPWP')" />
                                    <x-text-input id="npwp_izin" class="block mt-1 w-full" type="text" name="npwp_izin" :value="old('npwp_izin', $pemasar->npwp_izin)" />
                                    <x-input-error :messages="$errors->get('npwp_izin')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="kusuka" :value="__('KUSUKA')" />
                                    <x-text-input id="kusuka" class="block mt-1 w-full" type="text" name="kusuka" :value="old('kusuka', $pemasar->kusuka)" />
                                    <x-input-error :messages="$errors->get('kusuka')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="pengesahan_menkumham" :value="__('Pengesahan MENKUMHAM')" />
                                    <x-text-input id="pengesahan_menkumham" class="block mt-1 w-full" type="text" name="pengesahan_menkumham" :value="old('pengesahan_menkumham', $pemasar->pengesahan_menkumham)" />
                                    <x-input-error :messages="$errors->get('pengesahan_menkumham')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tdu_php" :value="__('TDU-PHP')" />
                                    <x-text-input id="tdu_php" class="block mt-1 w-full" type="text" name="tdu_php" :value="old('tdu_php', $pemasar->tdu_php)" />
                                    <x-input-error :messages="$errors->get('tdu_php')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="sppl" :value="__('SPPL')" />
                                    <x-text-input id="sppl" class="block mt-1 w-full" type="text" name="sppl" :value="old('sppl', $pemasar->sppl)" />
                                    <x-input-error :messages="$errors->get('sppl')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="siup_perdagangan" :value="__('SIUP Perdagangan')" />
                                    <x-text-input id="siup_perdagangan" class="block mt-1 w-full" type="text" name="siup_perdagangan" :value="old('siup_perdagangan', $pemasar->siup_perdagangan)" />
                                    <x-input-error :messages="$errors->get('siup_perdagangan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="akta_pendiri_usaha" :value="__('AKTA Pendiri Usaha')" />
                                    <x-text-input id="akta_pendiri_usaha" class="block mt-1 w-full" type="text" name="akta_pendiri_usaha" :value="old('akta_pendiri_usaha', $pemasar->akta_pendiri_usaha)" />
                                    <x-input-error :messages="$errors->get('akta_pendiri_usaha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="imb" :value="__('IMB (Izin Mendirikan Bangunan)')" />
                                    <x-text-input id="imb" class="block mt-1 w-full" type="text" name="imb" :value="old('imb', $pemasar->imb)" />
                                    <x-input-error :messages="$errors->get('imb')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="siup_perikanan" :value="__('SIUP Perikanan')" />
                                    <x-text-input id="siup_perikanan" class="block mt-1 w-full" type="text" name="siup_perikanan" :value="old('siup_perikanan', $pemasar->siup_perikanan)" />
                                    <x-input-error :messages="$errors->get('siup_perikanan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="ukl_upl" :value="__('UKL-UPL')" />
                                    <x-text-input id="ukl_upl" class="block mt-1 w-full" type="text" name="ukl_upl" :value="old('ukl_upl', $pemasar->ukl_upl)" />
                                    <x-input-error :messages="$errors->get('ukl_upl')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="amdal" :value="__('AMDAL')" />
                                    <x-text-input id="amdal" class="block mt-1 w-full" type="text" name="amdal" :value="old('amdal', $pemasar->amdal)" />
                                    <x-input-error :messages="$errors->get('amdal')" class="mt-2" />
                                </div>
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
                                        <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha', $pemasar->nama_usaha)" />
                                        <x-input-error :messages="$errors->get('nama_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="nama_kelompok" :value="__('Nama Kelompok')" />
                                        <x-text-input id="nama_kelompok" class="block mt-1 w-full" type="text" name="nama_kelompok" :value="old('nama_kelompok', $pemasar->nama_kelompok)" />
                                        <x-input-error :messages="$errors->get('nama_kelompok')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="npwp_usaha" :value="__('NPWP Usaha')" />
                                        <x-text-input id="npwp_usaha" class="block mt-1 w-full" type="text" name="npwp_usaha" :value="old('npwp_usaha', $pemasar->npwp_usaha)" />
                                        <x-input-error :messages="$errors->get('npwp_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="telp_usaha" :value="__('No. Telepon Usaha')" />
                                        <x-text-input id="telp_usaha" class="block mt-1 w-full" type="text" name="telp_usaha" :value="old('telp_usaha', $pemasar->telp_usaha)" />
                                        <x-input-error :messages="$errors->get('telp_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="email_usaha" :value="__('Email Usaha')" />
                                        <x-text-input id="email_usaha" class="block mt-1 w-full" type="email" name="email_usaha" :value="old('email_usaha', $pemasar->email_usaha)" />
                                        <x-input-error :messages="$errors->get('email_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                        <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pemasar->tahun_mulai_usaha)" min="1900" max="2100" placeholder="Contoh: 2020" />
                                        <x-input-error :messages="$errors->get('tahun_mulai_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                        <select name="status_usaha" id="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="Aktif" {{ old('status_usaha', $pemasar->status_usaha)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Tidak Aktif" {{ old('status_usaha', $pemasar->status_usaha)=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="skala_usaha" :value="__('Skala Usaha')" />
                                        <select name="skala_usaha" id="skala_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Skala Usaha</option>
                                            <option value="Mikro" {{ old('skala_usaha', $pemasar->skala_usaha)=='Mikro' ? 'selected' : '' }}>Mikro</option>
                                            <option value="Kecil" {{ old('skala_usaha', $pemasar->skala_usaha)=='Kecil' ? 'selected' : '' }}>Kecil</option>
                                            <option value="Menengah" {{ old('skala_usaha', $pemasar->skala_usaha)=='Menengah' ? 'selected' : '' }}>Menengah</option>
                                            <option value="Besar" {{ old('skala_usaha', $pemasar->skala_usaha)=='Besar' ? 'selected' : '' }}>Besar</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('skala_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="komoditas" :value="__('Komoditas')" />
                                        <select name="komoditas" id="komoditas" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Komoditas</option>
                                            @foreach($komoditas as $item)
                                                <option value="{{ $item->nama_komoditas }}" {{ old('komoditas', $pemasar->komoditas)==$item->nama_komoditas ? 'selected' : '' }}>
                                                    {{ $item->nama_komoditas }} ({{ $item->tipe }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('komoditas')" class="mt-2" />
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
                                                <option value="{{ $kecamatan->id_kecamatan }}" {{ old('kecamatan_usaha', $pemasar->id_kecamatan_usaha)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
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
                                        <x-input-label for="alamat_usaha" :value="__('Alamat Lengkap Usaha')" />
                                        <textarea id="alamat_usaha" name="alamat_usaha" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat_usaha', $pemasar->alamat_usaha) }}</textarea>
                                    </div>
                                </div>

                                <!-- Peta Lokasi Usaha -->
                                <div class="mb-4">
                                    <x-input-label :value="__('Peta Lokasi Usaha')" class="mb-2" />
                                    <p class="text-sm text-slate-600 mb-3">Klik pada peta untuk menandai lokasi usaha Anda atau izinkan akses lokasi browser agar tidak otomatis mengikuti posisi Anda.</p>
                                    
                                    <!-- Tombol Gunakan Lokasi Saya -->
                                    <button type="button" id="btnLokasiSayaPemasarEdit" class="mb-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">
                                        Gunakan Lokasi Saya
                                    </button>

                                    <!-- Peta Interaktif -->
                                    <div id="mapUsahaPemasarEdit" class="w-full h-64 bg-gray-200 rounded-md border border-gray-300 mb-3 relative overflow-hidden z-0"></div>

                                    <!-- Input Latitude & Longitude -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="latitude" :value="__('Latitude')" />
                                            <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude', $pemasar->latitude)" placeholder="-8.188723" />
                                            <p class="text-xs text-slate-500 mt-1">Akan terisi otomatis saat memilih lokasi di peta</p>
                                        </div>
                                        <div>
                                            <x-input-label for="longitude" :value="__('Longitude')" />
                                            <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude', $pemasar->longitude)" placeholder="113.688576" />
                                            <p class="text-xs text-slate-500 mt-1">Akan terisi otomatis saat memilih lokasi di peta</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Investasi -->
                        <div x-show="step===4" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6" x-data="{
                            mesinPeralatan: @js($pemasar->mesin_peralatan ? json_decode($pemasar->mesin_peralatan) : [['jenis_mesin' => '', 'kapasitas' => '', 'jumlah' => '', 'asal' => '']])
                        }">
                            <h3 class="text-lg font-semibold mb-4">Investasi</h3>
                            
                            <!-- Mesin/Peralatan Section -->
                            <div class="space-y-4">
                                <template x-for="(mesin, index) in mesinPeralatan" :key="index">
                                    <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-semibold text-slate-800">Mesin/Peralatan <span x-text="index + 1"></span></h4>
                                            <button type="button" @click="mesinPeralatan.splice(index, 1)" x-show="mesinPeralatan.length > 1" class="px-3 py-1.5 text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded transition">Hapus</button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <x-input-label :value="__('Jenis Mesin')" class="font-semibold" />
                                                <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-bind:name="'mesin_peralatan['+index+'][jenis_mesin]'" x-model="mesinPeralatan[index].jenis_mesin" />
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Kapasitas')" class="font-semibold" />
                                                <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-bind:name="'mesin_peralatan['+index+'][kapasitas]'" x-model="mesinPeralatan[index].kapasitas" />
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Jumlah')" class="font-semibold" />
                                                <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" x-bind:name="'mesin_peralatan['+index+'][jumlah]'" x-model="mesinPeralatan[index].jumlah" />
                                            </div>
                                            <div>
                                                <x-input-label :value="__('Asal')" class="font-semibold" />
                                                <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-bind:name="'mesin_peralatan['+index+'][asal]'" x-model="mesinPeralatan[index].asal" />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="mesinPeralatan.push({})" class="mb-6 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition">
                                + Tambah Mesin/Peralatan
                            </button>

                            <!-- Nilai Investasi (Modal Tetap / MT) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Nilai Investasi (Modal Tetap / MT)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="investasi_tanah" :value="__('Tanah')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_tanah" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_tanah" value="{{ old('investasi_tanah', $pemasar->investasi_tanah ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="investasi_gedung" :value="__('Gedung')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_gedung" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_gedung" value="{{ old('investasi_gedung', $pemasar->investasi_gedung ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="investasi_mesin_peralatan" :value="__('Mesin/Peralatan')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_mesin_peralatan" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_mesin_peralatan" value="{{ old('investasi_mesin_peralatan', $pemasar->investasi_mesin_peralatan ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="investasi_kendaraan" :value="__('Kendaraan')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_kendaraan" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_kendaraan" value="{{ old('investasi_kendaraan', $pemasar->investasi_kendaraan ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="investasi_lain_lain" :value="__('Lain-lain')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_lain_lain" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_lain_lain" value="{{ old('investasi_lain_lain', $pemasar->investasi_lain_lain ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="investasi_sub_jumlah" :value="__('Sub Jumlah')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="investasi_sub_jumlah" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="investasi_sub_jumlah" value="{{ old('investasi_sub_jumlah', $pemasar->investasi_sub_jumlah ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Investasi (Modal Kerja / MK) -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Nilai Investasi (Modal Kerja / MK)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="modal_kerja_1_bulan" :value="__('1 Bulan')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="modal_kerja_1_bulan" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="modal_kerja_1_bulan" value="{{ old('modal_kerja_1_bulan', $pemasar->modal_kerja_1_bulan ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="modal_kerja_sub_jumlah" :value="__('Sub Jumlah')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="modal_kerja_sub_jumlah" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="modal_kerja_sub_jumlah" value="{{ old('modal_kerja_sub_jumlah', $pemasar->modal_kerja_sub_jumlah ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sumber Pembiayaan -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Sumber Pembiayaan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <x-input-label for="modal_sendiri" :value="__('Modal Sendiri')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="modal_sendiri" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="modal_sendiri" value="{{ old('modal_sendiri', $pemasar->modal_sendiri ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="laba_ditanam" :value="__('Laba ditanam')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="laba_ditanam" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="laba_ditanam" value="{{ old('laba_ditanam', $pemasar->laba_ditanam ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="modal_pinjam" :value="__('Modal Pinjam')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="modal_pinjam" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="modal_pinjam" value="{{ old('modal_pinjam', $pemasar->modal_pinjam ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sertifikat Lahan -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Sertifikat Lahan</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_lahan[]" value="SHM" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) && in_array('SHM', old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">SHM</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_lahan[]" value="SHSRS" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) && in_array('SHSRS', old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">SHSRS</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_lahan[]" value="SHGB" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) && in_array('SHGB', old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">SHGB</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_lahan[]" value="Girik/Petok" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) && in_array('Girik/Petok', old('sertifikat_lahan', json_decode($pemasar->sertifikat_lahan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">Girik/Petok</span>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <x-input-label for="luas_lahan" :value="__('Luas Lahan')" />
                                        <div class="flex items-center gap-2 mt-1">
                                            <input id="luas_lahan" class="block w-full border-gray-300 rounded-md shadow-sm" type="number" name="luas_lahan" value="{{ old('luas_lahan', $pemasar->luas_lahan ?? '') }}" step="0.01" />
                                            <span class="text-sm text-gray-600 whitespace-nowrap">m2</span>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="nilai_lahan" :value="__('Nilai')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="nilai_lahan" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="nilai_lahan" value="{{ old('nilai_lahan', $pemasar->nilai_lahan ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sertifikat Bangunan -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Sertifikat Bangunan</h4>
                                <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_bangunan[]" value="IMB" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_bangunan', json_decode($pemasar->sertifikat_bangunan ?? '[]'))) && in_array('IMB', old('sertifikat_bangunan', json_decode($pemasar->sertifikat_bangunan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">IMB</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sertifikat_bangunan[]" value="NON-IMB" class="rounded border-gray-300 text-blue-600" {{ is_array(old('sertifikat_bangunan', json_decode($pemasar->sertifikat_bangunan ?? '[]'))) && in_array('NON-IMB', old('sertifikat_bangunan', json_decode($pemasar->sertifikat_bangunan ?? '[]'))) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm">NON-IMB</span>
                                    </label>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <x-input-label for="luas_bangunan" :value="__('Luas Bangunan')" />
                                        <div class="flex items-center gap-2 mt-1">
                                            <input id="luas_bangunan" class="block w-full border-gray-300 rounded-md shadow-sm" type="number" name="luas_bangunan" value="{{ old('luas_bangunan', $pemasar->luas_bangunan ?? '') }}" step="0.01" />
                                            <span class="text-sm text-gray-600 whitespace-nowrap">m2</span>
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label for="nilai_bangunan" :value="__('Nilai')" />
                                        <div class="relative mt-1">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp.</span>
                                            <input id="nilai_bangunan" class="block w-full pl-12 border-gray-300 rounded-md shadow-sm" type="number" name="nilai_bangunan" value="{{ old('nilai_bangunan', $pemasar->nilai_bangunan ?? '') }}" step="0.01" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kapasitas Terpasang Setahun -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Kapasitas Terpasang Setahun</h4>
                                <div class="flex items-center gap-2">
                                    <input class="block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm" type="number" name="kapasitas_terpasang_setahun" value="{{ old('kapasitas_terpasang_setahun', $pemasar->kapasitas_terpasang_setahun ?? '') }}" step="0.01" />
                                    <span class="text-sm text-gray-600">Kg</span>
                                </div>
                            </div>

                            <!-- Bulan Produksi -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Bulan Produksi <span class="text-xs text-gray-500">(Centang yang dipilih)</span></h4>
                                <div class="grid grid-cols-6 md:grid-cols-12 gap-3">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="bulan_produksi[]" value="{{ $i }}" class="rounded border-gray-300 text-blue-600" {{ is_array(old('bulan_produksi', json_decode($pemasar->bulan_produksi ?? '[]'))) && in_array($i, old('bulan_produksi', json_decode($pemasar->bulan_produksi ?? '[]'))) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm">{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Jumlah Hari Produksi/bulan -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Jumlah Hari Produksi/bulan</h4>
                                <div class="flex items-center gap-2">
                                    <input class="block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm" type="number" name="jumlah_hari_produksi" value="{{ old('jumlah_hari_produksi', $pemasar->jumlah_hari_produksi ?? '') }}" min="0" />
                                    <span class="text-sm text-gray-600">hari</span>
                                </div>
                            </div>

                            <!-- Distribusi Pemasaran -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">Distribusi Pemasaran</h4>
                                <textarea class="block w-full border-gray-300 rounded-md shadow-sm" name="distribusi_pemasaran" rows="3">{{ old('distribusi_pemasaran', $pemasar->distribusi_pemasaran ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Step 5: Tenaga Kerja -->
                        <div x-show="step===5" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Tenaga Kerja</h3>
                            
                            <!-- WNI Section -->
                            <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">WNI</h4>
                                
                                <!-- Laki-laki -->
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-3">Laki-laki</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wni_laki_tetap" :value="__('Tetap :')" />
                                            <input id="wni_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_laki_tetap" value="{{ old('wni_laki_tetap', $pemasar->wni_laki_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_laki_tidak_tetap" value="{{ old('wni_laki_tidak_tetap', $pemasar->wni_laki_tidak_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_laki_keluarga" value="{{ old('wni_laki_keluarga', $pemasar->wni_laki_keluarga ?? '') }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Perempuan -->
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wni_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wni_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_perempuan_tetap" value="{{ old('wni_perempuan_tetap', $pemasar->wni_perempuan_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_perempuan_tidak_tetap" value="{{ old('wni_perempuan_tidak_tetap', $pemasar->wni_perempuan_tidak_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wni_perempuan_keluarga" value="{{ old('wni_perempuan_keluarga', $pemasar->wni_perempuan_keluarga ?? '') }}" min="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WNA Section -->
                            <div class="p-4 bg-white rounded-lg border border-gray-200">
                                <h4 class="font-semibold text-slate-800 mb-4">WNA</h4>
                                
                                <!-- Laki-laki -->
                                <div class="mb-4">
                                    <h5 class="font-medium text-slate-700 mb-3">Laki-laki</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wna_laki_tetap" :value="__('Tetap :')" />
                                            <input id="wna_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_laki_tetap" value="{{ old('wna_laki_tetap', $pemasar->wna_laki_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_laki_tidak_tetap" value="{{ old('wna_laki_tidak_tetap', $pemasar->wna_laki_tidak_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_laki_keluarga" value="{{ old('wna_laki_keluarga', $pemasar->wna_laki_keluarga ?? '') }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Perempuan -->
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wna_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wna_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_perempuan_tetap" value="{{ old('wna_perempuan_tetap', $pemasar->wna_perempuan_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_perempuan_tidak_tetap" value="{{ old('wna_perempuan_tidak_tetap', $pemasar->wna_perempuan_tidak_tetap ?? '') }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="wna_perempuan_keluarga" value="{{ old('wna_perempuan_keluarga', $pemasar->wna_perempuan_keluarga ?? '') }}" min="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6: Lampiran -->
                        <div x-show="step===6" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Lampiran</h3>
                            <p class="text-slate-600 mb-6">Unggah dokumentasi berikut (format: JPG, PNG, PDF. Maksimal 2MB per file)</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="foto_ktp" :value="__('Foto KTP')" />
                                    @if($pemasar->foto_ktp)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_ktp) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_ktp')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_sertifikat" :value="__('Foto Sertifikat')" />
                                    @if($pemasar->foto_sertifikat)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_sertifikat) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat" id="foto_sertifikat" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_cpib_cbib" :value="__('Foto CPIB/CBIB')" />
                                    @if($pemasar->foto_cpib_cbib)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_cpib_cbib) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_cpib_cbib" id="foto_cpib_cbib" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_cpib_cbib')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_unit_usaha" :value="__('Foto Unit Usaha')" />
                                    @if($pemasar->foto_unit_usaha)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_unit_usaha) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_unit_usaha" id="foto_unit_usaha" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_unit_usaha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_npwp" :value="__('Foto NPWP')" />
                                    @if($pemasar->foto_npwp)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_npwp) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_npwp" id="foto_npwp" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_npwp')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_izin_usaha" :value="__('Foto Izin Usaha')" />
                                    @if($pemasar->foto_izin_usaha)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_izin_usaha) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_izin_usaha" id="foto_izin_usaha" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_izin_usaha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="foto_produk" :value="__('Foto Produk')" />
                                    @if($pemasar->foto_produk)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_produk) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_produk" id="foto_produk" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_produk')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat PIRT -->
                                <div>
                                    <x-input-label for="foto_sertifikat_pirt" :value="__('Foto Sertifikat PIRT')" />
                                    @if($pemasar->foto_sertifikat_pirt)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_sertifikat_pirt) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat_pirt" id="foto_sertifikat_pirt" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat_pirt')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat Halal -->
                                <div>
                                    <x-input-label for="foto_sertifikat_halal" :value="__('Foto Sertifikat Halal')" />
                                    @if($pemasar->foto_sertifikat_halal)
                                        <p class="text-sm text-gray-600 mt-1">File saat ini: <a href="{{ asset('storage/' . $pemasar->foto_sertifikat_halal) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat_halal" id="foto_sertifikat_halal" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat_halal')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="px-6 pb-6 flex justify-between items-center border-t pt-6">
                        <a href="{{ route('pemasar.index') }}" class="text-base text-slate-700 hover:text-slate-900 hover:underline">Batal</a>
                        <div class="flex gap-3">
                            <button type="button" @click="if(step > 0) step--" x-show="step > 0" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                Sebelumnya
                            </button>
                            <button type="button" @click="if(step < maxStep) step++" x-show="step < maxStep" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Berikutnya
                            </button>
                            <button type="submit" x-show="step === maxStep" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
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
            // Tunggu sedikit untuk memastikan DOM dan Leaflet sudah siap
            setTimeout(function() {
                // Inisialisasi peta untuk lokasi usaha (edit)
                const initialLat = {{ $pemasar->latitude ?? '-8.1879' }};
                const initialLng = {{ $pemasar->longitude ?? '113.6885' }};
                
                // Cek apakah elemen peta ada
                const mapElement = document.getElementById('mapUsahaPemasarEdit');
                if (!mapElement) {
                    console.error('Map element not found');
                    return;
                }

                // Inisialisasi peta
                const mapUsahaEdit = L.map('mapUsahaPemasarEdit').setView([initialLat, initialLng], 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(mapUsahaEdit);

                let markerUsahaEdit = L.marker([initialLat, initialLng], {draggable: true}).addTo(mapUsahaEdit);

                // Update koordinat saat marker di-drag
                markerUsahaEdit.on('dragend', function(e) {
                    const position = markerUsahaEdit.getLatLng();
                    document.getElementById('latitude').value = position.lat.toFixed(6);
                    document.getElementById('longitude').value = position.lng.toFixed(6);
                });

                // Update marker saat peta diklik
                mapUsahaEdit.on('click', function(e) {
                    markerUsahaEdit.setLatLng(e.latlng);
                    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
                    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
                });

                // Tombol Gunakan Lokasi Saya
                const btnLokasi = document.getElementById('btnLokasiSayaPemasarEdit');
                if (btnLokasi) {
                    btnLokasi.addEventListener('click', function() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                
                                markerUsahaEdit.setLatLng([lat, lng]);
                                mapUsahaEdit.setView([lat, lng], 15);
                                
                                document.getElementById('latitude').value = lat.toFixed(6);
                                document.getElementById('longitude').value = lng.toFixed(6);
                            }, function(error) {
                                alert('Tidak dapat mengakses lokasi Anda. Pastikan Anda mengizinkan akses lokasi di browser.');
                            });
                        } else {
                            alert('Browser Anda tidak mendukung geolocation.');
                        }
                    });
                }

                // Update marker saat input latitude/longitude diubah manual
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                
                if (latInput) {
                    latInput.addEventListener('change', function() {
                        const lat = parseFloat(this.value);
                        const lng = parseFloat(lngInput.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            markerUsahaEdit.setLatLng([lat, lng]);
                            mapUsahaEdit.setView([lat, lng], 13);
                        }
                    });
                }

                if (lngInput) {
                    lngInput.addEventListener('change', function() {
                        const lat = parseFloat(latInput.value);
                        const lng = parseFloat(this.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            markerUsahaEdit.setLatLng([lat, lng]);
                            mapUsahaEdit.setView([lat, lng], 13);
                        }
                    });
                }

                // Force map to refresh setelah init
                setTimeout(function() {
                    mapUsahaEdit.invalidateSize();
                }, 100);

                // Refresh peta saat tab Profil Usaha dibuka (step 3)
                // Menggunakan MutationObserver untuk mendeteksi perubahan visibility
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'style') {
                            const isVisible = mapElement.offsetParent !== null;
                            if (isVisible) {
                                setTimeout(function() {
                                    mapUsahaEdit.invalidateSize();
                                }, 50);
                            }
                        }
                    });
                });

                // Observe perubahan pada parent container
                const stepContainer = mapElement.closest('[x-show]');
                if (stepContainer) {
                    observer.observe(stepContainer, { 
                        attributes: true, 
                        attributeFilter: ['style'] 
                    });
                }
            }, 500); // Delay 500ms untuk memastikan tab sudah render
        });

        // Dependent dropdown: Kecamatan -> Desa
        const kecamatanSelect = document.getElementById('id_kecamatan');
        const desaSelect = document.getElementById('id_desa');
        const oldKecamatanValue = '{{ old("id_kecamatan", $pemasar->id_kecamatan) }}';
        const oldDesaValue = '{{ old("id_desa", $pemasar->id_desa) }}';

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
        const oldKecamatanUsahaValue = '{{ old("kecamatan_usaha", $pemasar->id_kecamatan_usaha) }}';
        const oldDesaUsahaValue = '{{ old("desa_usaha", $pemasar->id_desa_usaha) }}';

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

        if (oldKecamatanUsahaValue) {
            loadDesaUsahaOptions(oldKecamatanUsahaValue, oldDesaUsahaValue);
        }
    </script>
    @endpush
</x-app-layout>
