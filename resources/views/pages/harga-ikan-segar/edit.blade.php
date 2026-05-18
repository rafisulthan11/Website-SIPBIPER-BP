<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Data Harga Ikan') }}
        </h2>
    </x-slot>

    @php
        // Tentukan step awal berdasarkan error pertama
        $stepMap = [
            'id_kecamatan' => 0,
            'id_desa' => 0,
            'tanggal_input' => 1,
            'nik_pedagang' => 0,
            'jenis_ikan' => 1,
            'harga_produsen' => 1,
            'harga_konsumen' => 1,
            'satuan' => 1,
        ];
        $initialStep = 0;
        if ($errors->any()) {
            foreach ($errors->keys() as $key) {
                if (array_key_exists($key, $stepMap)) { $initialStep = $stepMap[$key]; break; }
            }
        }

        $oldIkanRows = old('ikan', [[
            'jenis_ikan' => $hargaIkanSegar->jenis_ikan,
            'ukuran' => $hargaIkanSegar->ukuran,
            'satuan' => $hargaIkanSegar->satuan,
            'harga_produsen' => $hargaIkanSegar->harga_produsen,
            'harga_konsumen' => $hargaIkanSegar->harga_konsumen,
            'kuantitas_perminggu' => $hargaIkanSegar->kuantitas_perminggu,
        ]]);
    @endphp

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div x-data="{ step: {{ $initialStep }}, maxStep: 1 }" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <form id="hargaIkanEditForm" method="POST" action="{{ route('harga-ikan-segar.update', $hargaIkanSegar->id_harga) }}">
                    @csrf
                    @method('PUT')

                    <!-- Header -->
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Edit Data Harga Ikan</h2>
                    </div>

                    <!-- Sub Title -->
                    <div class="px-6 pt-4 pb-2">
                        <h3 class="text-lg font-semibold text-slate-800">Edit Data Harga</h3>
                    </div>

                    <!-- Tabs -->
                    <div class="px-6 py-3">
                        <div class="flex flex-wrap gap-2">
                            @php $tabs = ['Profil Pasar','Detail Ikan']; @endphp
                            @foreach($tabs as $i => $tab)
                                <button type="button" @click="step={{ $i }}" :class="step==={{ $i }} ? 'bg-blue-600 text-white' : 'bg-white text-slate-700 border border-gray-300'" class="px-4 py-2 rounded text-sm font-medium hover:bg-blue-50 transition">
                                    {{ $tab }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="px-6 pb-6">
                        <!-- Step 0: Profil Pasar -->
                        <div x-show="step===0" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Profil Pasar</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Tahun Pendataan -->
                                <div>
                                    <x-input-label for="tahun_pendataan" :value="__('Tahun Pendataan*')" />
                                    <select id="tahun_pendataan" name="tahun_pendataan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        @php $currentYear = date('Y'); @endphp
                                        @foreach(range($currentYear + 5, 2026) as $year)
                                            <option value="{{ $year }}" {{ old('tahun_pendataan', $hargaIkanSegar->tahun_pendataan ?? $currentYear) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('tahun_pendataan')" class="mt-2" />
                                </div>

                                <!-- Tanggal Input -->
                                <div>
                                    <x-input-label for="tanggal_input" :value="__('Tanggal Input*')" />
                                    <x-text-input id="tanggal_input" class="block mt-1 w-full" type="date" name="tanggal_input" :value="old('tanggal_input', $hargaIkanSegar->tanggal_input)" required />
                                    <x-input-error :messages="$errors->get('tanggal_input')" class="mt-2" />
                                </div>

                                <!-- Nama Pedagang -->
                                <div>
                                    <x-input-label for="nama_pedagang" :value="__('Nama Pedagang*')" />
                                    <x-text-input id="nama_pedagang" class="block mt-1 w-full" type="text" name="nama_pedagang" :value="old('nama_pedagang', $hargaIkanSegar->nama_pedagang)" required placeholder="Nama penjual ikan" />
                                    <x-input-error :messages="$errors->get('nama_pedagang')" class="mt-2" />
                                </div>

                                <!-- NIK Pedagang -->
                                <div>
                                    <x-input-label for="nik_pedagang" :value="__('NIK Pedagang*')" />
                                    <x-text-input id="nik_pedagang" class="block mt-1 w-full" type="text" name="nik_pedagang" :value="old('nik_pedagang', $hargaIkanSegar->nik_pedagang)" required maxlength="16" inputmode="numeric" pattern="[0-9]*" placeholder="16 digit" />
                                    <p class="text-xs text-gray-500 mt-1">Masukkan 16 digit angka.</p>
                                    <x-input-error :messages="$errors->get('nik_pedagang')" class="mt-2" />
                                </div>

                                <!-- Kecamatan -->
                                <div>
                                    <x-input-label for="id_kecamatan" :value="__('Kecamatan*')" />
                                    <select name="id_kecamatan" id="id_kecamatan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach ($kecamatans as $kecamatan)
                                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $hargaIkanSegar->id_kecamatan)==$kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('id_kecamatan')" class="mt-2" />
                                </div>

                                <!-- Desa -->
                                <div>
                                    <x-input-label for="id_desa" :value="__('Desa/Kelurahan*')" />
                                    <select name="id_desa" id="id_desa" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Loading...</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('id_desa')" class="mt-2" />
                                </div>

                                <!-- Nama Pasar -->
                                <div>
                                    <x-input-label for="nama_pasar" :value="__('Nama Pasar*')" />
                                    <select name="nama_pasar" id="nama_pasar" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Loading...</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('nama_pasar')" class="mt-2" />
                                </div>

                                <!-- Asal Ikan -->
                                <div>
                                    <x-input-label for="asal_ikan" :value="__('Asal Ikan (Opsional)')" />
                                    <x-text-input id="asal_ikan" class="block mt-1 w-full" type="text" name="asal_ikan" :value="old('asal_ikan', $hargaIkanSegar->asal_ikan)" placeholder="Contoh: Tambak Wono, Surabaya" />
                                    <x-input-error :messages="$errors->get('asal_ikan')" class="mt-2" />
                                </div>

                                <!-- Keterangan -->
                                <div class="md:col-span-2 lg:col-span-3">
                                    <x-input-label for="keterangan" :value="__('Keterangan/Catatan Pasar')" />
                                    <textarea id="keterangan" name="keterangan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="3" placeholder="Catatan tambahan tentang kondisi pasar atau informasi harga">{{ old('keterangan', $hargaIkanSegar->keterangan) }}</textarea>
                                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Detail Ikan -->
                        <div x-show="step===1" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6" x-data='{ ikanList: @json($oldIkanRows) }'>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Detail Ikan</h3>
                            </div>

                            <x-input-error :messages="$errors->get('ikan')" class="mb-4" />

                            <template x-for="(ikan, index) in ikanList" :key="index">
                                <div class="mb-6 p-4 bg-white rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-semibold text-slate-800">Ikan <span x-text="index + 1"></span></h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <div>
                                            <x-input-label :value="__('Jenis Ikan*')" class="font-semibold" />
                                            <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="text" x-bind:name="'ikan['+index+'][jenis_ikan]'" x-model="ikan.jenis_ikan" required placeholder="Contoh: Lele, Nila, Gurame" />
                                        </div>

                                        <div>
                                            <x-input-label :value="__('Ukuran')" class="font-semibold" />
                                            <select class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" x-bind:name="'ikan['+index+'][ukuran]'" x-model="ikan.ukuran">
                                                <option value="">Pilih Ukuran</option>
                                                <option value="1-20 cm">1-20 cm</option>
                                                <option value="21-40 cm">21-40 cm</option>
                                                <option value="41-60 cm">41-60 cm</option>
                                                <option value="61-80 cm">61-80 cm</option>
                                                <option value="81-100 cm">81-100 cm</option>
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label :value="__('Satuan*')" class="font-semibold" />
                                            <select class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" x-bind:name="'ikan['+index+'][satuan]'" x-model="ikan.satuan" required>
                                                <option value="">Pilih Satuan</option>
                                                <option value="Kg">Kg (Kilogram)</option>
                                                <option value="Ekor">Ekor</option>
                                                <option value="Kwintal">Kwintal</option>
                                                <option value="Ton">Ton</option>
                                            </select>
                                        </div>

                                        <div>
                                            <x-input-label :value="__('Harga Produsen (Rp)*')" class="font-semibold" />
                                            <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" x-bind:name="'ikan['+index+'][harga_produsen]'" x-model="ikan.harga_produsen" min="0" step="0.01" placeholder="0" required />
                                            <p class="mt-1 text-xs text-gray-500">Harga jual dari produsen/pembudidaya</p>
                                        </div>

                                        <div>
                                            <x-input-label :value="__('Harga Konsumen (Rp)*')" class="font-semibold" />
                                            <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" x-bind:name="'ikan['+index+'][harga_konsumen]'" x-model="ikan.harga_konsumen" min="0" step="0.01" placeholder="0" required />
                                            <p class="mt-1 text-xs text-gray-500">Harga beli untuk konsumen akhir</p>
                                        </div>

                                        <div>
                                            <x-input-label :value="__('Kuantitas Perminggu*')" class="font-semibold" />
                                            <input class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="number" x-bind:name="'ikan['+index+'][kuantitas_perminggu]'" x-model="ikan.kuantitas_perminggu" min="0" step="0.01" placeholder="0" required />
                                            <p class="mt-1 text-xs text-gray-500">Jumlah ikan per minggu</p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="px-6 pb-6 flex items-center justify-between border-t border-gray-200 pt-4">
                        <a href="{{ route('harga-ikan-segar.index') }}" class="text-base text-slate-700 hover:text-slate-900 hover:underline">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="if(step>0) step--" x-show="step>0" class="px-5 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-slate-700 text-sm font-medium transition">
                                Sebelumnya
                            </button>
                            <button type="button" @click="step++" x-show="step<maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                                Berikutnya
                            </button>
                            <button type="submit" x-show="step===maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition shadow-sm">
                                Perbarui Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('hargaIkanEditForm');
            const kecamatanSelect = document.getElementById('id_kecamatan');
            const desaSelect = document.getElementById('id_desa');
            const pasarSelect = document.getElementById('nama_pasar');

            if (!form || !kecamatanSelect || !desaSelect || !pasarSelect) {
                return;
            }

            const oldKecamatanValue = @json(old('id_kecamatan', $hargaIkanSegar->id_kecamatan));
            const oldDesaValue = @json(old('id_desa', $hargaIkanSegar->id_desa));
            const oldPasarValue = @json(old('nama_pasar', $hargaIkanSegar->nama_pasar));

            function loadDesaOptions(idKecamatan, selectedDesaId = null) {
                desaSelect.innerHTML = '<option value="">Loading...</option>';
                desaSelect.disabled = true;
                desaSelect.classList.add('bg-gray-100');

                pasarSelect.innerHTML = '<option value="">Pilih Desa Terlebih Dahulu</option>';
                pasarSelect.disabled = true;
                pasarSelect.classList.add('bg-gray-100');

                if (!idKecamatan) {
                    desaSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
                    return;
                }

                fetch(`/api/desa-by-kecamatan/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        desaSelect.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa;
                            option.textContent = desa.nama_desa;
                            if (selectedDesaId && String(selectedDesaId) === String(desa.id_desa)) {
                                option.selected = true;
                            }
                            desaSelect.appendChild(option);
                        });
                        desaSelect.disabled = false;
                        desaSelect.classList.remove('bg-gray-100');

                        if (selectedDesaId) {
                            loadPasarOptions(selectedDesaId, oldPasarValue || null);
                        }
                    })
                    .catch(() => {
                        desaSelect.innerHTML = '<option value="">Gagal memuat desa</option>';
                    });
            }

            function loadPasarOptions(idDesa, selectedPasarValue = null) {
                pasarSelect.innerHTML = '<option value="">Loading...</option>';
                pasarSelect.disabled = true;
                pasarSelect.classList.add('bg-gray-100');

                if (!idDesa) {
                    pasarSelect.innerHTML = '<option value="">Pilih Desa Terlebih Dahulu</option>';
                    return;
                }

                fetch(`/api/pasar-by-desa/${idDesa}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load pasar');
                        }
                        return response.json();
                    })
                    .then(data => {
                        pasarSelect.innerHTML = '<option value="">Pilih Pasar</option>';

                        if (!Array.isArray(data) || data.length === 0) {
                            pasarSelect.innerHTML = '<option value="">Tidak ada pasar aktif</option>';
                            return;
                        }

                        data.forEach(pasar => {
                            const option = document.createElement('option');
                            option.value = pasar.nama_pasar;
                            option.textContent = pasar.nama_pasar;
                            if (selectedPasarValue && String(selectedPasarValue) === String(pasar.nama_pasar)) {
                                option.selected = true;
                            }
                            pasarSelect.appendChild(option);
                        });

                        pasarSelect.disabled = false;
                        pasarSelect.classList.remove('bg-gray-100');
                    })
                    .catch(() => {
                        pasarSelect.innerHTML = '<option value="">Gagal memuat pasar</option>';
                    });
            }

            kecamatanSelect.addEventListener('change', function() {
                loadDesaOptions(this.value, null);
            });

            desaSelect.addEventListener('change', function() {
                loadPasarOptions(this.value, null);
            });

            if (oldKecamatanValue) {
                loadDesaOptions(oldKecamatanValue, oldDesaValue || null);
            } else {
                desaSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
                pasarSelect.innerHTML = '<option value="">Pilih Desa Terlebih Dahulu</option>';
            }
        });
    </script>
    @endpush
</x-app-layout>
