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

                <form method="POST" action="{{ route('pembudidaya.update', $pembudidaya->id_pembudidaya) }}" class="mt-5">
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
                                        @foreach(['Pembenihan','Pembesaran','Tambak'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_kegiatan_usaha', $pembudidaya->jenis_kegiatan_usaha)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="jenis_budidaya" :value="__('Jenis Budidaya*')" />
                                    <select name="jenis_budidaya" id="jenis_budidaya" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        @foreach(['Kolam','Mina Padi','Keramba','Tambak'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_budidaya', $pembudidaya->jenis_budidaya)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Profil Pemilik -->
                        <div x-show="step===1" x-transition class="p-4 bg-gray-50 rounded border">
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
                                        @foreach ($desas as $desa)
                                            <option value="{{ $desa->id_desa }}" {{ old('id_desa', $pembudidaya->id_desa)==$desa->id_desa ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                        @endforeach
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
                        <div x-show="step===2" x-transition class="p-4 bg-gray-50 rounded border">
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
                        <div x-show="step===3" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Profil Usaha</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="nama_usaha" :value="__('Nama Usaha')" />
                                    <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha', $pembudidaya->nama_usaha)" />
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
                                    <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pembudidaya->tahun_mulai_usaha)" />
                                </div>
                                <div>
                                    <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                    <select name="status_usaha" id="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Aktif" {{ old('status_usaha', $pembudidaya->status_usaha)=='Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Tidak Aktif" {{ old('status_usaha', $pembudidaya->status_usaha)=='Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="lg:col-span-3">
                                    <x-input-label for="alamat_usaha" :value="__('Alamat Usaha')" />
                                    <textarea id="alamat_usaha" name="alamat_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat_usaha', $pembudidaya->alamat_usaha) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Investasi -->
                        <div x-show="step===4" x-transition class="p-4 bg-gray-50 rounded border">
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

                        <!-- Step 5-7 placeholders -->
                        <div x-show="step===5" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Produksi</h3>
                            <p class="text-slate-600">Form Produksi akan ditambahkan.</p>
                        </div>
                        <div x-show="step===6" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Tenaga Kerja</h3>
                            <p class="text-slate-600">Form Tenaga Kerja akan ditambahkan.</p>
                        </div>
                        <div x-show="step===7" x-transition class="p-4 bg-gray-50 rounded border">
                            <h3 class="text-lg font-semibold mb-4">Lampiran</h3>
                            <p class="text-slate-600">Lampiran akan ditambahkan. Simpan data untuk menyelesaikan.</p>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="px-5 pb-5 flex items-center justify-between">
                        <a href="{{ route('pembudidaya.index') }}" class="text-base text-slate-700 hover:text-slate-900">Batal</a>
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
