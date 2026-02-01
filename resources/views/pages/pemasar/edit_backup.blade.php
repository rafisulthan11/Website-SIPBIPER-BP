<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Pemasar: ') . $pemasar->nama_lengkap }}
        </h2>
    </x-slot>

    @php
        $stepMap = [
            'jenis_kegiatan_usaha' => 0,
            'jenis_pemasaran' => 0,
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
            <div x-data="{ step: {{ $initialStep }}, maxStep: 7 }" class="bg-white border border-slate-200 rounded-md shadow">
                
                <!-- Header Biru -->
                <div class="bg-blue-600 px-6 py-4 rounded-t-md">
                    <h3 class="text-lg font-semibold text-white">Data Pemasar</h3>
                </div>

                <!-- Tabs -->
                <div class="px-5 pt-5">
                    <div class="flex flex-wrap gap-3">
                        @php $tabs = ['Jenis Usaha','Profil Pemilik','Izin Usaha','Profil Usaha','Investasi','Produksi','Tenaga Kerja','Lampiran']; @endphp
                        @foreach($tabs as $i => $tab)
                            <button type="button" @click="step={{ $i }}" :class="step==={{ $i }} ? 'bg-blue-700 text-white' : 'bg-white text-slate-700'" class="px-4 py-2 rounded border shadow-sm text-sm font-medium">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('pemasar.update', $pemasar->id_pemasar) }}" enctype="multipart/form-data" class="mt-5">
                    @csrf
                    @method('PUT')

                    <div class="px-5 pb-5">
                        <!-- Step 0: Jenis Usaha -->
                        <div x-show="step===0" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Jenis Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="jenis_kegiatan_usaha" :value="__('Jenis Kegiatan Usaha*')" />
                                    <select name="jenis_kegiatan_usaha" id="jenis_kegiatan_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis Kegiatan</option>
                                        @foreach(['Distribusi','Retail','Ekspor','Grosir'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_kegiatan_usaha', $pemasar->jenis_kegiatan_usaha)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="jenis_pemasaran" :value="__('Jenis Pemasaran*')" />
                                    <select name="jenis_pemasaran" id="jenis_pemasaran" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis Pemasaran</option>
                                        @foreach(['Ikan Segar','Ikan Beku','Ikan Olahan','Produk Perikanan'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_pemasaran', $pemasar->jenis_pemasaran)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="komoditas" :value="__('Komoditas*')" />
                                    <x-text-input id="komoditas" class="block mt-1 w-full" type="text" name="komoditas" :value="old('komoditas', $pemasar->komoditas)" required />
                                    <p class="text-xs text-gray-500 mt-1">Contoh: Ikan Lele, Ikan Nila, Udang, dll.</p>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="wilayah_pemasaran" :value="__('Wilayah Pemasaran')" />
                                    <x-text-input id="wilayah_pemasaran" class="block mt-1 w-full" type="text" name="wilayah_pemasaran" :value="old('wilayah_pemasaran', $pemasar->wilayah_pemasaran)" />
                                    <p class="text-xs text-gray-500 mt-1">Contoh: Jember, Surabaya, Bali, Nasional, Internasional</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Profil Pemilik -->
                        <div x-show="step===1" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Profil Pemilik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (Sesuai KTP)*')" />
                                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $pemasar->nama_lengkap)" required />
                                </div>
                                <div>
                                    <x-input-label for="nik_pemasar" :value="__('NIK (Sesuai KTP)*')" />
                                    <x-text-input id="nik_pemasar" class="block mt-1 w-full" type="text" name="nik_pemasar" :value="old('nik_pemasar', $pemasar->nik_pemasar)" required maxlength="16" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin*')" />
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $pemasar->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $pemasar->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $pemasar->tempat_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $pemasar->tanggal_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="pendidikan_terakhir" :value="__('Pendidikan Terakhir')" />
                                    <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Pendidikan</option>
                                        @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $opt)
                                            <option value="{{ $opt }}" {{ old('pendidikan_terakhir', $pemasar->pendidikan_terakhir)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select name="status_perkawinan" id="status_perkawinan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Status</option>
                                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $opt)
                                            <option value="{{ $opt }}" {{ old('status_perkawinan', $pemasar->status_perkawinan)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="jumlah_tanggungan" :value="__('Jumlah Tanggungan')" />
                                    <x-text-input id="jumlah_tanggungan" class="block mt-1 w-full" type="number" name="jumlah_tanggungan" :value="old('jumlah_tanggungan', $pemasar->jumlah_tanggungan)" min="0" />
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap (Sesuai KTP)*')" />
                                    <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3" required>{{ old('alamat', $pemasar->alamat) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $pemasar->id_kecamatan)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="id_desa" :value="__('Desa/Kelurahan*')" />
                                    <select name="id_desa" id="id_desa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Desa</option>
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id_desa }}" {{ old('id_desa', $pemasar->id_desa)==$desa->id_desa ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="kontak" :value="__('No. Telepon / HP*')" />
                                    <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak" :value="old('kontak', $pemasar->kontak)" required />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $pemasar->email)" />
                                </div>
                                <div>
                                    <x-input-label for="no_npwp" :value="__('No. NPWP')" />
                                    <x-text-input id="no_npwp" class="block mt-1 w-full" type="text" name="no_npwp" :value="old('no_npwp', $pemasar->no_npwp)" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Izin Usaha -->
                        <div x-show="step===2" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Izin Usaha</h3>
                            <p class="text-slate-600">Form Izin Usaha akan ditambahkan.</p>
                        </div>

                        <!-- Step 3: Profil Usaha -->
                        <div x-show="step===3" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Profil Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_usaha" :value="__('Nama Usaha')" />
                                    <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha', $pemasar->nama_usaha)" />
                                </div>
                                <div>
                                    <x-input-label for="npwp_usaha" :value="__('NPWP Usaha')" />
                                    <x-text-input id="npwp_usaha" class="block mt-1 w-full" type="text" name="npwp_usaha" :value="old('npwp_usaha', $pemasar->npwp_usaha)" />
                                </div>
                                <div>
                                    <x-input-label for="telp_usaha" :value="__('No. Telepon Usaha')" />
                                    <x-text-input id="telp_usaha" class="block mt-1 w-full" type="text" name="telp_usaha" :value="old('telp_usaha', $pemasar->telp_usaha)" />
                                </div>
                                <div>
                                    <x-input-label for="email_usaha" :value="__('Email Usaha')" />
                                    <x-text-input id="email_usaha" class="block mt-1 w-full" type="email" name="email_usaha" :value="old('email_usaha', $pemasar->email_usaha)" />
                                </div>
                                <div>
                                    <x-input-label for="skala_usaha" :value="__('Skala Usaha')" />
                                    <select name="skala_usaha" id="skala_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Skala Usaha</option>
                                        @foreach(['Mikro','Kecil','Menengah','Besar'] as $opt)
                                            <option value="{{ $opt }}" {{ old('skala_usaha', $pemasar->skala_usaha)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                    <select name="status_usaha" id="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Status</option>
                                        <option value="Aktif" {{ old('status_usaha', $pemasar->status_usaha)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status_usaha', $pemasar->status_usaha)=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                    <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pemasar->tahun_mulai_usaha)" min="1900" max="2100" />
                                </div>
                                <div class="lg:col-span-3">
                                    <x-input-label for="alamat_usaha" :value="__('Alamat Usaha')" />
                                    <textarea id="alamat_usaha" name="alamat_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('alamat_usaha', $pemasar->alamat_usaha) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="latitude" :value="__('Latitude')" />
                                    <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude', $pemasar->latitude)" />
                                </div>
                                <div>
                                    <x-input-label for="longitude" :value="__('Longitude')" />
                                    <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude', $pemasar->longitude)" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Investasi -->
                        <div x-show="step===4" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Investasi</h3>
                            <p class="text-slate-600">Form Investasi akan ditambahkan.</p>
                        </div>

                        <!-- Step 5: Produksi -->
                        <div x-show="step===5" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Produksi</h3>
                            <p class="text-slate-600">Form Produksi akan ditambahkan.</p>
                        </div>

                        <!-- Step 6: Tenaga Kerja -->
                        <div x-show="step===6" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Tenaga Kerja</h3>
                            <p class="text-slate-600">Form Tenaga Kerja akan ditambahkan.</p>
                        </div>

                        <!-- Step 7: Lampiran -->
                        <div x-show="step===7" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Lampiran</h3>
                            <p class="text-slate-600">Lampiran akan ditambahkan. Simpan data untuk menyelesaikan.</p>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="px-5 pb-5 flex items-center justify-between">
                        <a href="{{ route('pemasar.index') }}" class="text-base text-slate-700 hover:text-slate-900">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-4 py-2 rounded border bg-white hover:bg-gray-50 text-slate-700" @click="if(step>0) step--" x-show="step>0">Sebelumnya</button>
                            <button type="button" class="px-4 py-2 rounded bg-blue-700 hover:bg-blue-800 text-white" @click="if(step<maxStep) step++" x-show="step<maxStep">Berikutnya</button>
                            <x-primary-button x-show="step===maxStep">
                                {{ __('Update Data') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
