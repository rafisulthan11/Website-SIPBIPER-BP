<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Tambah Data Pembudidaya') }}
        </h2>
    </x-slot>

    @php
        // Tentukan step awal berdasarkan error pertama (agar user langsung diarahkan ke bagian yang perlu diperbaiki)
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
            <div x-data="{ step: {{ $initialStep }}, maxStep: 7 }" class="bg-white border border-slate-200 rounded-md shadow">
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

                <form method="POST" action="{{ route('pembudidaya.store') }}" class="mt-5">
                    @csrf
                    <!-- Step panels -->
                    <div class="px-5 pb-5">
                        <!-- Step 0: Jenis Usaha -->
                        <div x-show="step===0" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Jenis Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="jenis_kegiatan_usaha" :value="__('Jenis Kegiatan Usaha*')" />
                                    <!-- pakai select untuk kompatibilitas backend; tampilkan opsinya seperti desain -->
                                    <select id="jenis_kegiatan_usaha" name="jenis_kegiatan_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis Kegiatan</option>
                                        @foreach(['Pembenihan','Pembesaran','Tambak'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_kegiatan_usaha')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kegiatan_usaha')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_budidaya" :value="__('Jenis Budidaya*')" />
                                    <select id="jenis_budidaya" name="jenis_budidaya" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis Budidaya</option>
                                        @foreach(['Kolam','Mina Padi','Keramba','Tambak'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_budidaya')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_budidaya')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Profil Pemilik -->
                        <div x-show="step===1" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Profil Pemilik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_lengkap" :value="__('Nama Lengkap (Sesuai KTP)*')" />
                                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required />
                                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="nik_pembudidaya" :value="__('NIK (Sesuai KTP)*')" />
                                    <x-text-input id="nik_pembudidaya" class="block mt-1 w-full" type="text" name="nik_pembudidaya" :value="old('nik_pembudidaya')" required />
                                    <x-input-error :messages="$errors->get('nik_pembudidaya')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir')" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" />
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select name="status_perkawinan" id="status_perkawinan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Status</option>
                                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $opt)
                                            <option value="{{ $opt }}" {{ old('status_perkawinan')===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap (Sesuai KTP)')" />
                                    <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat') }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan')==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_kecamatan')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="id_desa" :value="__('Desa/Kelurahan*')" />
                                    <select name="id_desa" id="id_desa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Desa</option>
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id_desa }}" {{ old('id_desa')==$desa->id_desa ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_desa')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="kontak" :value="__('No. Telepon / HP')" />
                                    <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak" :value="old('kontak')" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" />
                                </div>
                                <div>
                                    <x-input-label for="no_npwp" :value="__('No. NPWP')" />
                                    <x-text-input id="no_npwp" class="block mt-1 w-full" type="text" name="no_npwp" :value="old('no_npwp')" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Izin Usaha -->
                        <div x-show="step===2" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Izin Usaha</h3>
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
                                        <x-text-input :id="'izin_' . $name" class="block mt-1 w-full" type="text" :name="'izin['.$name.']'" :value="old('izin.'.$name)" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Profil Usaha -->
                        <div x-show="step===3" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Profil Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_usaha" :value="__('Nama Usaha')" />
                                    <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha')" />
                                </div>
                                <div>
                                    <x-input-label for="npwp_usaha" :value="__('NPWP Usaha')" />
                                    <x-text-input id="npwp_usaha" class="block mt-1 w-full" type="text" name="npwp_usaha" :value="old('npwp_usaha')" />
                                </div>
                                <div>
                                    <x-input-label for="telp_usaha" :value="__('No. Telepon Usaha')" />
                                    <x-text-input id="telp_usaha" class="block mt-1 w-full" type="text" name="telp_usaha" :value="old('telp_usaha')" />
                                </div>
                                <div>
                                    <x-input-label for="email_usaha" :value="__('Email Usaha')" />
                                    <x-text-input id="email_usaha" class="block mt-1 w-full" type="email" name="email_usaha" :value="old('email_usaha')" />
                                </div>
                                <div>
                                    <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                    <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha')" placeholder="Contoh: 2020" />
                                </div>
                                <div>
                                    <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                    <select id="status_usaha" name="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Aktif" {{ old('status_usaha')=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status_usaha')=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="lg:col-span-3">
                                    <x-input-label for="alamat_usaha" :value="__('Alamat Usaha')" />
                                    <textarea id="alamat_usaha" name="alamat_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat_usaha') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Investasi -->
                        <div x-show="step===4" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Investasi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="investasi_nilai_asset" :value="__('Nilai Asset (Rp)')" />
                                    <x-text-input id="investasi_nilai_asset" class="block mt-1 w-full" type="number" step="0.01" name="investasi[nilai_asset]" :value="old('investasi.nilai_asset')" />
                                </div>
                                <div>
                                    <x-input-label for="investasi_laba_ditanam" :value="__('Laba Ditanam (Rp)')" />
                                    <x-text-input id="investasi_laba_ditanam" class="block mt-1 w-full" type="number" step="0.01" name="investasi[laba_ditanam]" :value="old('investasi.laba_ditanam')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_sewa" :value="__('Sewa (Rp)')" />
                                    <x-text-input id="investasi_sewa" class="block mt-1 w-full" type="number" step="0.01" name="investasi[sewa]" :value="old('investasi.sewa')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Pinjaman')" />
                                    <div class="flex items-center gap-6 mt-2">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[pinjaman]" value="1" {{ old('investasi.pinjaman')==='1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Ada</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[pinjaman]" value="0" {{ old('investasi.pinjaman')==='0' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_modal_sendiri" :value="__('Modal Sendiri (Rp)')" />
                                    <x-text-input id="investasi_modal_sendiri" class="block mt-1 w-full" type="number" step="0.01" name="investasi[modal_sendiri]" :value="old('investasi.modal_sendiri')" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Lahan (Status Kepemilikan)')" />
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-2">
                                        @php $ops = ['LHSM','SHRS','SHGB','Girik/Petok']; @endphp
                                        @foreach($ops as $opsi)
                                            <label class="inline-flex items-center gap-2">
                                                <input type="checkbox" name="investasi[lahan_status][]" value="{{ $opsi }}" {{ in_array($opsi, old('investasi.lahan_status', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                <span>{{ $opsi }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="investasi_luas_m2" :value="__('Luas (m2)')" />
                                    <x-text-input id="investasi_luas_m2" class="block mt-1 w-full" type="number" step="0.01" name="investasi[luas_m2]" :value="old('investasi.luas_m2')" />
                                </div>
                                <div>
                                    <x-input-label for="investasi_nilai_bangunan" :value="__('Nilai Bangunan (Rp)')" />
                                    <x-text-input id="investasi_nilai_bangunan" class="block mt-1 w-full" type="number" step="0.01" name="investasi[nilai_bangunan]" :value="old('investasi.nilai_bangunan')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="investasi_bangunan" :value="__('Bangunan (Keterangan)')" />
                                    <x-text-input id="investasi_bangunan" class="block mt-1 w-full" type="text" name="investasi[bangunan]" :value="old('investasi.bangunan')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Sertifikat')" />
                                    <div class="flex items-center gap-6 mt-2">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[sertifikat]" value="IMB" {{ old('investasi.sertifikat')==='IMB' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>IMB</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="investasi[sertifikat]" value="NON_IMB" {{ old('investasi.sertifikat')==='NON_IMB' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span>Non IMB</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Produksi (placeholder aman) -->
                        <div x-show="step===5" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Produksi</h3>
                            <p class="text-slate-600">Form Produksi akan ditambahkan. Anda bisa lanjut ke langkah berikutnya.</p>
                        </div>

                        <!-- Step 6: Tenaga Kerja (placeholder aman) -->
                        <div x-show="step===6" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Tenaga Kerja</h3>
                            <p class="text-slate-600">Form Tenaga Kerja akan ditambahkan. Anda bisa lanjut ke langkah berikutnya.</p>
                        </div>

                        <!-- Step 7: Lampiran (placeholder aman) -->
                        <div x-show="step===7" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Lampiran</h3>
                            <p class="text-slate-600 mb-3">Lampiran akan ditambahkan. Silakan simpan data untuk menyelesaikan.</p>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="px-5 pb-5 flex items-center justify-between">
                        <a href="{{ route('pembudidaya.index') }}" class="text-base text-slate-700 hover:text-slate-900">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-4 py-2 rounded border bg-white hover:bg-gray-50 text-slate-700" @click="if(step>0) step--" x-show="step>0">Sebelumnya</button>
                            <button type="button" class="px-4 py-2 rounded bg-blue-700 hover:bg-blue-800 text-white" @click="if(step<maxStep) step++" x-show="step<maxStep">Berikutnya</button>
                            <x-primary-button x-show="step===maxStep">
                                {{ __('Simpan Data') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
