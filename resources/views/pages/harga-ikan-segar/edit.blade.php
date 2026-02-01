<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Data Harga Ikan Segar') }}
        </h2>
    </x-slot>

    @php
        // Tentukan step awal berdasarkan error pertama
        $stepMap = [
            'id_kecamatan' => 0,
            'id_desa' => 0,
            'tanggal_input' => 1,
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
    @endphp

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div x-data="{ step: {{ $initialStep }}, maxStep: 1 }" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <form method="POST" action="{{ route('harga-ikan-segar.update', $hargaIkanSegar->id_harga) }}">
                    @csrf
                    @method('PUT')

                    <!-- Header -->
                    <div class="bg-blue-600 text-white px-6 py-4">
                        <h2 class="text-xl font-bold">Edit Data Harga Ikan Segar</h2>
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
                        <div x-show="step===1" x-transition class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold mb-4">Detail Ikan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <!-- Jenis Ikan -->
                                <div>
                                    <x-input-label for="jenis_ikan" :value="__('Jenis Ikan*')" />
                                    <x-text-input id="jenis_ikan" class="block mt-1 w-full" type="text" name="jenis_ikan" :value="old('jenis_ikan', $hargaIkanSegar->jenis_ikan)" required placeholder="Contoh: Lele, Nila, Gurame" />
                                    <x-input-error :messages="$errors->get('jenis_ikan')" class="mt-2" />
                                </div>

                                <!-- Ukuran -->
                                <div>
                                    <x-input-label for="ukuran" :value="__('Ukuran')" />
                                    <x-text-input id="ukuran" class="block mt-1 w-full" type="text" name="ukuran" :value="old('ukuran', $hargaIkanSegar->ukuran)" placeholder="Contoh: Kecil, Sedang, Besar, 5-7 cm" />
                                    <x-input-error :messages="$errors->get('ukuran')" class="mt-2" />
                                </div>

                                <!-- Satuan -->
                                <div>
                                    <x-input-label for="satuan" :value="__('Satuan*')" />
                                    <select name="satuan" id="satuan" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="Kg" {{ old('satuan', $hargaIkanSegar->satuan)=='Kg' ? 'selected' : '' }}>Kg (Kilogram)</option>
                                        <option value="Ekor" {{ old('satuan', $hargaIkanSegar->satuan)=='Ekor' ? 'selected' : '' }}>Ekor</option>
                                        <option value="Kwintal" {{ old('satuan', $hargaIkanSegar->satuan)=='Kwintal' ? 'selected' : '' }}>Kwintal</option>
                                        <option value="Ton" {{ old('satuan', $hargaIkanSegar->satuan)=='Ton' ? 'selected' : '' }}>Ton</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
                                </div>

                                <!-- Harga Produsen -->
                                <div>
                                    <x-input-label for="harga_produsen" :value="__('Harga Produsen (Rp)')" />
                                    <x-text-input id="harga_produsen" class="block mt-1 w-full" type="number" name="harga_produsen" :value="old('harga_produsen', $hargaIkanSegar->harga_produsen)" min="0" step="0.01" placeholder="0" />
                                    <x-input-error :messages="$errors->get('harga_produsen')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500">Harga jual dari produsen/pembudidaya</p>
                                </div>

                                <!-- Harga Konsumen -->
                                <div>
                                    <x-input-label for="harga_konsumen" :value="__('Harga Konsumen (Rp)')" />
                                    <x-text-input id="harga_konsumen" class="block mt-1 w-full" type="number" name="harga_konsumen" :value="old('harga_konsumen', $hargaIkanSegar->harga_konsumen)" min="0" step="0.01" placeholder="0" />
                                    <x-input-error :messages="$errors->get('harga_konsumen')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500">Harga beli untuk konsumen akhir</p>
                                </div>

                                <!-- Kuantitas Perminggu -->
                                <div>
                                    <x-input-label for="kuantitas_perminggu" :value="__('Kuantitas Perminggu')" />
                                    <x-text-input id="kuantitas_perminggu" class="block mt-1 w-full" type="number" name="kuantitas_perminggu" :value="old('kuantitas_perminggu', $hargaIkanSegar->kuantitas_perminggu)" min="0" step="0.01" placeholder="0" />
                                    <x-input-error :messages="$errors->get('kuantitas_perminggu')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500">Jumlah ikan per minggu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="px-6 pb-6 flex items-center justify-between border-t border-gray-200 pt-4">
                        <a href="{{ route('harga-ikan-segar.index') }}" class="text-base text-slate-700 hover:text-slate-900 hover:underline">Batal</a>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="if(step>0) step--" x-show="step>0" class="px-5 py-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-slate-700 text-sm font-medium transition">
                                Sebelumnya
                            </button>
                            <button type="button" @click="if(step<maxStep) step++" x-show="step<maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                                Berikutnya
                            </button>
                            <button type="submit" x-show="step===maxStep" class="px-5 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                                {{ __('Simpan Perubahan') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Triple dependent dropdown: Kecamatan -> Desa -> Pasar
        const kecamatanSelect = document.getElementById('id_kecamatan');
        const desaSelect = document.getElementById('id_desa');
        const pasarSelect = document.getElementById('nama_pasar');
        const oldKecamatanValue = '{{ old("id_kecamatan", $hargaIkanSegar->id_kecamatan) }}';
        const oldDesaValue = '{{ old("id_desa", $hargaIkanSegar->id_desa) }}';
        const oldPasarValue = '{{ old("nama_pasar", $hargaIkanSegar->nama_pasar) }}';

        function loadDesaOptions(idKecamatan, selectedDesaId = null) {
            desaSelect.innerHTML = '<option value="">Loading...</option>';
            desaSelect.disabled = true;
            desaSelect.classList.add('bg-gray-100');
            pasarSelect.innerHTML = '<option value="">Pilih Desa Terlebih Dahulu</option>';
            pasarSelect.disabled = true;
            pasarSelect.classList.add('bg-gray-100');

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
                        
                        // Trigger desa change if selected value exists
                        if (selectedDesaId) {
                            desaSelect.dispatchEvent(new Event('change'));
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching desa:', error);
                        desaSelect.innerHTML = '<option value="">Error loading desa</option>';
                    });
            } else {
                desaSelect.innerHTML = '<option value="">Pilih Kecamatan Terlebih Dahulu</option>';
            }
        }

        function loadPasarOptions(idDesa, selectedPasarValue = null) {
            pasarSelect.innerHTML = '<option value="">Loading...</option>';
            pasarSelect.disabled = true;
            pasarSelect.classList.add('bg-gray-100');

            if (idDesa) {
                fetch(`/api/pasar-by-desa/${idDesa}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response content-type:', response.headers.get('content-type'));
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const contentType = response.headers.get('content-type');
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Response bukan JSON, kemungkinan session habis atau error server');
                        }
                        
                        return response.json();
                    })
                    .then(data => {
                        console.log('Pasar data received:', data);
                        pasarSelect.innerHTML = '<option value="">Pilih Pasar</option>';
                        
                        if (data.length === 0) {
                            pasarSelect.innerHTML = '<option value="">Tidak ada pasar aktif</option>';
                        } else {
                            data.forEach(pasar => {
                                const option = document.createElement('option');
                                option.value = pasar.nama_pasar;
                                option.textContent = pasar.nama_pasar;
                                if (selectedPasarValue && selectedPasarValue == pasar.nama_pasar) {
                                    option.selected = true;
                                }
                                pasarSelect.appendChild(option);
                            });
                        }
                        
                        pasarSelect.disabled = false;
                        pasarSelect.classList.remove('bg-gray-100');
                    })
                    .catch(error => {
                        console.error('Error fetching pasar:', error);
                        pasarSelect.innerHTML = '<option value="">Error loading pasar</option>';
                    });
            } else {
                pasarSelect.innerHTML = '<option value="">Pilih Desa Terlebih Dahulu</option>';
            }
        }

        kecamatanSelect.addEventListener('change', function() {
            const idKecamatan = this.value;
            loadDesaOptions(idKecamatan);
        });

        desaSelect.addEventListener('change', function() {
            const idDesa = this.value;
            loadPasarOptions(idDesa);
        });

        // Load desa on page load with current kecamatan
        if (oldKecamatanValue) {
            loadDesaOptions(oldKecamatanValue, oldDesaValue);
        }
    </script>
    @endpush
</x-app-layout>
