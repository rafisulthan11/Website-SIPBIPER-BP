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
                            <label for="kecamatan" class="block mb-2 text-base font-medium text-gray-900">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan kecamatan">
                            @error('kecamatan')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Desa -->
                        <div class="mb-5">
                            <label for="desa" class="block mb-2 text-base font-medium text-gray-900">
                                Desa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="desa" name="desa" value="{{ old('desa') }}" required class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Masukkan desa">
                            @error('desa')
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

                        <!-- Latitude -->
                        <div class="mb-5">
                            <label for="latitude" class="block mb-2 text-base font-medium text-gray-900">
                                Latitude
                            </label>
                            <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: -8.123456">
                            @error('latitude')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Longitude -->
                        <div class="mb-5">
                            <label for="longitude" class="block mb-2 text-base font-medium text-gray-900">
                                Longitude
                            </label>
                            <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-base rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: 113.123456">
                            @error('longitude')
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
                        <div class="flex justify-end gap-3 mt-6">
                            <a href="{{ route('pasar.index') }}" class="text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Batal
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-base px-5 py-2.5 text-center">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
