<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Pengolah: ') . $pengolah->nama_lengkap }}
        </h2>
    </x-slot>

    @php
        $stepMap = [
            'jenis_kegiatan_usaha' => 0,
            'nama_lengkap' => 1,
            'nik_pengolah' => 1,
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
            <div x-data="{ step: {{ $initialStep }}, maxStep: 5 }" x-init="console.log('Alpine initialized. Step:', step, 'MaxStep:', maxStep)" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <!-- Header -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h2 class="text-xl font-bold">Data Pengolah</h2>
                </div>
                
                <!-- Edit Title -->
                <div class="px-6 pt-4 pb-2">
                    <h3 class="text-lg font-semibold text-slate-800">Edit Pengolah</h3>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-3">
                    <div class="flex flex-wrap gap-2">
                        @php $tabs = ['Jenis Usaha','Profil Pemilik','Profil Usaha','Produksi','Tenaga Kerja','Lampiran']; @endphp
                        @foreach($tabs as $i => $tab)
                            <button type="button" @click="step={{ $i }}" :class="step==={{ $i }} ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-gray-300'" class="px-4 py-2 rounded text-sm font-medium hover:bg-blue-50 transition">
                                {{ $tab }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('pengolah.update', $pengolah->id_pengolah) }}" enctype="multipart/form-data" novalidate>
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
                                        <option value="Pengalengan" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pengalengan' ? 'selected' : '' }}>Pengalengan</option>
                                        <option value="Pembekuan" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pembekuan' ? 'selected' : '' }}>Pembekuan</option>
                                        <option value="Penggaraman/Pengeringan" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Penggaraman/Pengeringan' ? 'selected' : '' }}>Penggaraman/Pengeringan</option>
                                        <option value="Pemindangan" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pemindangan' ? 'selected' : '' }}>Pemindangan</option>
                                        <option value="Pengasapan/Pemanggangan" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pengasapan/Pemanggangan' ? 'selected' : '' }}>Pengasapan/Pemanggangan</option>
                                        <option value="Fermentasi/Peragian" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Fermentasi/Peragian' ? 'selected' : '' }}>Fermentasi/Peragian</option>
                                        <option value="Pereduksian/Ekstraksi" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pereduksian/Ekstraksi' ? 'selected' : '' }}>Pereduksian/Ekstraksi</option>
                                        <option value="Pelumatan Daging/Surimi" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pelumatan Daging/Surimi' ? 'selected' : '' }}>Pelumatan Daging/Surimi</option>
                                        <option value="Penanganan Produk Segar/Dingin" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Penanganan Produk Segar/Dingin' ? 'selected' : '' }}>Penanganan Produk Segar/Dingin</option>
                                        <option value="Pengolahan Lainnya" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Pengolahan Lainnya' ? 'selected' : '' }}>Pengolahan Lainnya</option>
                                        <option value="Non Konsumsi/Ikan Hias" {{ old('jenis_kegiatan_usaha', $pengolah->jenis_kegiatan_usaha)=='Non Konsumsi/Ikan Hias' ? 'selected' : '' }}>Non Konsumsi/Ikan Hias</option>
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
                                    <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap', $pengolah->nama_lengkap)" required />
                                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="nik_pengolah" :value="__('NIK (Sesuai KTP)*')" />
                                    <x-text-input id="nik_pengolah" class="block mt-1 w-full" type="text" name="nik_pengolah" :value="old('nik_pengolah', $pengolah->nik_pengolah)" required />
                                    <x-input-error :messages="$errors->get('nik_pengolah')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $pengolah->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $pengolah->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                                    <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir', $pengolah->tempat_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                                    <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir', $pengolah->tanggal_lahir)" />
                                </div>
                                <div>
                                    <x-input-label for="status_perkawinan" :value="__('Status Perkawinan')" />
                                    <select name="status_perkawinan" id="status_perkawinan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Status</option>
                                        @foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $opt)
                                            <option value="{{ $opt }}" {{ old('status_perkawinan', $pengolah->status_perkawinan)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="aset_pribadi" :value="__('Aset Pribadi (Rp)')" />
                                    <div class="mt-1">
                                        <x-text-input id="aset_pribadi" class="block w-full" type="number" name="aset_pribadi" :value="old('aset_pribadi', $pengolah->aset_pribadi)" min="0" step="0.01" />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="pendidikan_terakhir" :value="__('Pendidikan Terakhir')" />
                                    <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Pilih Pendidikan</option>
                                        @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $opt)
                                            <option value="{{ $opt }}" {{ old('pendidikan_terakhir', $pengolah->pendidikan_terakhir)===$opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                    <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pengolah->tahun_mulai_usaha)" min="1900" max="2099" />
                                </div>
                                <div class="relative">
                                    <x-input-label for="jumlah_tanggungan" :value="__('Jumlah Tanggungan')" />
                                    <div class="mt-1 flex items-center gap-3">
                                        <div class="w-full">
                                            <x-text-input id="jumlah_tanggungan" class="block w-full" type="number" name="jumlah_tanggungan" :value="old('jumlah_tanggungan', $pengolah->jumlah_tanggungan)" min="0" />
                                        </div>
                                        <p class="text-xs text-gray-500 whitespace-nowrap absolute left-full ml-3 top-[2.25rem]">(jumlah anggota keluarga yang ditanggung, tidak termasuk diri sendiri)</p>
                                    </div>
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="alamat" :value="__('Alamat Lengkap (Sesuai KTP)')" />
                                    <textarea id="alamat" name="alamat" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat', $pengolah->alamat) }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $pengolah->id_kecamatan)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
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
                                    <x-input-label for="kontak" :value="__('No. Telepon / HP')" />
                                    <x-text-input id="kontak" class="block mt-1 w-full" type="text" name="kontak" :value="old('kontak', $pengolah->kontak)" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $pengolah->email)" />
                                </div>
                                <div>
                                    <x-input-label for="no_npwp" :value="__('No. NPWP')" />
                                    <x-text-input id="no_npwp" class="block mt-1 w-full" type="text" name="no_npwp" :value="old('no_npwp', $pengolah->no_npwp)" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Profil Usaha -->
                        <div x-show="step===2" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Profil Usaha</h3>
                            
                            <!-- Informasi Umum Section -->
                            <div class="mb-6">
                                <h4 class="text-base font-semibold text-slate-700 mb-4">Informasi Umum</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div>
                                        <x-input-label for="nama_usaha" :value="__('Nama Usaha')" />
                                        <x-text-input id="nama_usaha" class="block mt-1 w-full" type="text" name="nama_usaha" :value="old('nama_usaha', $pengolah->nama_usaha)" />
                                    </div>
                                    <div>
                                        <x-input-label for="nama_kelompok" :value="__('Nama Kelompok (opsional)')" />
                                        <x-text-input id="nama_kelompok" class="block mt-1 w-full" type="text" name="nama_kelompok" :value="old('nama_kelompok', $pengolah->nama_kelompok)" />
                                    </div>
                                    <div>
                                        <x-input-label for="komoditas" :value="__('Komoditas')" />
                                        <select id="komoditas" name="komoditas" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Komoditas</option>
                                            @foreach($komoditas as $item)
                                                <option value="{{ $item->nama_komoditas }}" {{ old('komoditas', $pengolah->komoditas) == $item->nama_komoditas ? 'selected' : '' }}>{{ $item->nama_komoditas }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('komoditas')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="skala_usaha" :value="__('Skala Usaha')" />
                                        <select id="skala_usaha" name="skala_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Skala Usaha</option>
                                            <option value="Mikro" {{ old('skala_usaha', $pengolah->skala_usaha) == 'Mikro' ? 'selected' : '' }}>Mikro</option>
                                            <option value="Kecil" {{ old('skala_usaha', $pengolah->skala_usaha) == 'Kecil' ? 'selected' : '' }}>Kecil</option>
                                            <option value="Menengah" {{ old('skala_usaha', $pengolah->skala_usaha) == 'Menengah' ? 'selected' : '' }}>Menengah</option>
                                            <option value="Besar" {{ old('skala_usaha', $pengolah->skala_usaha) == 'Besar' ? 'selected' : '' }}>Besar</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('skala_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="status_usaha" :value="__('Status Usaha')" />
                                        <select id="status_usaha" name="status_usaha" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Pilih Status Usaha</option>
                                            <option value="Aktif" {{ old('status_usaha', $pengolah->status_usaha) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Tidak Aktif" {{ old('status_usaha', $pengolah->status_usaha) == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status_usaha')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="tahun_mulai_usaha" :value="__('Tahun Mulai Usaha')" />
                                        <x-text-input id="tahun_mulai_usaha" class="block mt-1 w-full" type="number" name="tahun_mulai_usaha" :value="old('tahun_mulai_usaha', $pengolah->tahun_mulai_usaha)" placeholder="Contoh: 2020" />
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
                                                <option value="{{ $kecamatan->id_kecamatan }}" {{ old('kecamatan_usaha', $pengolah->id_kecamatan_usaha)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
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
                                        <textarea id="alamat_lengkap_usaha" name="alamat_lengkap_usaha" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat_lengkap_usaha', $pengolah->alamat_usaha) }}</textarea>
                                    </div>
                                </div>

                                <!-- Peta Lokasi Usaha -->
                                <div class="mb-4">
                                    <x-input-label :value="__('Peta Lokasi Usaha')" class="mb-2" />
                                    <p class="text-sm text-slate-600 mb-3">Klik pada peta untuk menandai lokasi usaha Anda atau izinkan akses lokasi browser agar tidak otomatis mengikuti posisi Anda.</p>
                                    
                                    <!-- Tombol Gunakan Lokasi Saya -->
                                    <button type="button" id="btnLokasiSayaPengolahEdit" class="mb-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition">
                                        Gunakan Lokasi Saya
                                    </button>

                                    <!-- Peta Interaktif -->
                                    <div id="mapUsahaPengolahEdit" class="w-full h-64 bg-gray-200 rounded-md border border-gray-300 mb-3 relative overflow-hidden z-0"></div>

                                    <!-- Input Latitude & Longitude -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <x-input-label for="latitude_usaha" :value="__('Latitude')" />
                                            <x-text-input id="latitude_usaha" class="block mt-1 w-full" type="text" name="latitude_usaha" :value="old('latitude_usaha', $pengolah->latitude)" placeholder="-8.188723" />
                                            <p class="text-xs text-slate-500 mt-1">Akan terisi otomatis saat memilih lokasi di peta</p>
                                        </div>
                                        <div>
                                            <x-input-label for="longitude_usaha" :value="__('Longitude')" />
                                            <x-text-input id="longitude_usaha" class="block mt-1 w-full" type="text" name="longitude_usaha" :value="old('longitude_usaha', $pengolah->longitude)" placeholder="113.688576" />
                                            <p class="text-xs text-slate-500 mt-1">Akan terisi otomatis saat memilih lokasi di peta</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Produksi -->
                        <div x-show="step===3" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6" x-data="{ 
                            products: {{ json_encode($pengolah->produksi_data ?? [[]]) }}, 
                            materials: {{ json_encode(collect($pengolah->produksi_data ?? [[]])->map(fn($p) => $p['bahan_baku'] ?? [[]]) ?? [[[]]]) }} 
                        }">
                            <h3 class="text-lg font-semibold mb-6">Produksi</h3>
                            
                            <template x-for="(product, index) in products" :key="index">
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-300">
                                        <h4 class="text-base font-semibold text-slate-800" x-text="'Produk ' + (index + 1)"></h4>
                                        <div class="flex gap-2">
                                            <button type="button" @click="products.push({}); materials.push([{}])" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Tambah Produk
                                            </button>
                                            <button type="button" @click="products.splice(index, 1); materials.splice(index, 1)" x-show="products.length > 1" class="px-3 py-1.5 text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded transition">Hapus Produk</button>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-6">
                                        <!-- Informasi Produk -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <x-input-label :value="__('Nama Merk')" class="font-semibold" />
                                                <input class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="text" x-bind:name="'produksi['+index+'][nama_merk]'" x-model="product.nama_merk" />
                                            </div>

                                            <div>
                                                <x-input-label :value="__('Periode')" class="font-semibold" />
                                                <select class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" x-bind:name="'produksi['+index+'][periode]'" x-model="product.periode">
                                                    <option value="">Pilih Periode</option>
                                                    <option value="Tahunan">Tahunan</option>
                                                    <option value="Bulanan">Bulanan</option>
                                                    <option value="Harian">Harian</option>
                                                </select>
                                            </div>

                                            <div>
                                                <x-input-label :value="__('Kapasitas Terpasang')" class="font-semibold" />
                                                <div class="flex items-center gap-2 mt-2">
                                                    <input class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][kapasitas_terpasang]'" x-model="product.kapasitas_terpasang" step="0.01" placeholder="0.00" />
                                                    <span class="text-sm text-gray-600 font-medium whitespace-nowrap">Kg</span>
                                                </div>
                                            </div>

                                            <div>
                                                <x-input-label :value="__('Jumlah Hari Produksi/bulan')" class="font-semibold" />
                                                <div class="flex items-center gap-2 mt-2">
                                                    <input class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][jumlah_hari_produksi]'" x-model="product.jumlah_hari_produksi" min="0" placeholder="0" />
                                                    <span class="text-sm text-gray-600 font-medium whitespace-nowrap">hari</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bulan Produksi -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <x-input-label :value="__('Bulan Produksi')" class="font-semibold" />
                                            <div class="grid grid-cols-6 md:grid-cols-12 gap-3 mt-3">
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="1" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('1')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">1</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="2" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('2')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">2</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="3" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('3')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">3</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="4" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('4')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">4</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="5" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('5')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">5</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="6" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('6')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">6</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="7" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('7')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">7</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="8" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('8')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">8</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="9" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('9')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">9</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="10" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('10')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">10</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="11" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('11')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">11</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][bulan_produksi][]'" value="12" x-bind:checked="product.bulan_produksi && product.bulan_produksi.includes('12')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">12</span></label>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2 italic">(Centang bulan yang dipilih)</p>
                                        </div>

                                        <!-- Sertifikat Lahan -->
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <x-input-label :value="__('Sertifikat Lahan')" class="font-semibold" />
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3">
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="HACCP" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('HACCP')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">HACCP</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="PIRT" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('PIRT')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">PIRT</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="SNI" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('SNI')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">SNI</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="MD" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('MD')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">MD</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="HALAL" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('HALAL')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">HALAL</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="ML" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('ML')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">ML</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="SKP" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('SKP')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">SKP</span></label>
                                                <label class="flex items-center cursor-pointer"><input type="checkbox" x-bind:name="'produksi['+index+'][sertifikat_lahan][]'" value="SP" x-bind:checked="product.sertifikat_lahan && product.sertifikat_lahan.includes('SP')" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">SP</span></label>
                                            </div>
                                        </div>

                                        <!-- Informasi Biaya dan Harga -->
                                        <div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label :value="__('Biaya Produksi')" class="font-semibold" />
                                                    <div class="relative mt-2">
                                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp</span>
                                                        <input class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][biaya_produksi]'" x-model="product.biaya_produksi" step="0.01" placeholder="0.00" />
                                                    </div>
                                                </div>

                                                <div>
                                                    <x-input-label :value="__('Biaya lain-lain')" class="font-semibold" />
                                                    <div class="relative mt-2">
                                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp</span>
                                                        <input class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][biaya_lain]'" x-model="product.biaya_lain" step="0.01" placeholder="0.00" />
                                                    </div>
                                                </div>

                                                <div>
                                                    <x-input-label :value="__('Harga Jual')" class="font-semibold" />
                                                    <div class="relative mt-2">
                                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp</span>
                                                        <input class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][harga_jual]'" x-model="product.harga_jual" step="0.01" placeholder="0.00" />
                                                    </div>
                                                </div>

                                                <div>
                                                    <x-input-label :value="__('Harga Produksi')" class="font-semibold" />
                                                    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2 mt-2">
                                                        <input class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][harga_produksi_qty]'" x-model="product.harga_produksi_qty" step="0.01" placeholder="0.00" />
                                                        <span class="text-sm text-gray-600 font-medium whitespace-nowrap">Kg</span>
                                                        <div class="relative">
                                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp</span>
                                                            <input class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][harga_produksi_harga]'" x-model="product.harga_produksi_harga" step="0.01" placeholder="0.00" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bahan Baku Section -->
                                        <div>
                                            <div class="flex justify-between items-center mb-4">
                                                <h5 class="font-semibold text-slate-800 text-base">Bahan Baku</h5>
                                                <button type="button" @click="if (!materials[index]) materials[index] = []; materials[index].push({})" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition shadow-sm">+ Tambah Bahan Baku</button>
                                            </div>

                                            <template x-for="(material, mIndex) in materials[index]" :key="mIndex">
                                                <div class="mb-4 p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <x-input-label :value="__('Bahan')" class="font-semibold" />
                                                            <input class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="text" x-bind:name="'produksi['+index+'][bahan_baku]['+mIndex+'][bahan]'" x-model="material.bahan" placeholder="Nama bahan" />
                                                        </div>
                                                        <div>
                                                            <x-input-label :value="__('Asal Bahan Baku')" class="font-semibold" />
                                                            <input class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="text" x-bind:name="'produksi['+index+'][bahan_baku]['+mIndex+'][asal]'" x-model="material.asal" placeholder="Asal/sumber bahan" />
                                                        </div>
                                                        <div>
                                                            <x-input-label :value="__('Harga Bahan Baku')" class="font-semibold" />
                                                            <input class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][bahan_baku]['+mIndex+'][harga]'" x-model="material.harga" step="0.01" placeholder="0.00" />
                                                        </div>
                                                        <div>
                                                            <x-input-label :value="__('Qty (kg)*')" class="font-semibold" />
                                                            <input class="block mt-2 w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][bahan_baku]['+mIndex+'][qty]'" x-model="material.qty" step="0.01" placeholder="0.00" />
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex justify-end">
                                                        <button type="button" @click="materials[index].splice(mIndex, 1)" x-show="materials[index] && materials[index].length > 1" class="px-3 py-1.5 text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded transition">Hapus Bahan</button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Pemasaran Section -->
                                        <div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="md:col-span-2">
                                                    <x-input-label :value="__('Pemasaran')" class="font-semibold" />
                                                    <input class="block mt-2 w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="text" x-bind:name="'produksi['+index+'][pemasaran]'" x-model="product.pemasaran" placeholder="Wilayah/metode pemasaran" />
                                                </div>
                                                <div>
                                                    <x-input-label :value="__('Jumlah Produk*')" class="font-semibold" />
                                                    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2 mt-2">
                                                        <input class="block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][jumlah_produk_qty]'" x-model="product.jumlah_produk_qty" step="0.01" placeholder="0.00" />
                                                        <span class="text-sm text-gray-600 font-medium whitespace-nowrap">Kg</span>
                                                        <div class="flex items-center gap-2">
                                                            <input class="block w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][jumlah_produk_pack]'" x-model="product.jumlah_produk_pack" placeholder="0" />
                                                            <span class="text-sm text-gray-600 font-medium whitespace-nowrap">pack</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <x-input-label :value="__('Harga Jual/pack')" class="font-semibold" />
                                                    <div class="relative mt-2">
                                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-gray-600 font-medium pointer-events-none">Rp</span>
                                                        <input class="block w-full pl-10 border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" type="number" x-bind:name="'produksi['+index+'][harga_jual_pack]'" x-model="product.harga_jual_pack" step="0.01" placeholder="0.00" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Step 4: Tenaga Kerja -->
                        <div x-show="step===4" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
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
                                            <input id="wni_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_laki_tetap" value="{{ old('tenaga_kerja_wni_laki_tetap', isset($pengolah->tenaga_kerja_data['wni_laki_tetap']) ? $pengolah->tenaga_kerja_data['wni_laki_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_laki_tidak_tetap" value="{{ old('tenaga_kerja_wni_laki_tidak_tetap', isset($pengolah->tenaga_kerja_data['wni_laki_tidak_tetap']) ? $pengolah->tenaga_kerja_data['wni_laki_tidak_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_laki_keluarga" value="{{ old('tenaga_kerja_wni_laki_keluarga', isset($pengolah->tenaga_kerja_data['wni_laki_keluarga']) ? $pengolah->tenaga_kerja_data['wni_laki_keluarga'] : 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Perempuan -->
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wni_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wni_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_perempuan_tetap" value="{{ old('tenaga_kerja_wni_perempuan_tetap', isset($pengolah->tenaga_kerja_data['wni_perempuan_tetap']) ? $pengolah->tenaga_kerja_data['wni_perempuan_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wni_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_perempuan_tidak_tetap" value="{{ old('tenaga_kerja_wni_perempuan_tidak_tetap', isset($pengolah->tenaga_kerja_data['wni_perempuan_tidak_tetap']) ? $pengolah->tenaga_kerja_data['wni_perempuan_tidak_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wni_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wni_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wni_perempuan_keluarga" value="{{ old('tenaga_kerja_wni_perempuan_keluarga', isset($pengolah->tenaga_kerja_data['wni_perempuan_keluarga']) ? $pengolah->tenaga_kerja_data['wni_perempuan_keluarga'] : 0) }}" min="0" />
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
                                            <input id="wna_laki_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_laki_tetap" value="{{ old('tenaga_kerja_wna_laki_tetap', isset($pengolah->tenaga_kerja_data['wna_laki_tetap']) ? $pengolah->tenaga_kerja_data['wna_laki_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_laki_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_laki_tidak_tetap" value="{{ old('tenaga_kerja_wna_laki_tidak_tetap', isset($pengolah->tenaga_kerja_data['wna_laki_tidak_tetap']) ? $pengolah->tenaga_kerja_data['wna_laki_tidak_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_laki_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_laki_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_laki_keluarga" value="{{ old('tenaga_kerja_wna_laki_keluarga', isset($pengolah->tenaga_kerja_data['wna_laki_keluarga']) ? $pengolah->tenaga_kerja_data['wna_laki_keluarga'] : 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Perempuan -->
                                <div>
                                    <h5 class="font-medium text-slate-700 mb-3">Perempuan</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <x-input-label for="wna_perempuan_tetap" :value="__('Tetap :')" />
                                            <input id="wna_perempuan_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_perempuan_tetap" value="{{ old('tenaga_kerja_wna_perempuan_tetap', isset($pengolah->tenaga_kerja_data['wna_perempuan_tetap']) ? $pengolah->tenaga_kerja_data['wna_perempuan_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_tidak_tetap" :value="__('Tidak tetap :')" />
                                            <input id="wna_perempuan_tidak_tetap" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_perempuan_tidak_tetap" value="{{ old('tenaga_kerja_wna_perempuan_tidak_tetap', isset($pengolah->tenaga_kerja_data['wna_perempuan_tidak_tetap']) ? $pengolah->tenaga_kerja_data['wna_perempuan_tidak_tetap'] : 0) }}" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="wna_perempuan_keluarga" :value="__('Keluarga :')" />
                                            <input id="wna_perempuan_keluarga" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" name="tenaga_kerja_wna_perempuan_keluarga" value="{{ old('tenaga_kerja_wna_perempuan_keluarga', isset($pengolah->tenaga_kerja_data['wna_perempuan_keluarga']) ? $pengolah->tenaga_kerja_data['wna_perempuan_keluarga'] : 0) }}" min="0" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Lampiran -->
                        <div x-show="step===5" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Lampiran</h3>
                            <p class="text-slate-600 mb-6">Unggah dokumentasi berikut (format: JPG, PNG, PDF. Maksimal 2MB per file)</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Foto KTP -->
                                <div>
                                    <x-input-label for="foto_ktp" :value="__('Foto KTP')" />
                                    @if($pengolah->foto_ktp)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_ktp) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_ktp')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat -->
                                <div>
                                    <x-input-label for="foto_sertifikat" :value="__('Foto Sertifikat')" />
                                    @if($pengolah->foto_sertifikat)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_sertifikat) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat" id="foto_sertifikat" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat')" class="mt-2" />
                                </div>

                                <!-- Foto CPIB/CBIB -->
                                <div>
                                    <x-input-label for="foto_cpib_cbib" :value="__('Foto CPIB/CBIB')" />
                                    @if($pengolah->foto_cpib_cbib)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_cpib_cbib) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_cpib_cbib" id="foto_cpib_cbib" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_cpib_cbib')" class="mt-2" />
                                </div>

                                <!-- Foto Unit Usaha -->
                                <div>
                                    <x-input-label for="foto_unit_usaha" :value="__('Foto Unit Usaha')" />
                                    @if($pengolah->foto_unit_usaha)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_unit_usaha) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_unit_usaha" id="foto_unit_usaha" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_unit_usaha')" class="mt-2" />
                                </div>

                                <!-- Foto KUSUKA -->
                                <div>
                                    <x-input-label for="foto_kusuka" :value="__('Foto KUSUKA')" />
                                    @if($pengolah->foto_kusuka)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_kusuka) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_kusuka" id="foto_kusuka" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_kusuka')" class="mt-2" />
                                </div>

                                <!-- Foto NIB -->
                                <div>
                                    <x-input-label for="foto_nib" :value="__('Foto NIB')" />
                                    @if($pengolah->foto_nib)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_nib) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_nib" id="foto_nib" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_nib')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat PIRT -->
                                <div>
                                    <x-input-label for="foto_sertifikat_pirt" :value="__('Foto Sertifikat PIRT')" />
                                    @if($pengolah->foto_sertifikat_pirt)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_sertifikat_pirt) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat_pirt" id="foto_sertifikat_pirt" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat_pirt')" class="mt-2" />
                                </div>

                                <!-- Foto Sertifikat Halal -->
                                <div>
                                    <x-input-label for="foto_sertifikat_halal" :value="__('Foto Sertifikat Halal')" />
                                    @if($pengolah->foto_sertifikat_halal)
                                        <p class="text-sm text-gray-600 mb-2">File saat ini: <a href="{{ asset('storage/' . $pengolah->foto_sertifikat_halal) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Lihat File</a></p>
                                    @endif
                                    <input type="file" name="foto_sertifikat_halal" id="foto_sertifikat_halal" accept="image/*,.pdf" class="block mt-1 w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md">
                                    <x-input-error :messages="$errors->get('foto_sertifikat_halal')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation buttons -->
                    <div class="px-6 pb-6 flex items-center justify-between border-t border-gray-200 pt-4" style="position: relative; z-index: 10;">
                        <a href="{{ route('pengolah.index') }}" class="text-base text-slate-700 hover:text-slate-900 hover:underline">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" class="px-5 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-slate-700 text-sm font-medium transition" @click="if(step>0) step--" x-show="step>0">Sebelumnya</button>
                            <button type="button" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition" @click="if(step<maxStep) step++" x-show="step<maxStep">Berikutnya</button>
                            <button type="submit" x-show="step===maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition" style="cursor: pointer; pointer-events: auto;">
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
        // Fallback untuk memastikan tombol submit selalu bisa diklik
        document.addEventListener('DOMContentLoaded', function() {
            // Check jika Alpine.js tidak load
            setTimeout(function() {
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn && window.getComputedStyle(submitBtn).display === 'none') {
                    console.warn('Alpine.js mungkin tidak ter-load. Menampilkan tombol submit.');
                    submitBtn.style.display = 'inline-block';
                }
            }, 1000);
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Tunggu sedikit untuk memastikan DOM dan Leaflet sudah siap
            setTimeout(function() {
                // Cek apakah elemen peta ada
                const mapElement = document.getElementById('mapUsahaPengolahEdit');
                if (!mapElement) {
                    console.error('Map element not found');
                    return;
                }

                // Ambil nilai existing dari database
                const existingLat = {{ $pengolah->latitude ?? -8.188723 }};
                const existingLng = {{ $pengolah->longitude ?? 113.688576 }};
                
                // Inisialisasi peta dengan lokasi existing atau default Jember
                const map = L.map('mapUsahaPengolahEdit').setView([existingLat, existingLng], 13);
            
            // Tambahkan tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Marker yang bisa dipindah
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
            const btnLokasiSaya = document.getElementById('btnLokasiSayaPengolahEdit');
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
                const latInput = document.getElementById('latitude_usaha');
                const lngInput = document.getElementById('longitude_usaha');
                
                if (latInput) {
                    latInput.addEventListener('change', function() {
                        const lat = parseFloat(this.value);
                        const lng = parseFloat(lngInput.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng]);
                        }
                    });
                }
                
                if (lngInput) {
                    lngInput.addEventListener('change', function() {
                        const lat = parseFloat(latInput.value);
                        const lng = parseFloat(this.value);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng]);
                        }
                    });
                }

                // Force map to refresh setelah init
                setTimeout(function() {
                    map.invalidateSize();
                }, 100);

                // Refresh peta saat tab Profil Usaha dibuka (step 2)
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.attributeName === 'style') {
                            const isVisible = mapElement.offsetParent !== null;
                            if (isVisible) {
                                setTimeout(function() {
                                    map.invalidateSize();
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
        const oldKecamatanValue = '{{ old("id_kecamatan", $pengolah->id_kecamatan) }}';
        const oldDesaValue = '{{ old("id_desa", $pengolah->id_desa) }}';

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
        const oldKecamatanUsahaValue = '{{ old("kecamatan_usaha", $pengolah->id_kecamatan_usaha) }}';
        const oldDesaUsahaValue = '{{ old("desa_usaha", $pengolah->id_desa_usaha) }}';

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
