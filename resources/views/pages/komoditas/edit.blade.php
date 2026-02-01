<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Edit Komoditas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-md shadow-md">
                <div class="p-5 sm:p-6">
                    <form action="{{ route('komoditas.update', $komoditas->id_komoditas) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Komoditas -->
                        <div class="mb-5">
                            <label for="nama_komoditas" class="block mb-2 text-base font-medium text-gray-900">
                                Nama Komoditas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_komoditas" name="nama_komoditas" value="{{ old('nama_komoditas', $komoditas->nama_komoditas) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan nama komoditas">
                            @error('nama_komoditas')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe -->
                        <div class="mb-5">
                            <label for="tipe" class="block mb-2 text-base font-medium text-gray-900">
                                Tipe <span class="text-red-500">*</span>
                            </label>
                            <select id="tipe" name="tipe" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Pilih Tipe</option>
                                <option value="Pengolah" {{ old('tipe', $komoditas->tipe) == 'Pengolah' ? 'selected' : '' }}>Pengolah</option>
                                <option value="Pembudidaya" {{ old('tipe', $komoditas->tipe) == 'Pembudidaya' ? 'selected' : '' }}>Pembudidaya</option>
                            </select>
                            @error('tipe')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode -->
                        <div class="mb-5">
                            <label for="kode" class="block mb-2 text-base font-medium text-gray-900">
                                Kode <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kode" name="kode" value="{{ old('kode', $komoditas->kode) }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan kode komoditas">
                            @error('kode')
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
                                <option value="aktif" {{ old('status', $komoditas->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status', $komoditas->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('komoditas.index') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Batal
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
