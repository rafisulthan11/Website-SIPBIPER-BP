<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Tambah Data Pasar') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-md shadow-md">
                <div class="p-5 sm:p-6">
                    <form action="{{ route('pasar.store') }}" method="POST">
                        @csrf

                        <!-- Nama Pasar -->
                        <div class="mb-5">
                            <label for="nama_pasar" class="block mb-2 text-base font-medium text-gray-900">
                                Nama Pasar <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_pasar" name="nama_pasar" value="{{ old('nama_pasar') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan nama pasar">
                            @error('nama_pasar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div class="mb-5">
                            <label for="id_kecamatan" class="block mb-2 text-base font-medium text-gray-900">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <select id="id_kecamatan" name="id_kecamatan" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatans as $kecamatan)
                                    <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan') == $kecamatan->id_kecamatan ? 'selected' : '' }}>{{ $kecamatan->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                            @error('id_kecamatan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desa -->
                        <div class="mb-5">
                            <label for="id_desa" class="block mb-2 text-base font-medium text-gray-900">
                                Desa <span class="text-red-500">*</span>
                            </label>
                            <select id="id_desa" name="id_desa" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" disabled>
                                <option value="">Pilih Kecamatan Terlebih Dahulu</option>
                            </select>
                            @error('id_desa')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-5">
                            <label for="alamat" class="block mb-2 text-base font-medium text-gray-900">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alamat" name="alamat" rows="3" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan alamat pasar">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Pasar -->
                        <div class="mb-5">
                            <label for="kode_pasar" class="block mb-2 text-base font-medium text-gray-900">
                                Kode Pasar <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kode_pasar" name="kode_pasar" value="{{ old('kode_pasar') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan kode pasar">
                            @error('kode_pasar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-5">
                            <label for="status" class="block mb-2 text-base font-medium text-gray-900">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6">
                            <a href="{{ route('pasar.index') }}" class="w-full sm:w-auto text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kecamatanSelect = document.getElementById('id_kecamatan');
    const desaSelect = document.getElementById('id_desa');
    const oldDesaId = '{{ old('id_desa') }}';

    function resetDesa(placeholder) {
        desaSelect.innerHTML = `<option value="">${placeholder}</option>`;
        desaSelect.disabled = true;
    }

    function loadDesa(idKecamatan, selectedDesaId = null) {
        if (!idKecamatan) {
            resetDesa('Pilih Kecamatan Terlebih Dahulu');
            return;
        }

        desaSelect.innerHTML = '<option value="">Memuat desa...</option>';
        desaSelect.disabled = true;

        fetch(`/api/desa-by-kecamatan/${idKecamatan}`)
            .then(response => response.json())
            .then(data => {
                desaSelect.innerHTML = '<option value="">Pilih Desa</option>';

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
            })
            .catch(() => {
                resetDesa('Gagal memuat desa');
            });
    }

    kecamatanSelect.addEventListener('change', function () {
        loadDesa(this.value);
    });

    if (kecamatanSelect.value) {
        loadDesa(kecamatanSelect.value, oldDesaId || null);
    }
});
</script>
