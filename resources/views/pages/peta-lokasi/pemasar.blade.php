<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            Peta Lokasi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="bg-blue-600 text-white rounded-t-xl shadow-lg">
                <div class="flex justify-center items-center gap-1 p-2">
                    <a href="{{ route('peta-lokasi.index') }}" class="px-8 py-3 font-semibold text-white hover:bg-blue-700 rounded-lg transition-all whitespace-nowrap">
                        Peta Interaktif Pembudidaya
                    </a>
                    <a href="{{ route('peta-lokasi.pengolah') }}" class="px-8 py-3 font-semibold text-white hover:bg-blue-700 rounded-lg transition-all whitespace-nowrap">
                        Peta Interaktif Pengolah
                    </a>
                    <!-- Tab Aktif: Pemasar -->
                    <a href="{{ route('peta-lokasi.pemasar') }}" class="px-8 py-3 font-bold bg-white text-blue-700 rounded-lg shadow-md whitespace-nowrap">
                        Peta Interaktif Pemasar
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="bg-white rounded-b-xl shadow-lg p-6">
                <!-- Filter Section -->
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-3">Peta Lokasi Pemasar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Filter Kecamatan -->
                        <div>
                            <label for="filter-kecamatan" class="block text-sm font-medium text-gray-700 mb-1">
                                Kecamatan
                            </label>
                            <select id="filter-kecamatan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Kecamatan</option>
                                @foreach($kecamatans ?? [] as $kecamatan)
                                    <option value="{{ $kecamatan->id_kecamatan }}">{{ $kecamatan->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Komoditas -->
                        <div>
                            <label for="filter-komoditas" class="block text-sm font-medium text-gray-700 mb-1">
                                Komoditas
                            </label>
                            <select id="filter-komoditas" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Komoditas</option>
                                <option value="lele">Lele</option>
                                <option value="nila">Nila</option>
                                <option value="gurame">Gurame</option>
                                <option value="patin">Patin</option>
                                <option value="udang">Udang</option>
                                <option value="bandeng">Bandeng</option>
                            </select>
                        </div>

                        <!-- (Jenis Budidaya removed for Pemasar view) -->
                    </div>
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-96 md:h-[500px] rounded-lg shadow-inner border border-gray-300 z-0 relative"></div>

                <!-- Legend / Keterangan -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Info Keterangan -->
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700 mb-2">
                            <strong class="text-gray-900">Cara Menggunakan:</strong>
                        </p>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Klik marker pada peta untuk melihat detail lokasi</li>
                            <li>Gunakan filter untuk menyaring data</li>
                            <li>Zoom in/out dengan scroll mouse atau tombol +/-</li>
                            <li>Geser peta dengan klik & drag</li>
                        </ul>
                    </div>

                    <!-- Legend Marker -->
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700 mb-2">
                            <strong class="text-gray-900">Legenda Marker:</strong>
                        </p>
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-6 h-8 bg-blue-600 rounded-sm"></div>
                                <span class="text-gray-700"><strong>Biru:</strong> Pembudidaya</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-6 h-8 bg-green-600 rounded-sm"></div>
                                <span class="text-gray-700"><strong>Hijau:</strong> Pengolah</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <div class="w-6 h-8 bg-orange-600 rounded-sm"></div>
                                <span class="text-gray-700"><strong>Oranye:</strong> Pemasar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    @endpush

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
    // Tunggu sampai DOM dan Leaflet library ready
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah element map ada
        const mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Element #map tidak ditemukan di DOM!');
            return;
        }
        
        // Cek apakah Leaflet library loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet library belum loaded!');
            return;
        }
        
        // Data (coba ambil dari $pemasars dulu, fallback ke $pembudidayas)
        let pembudidayaData = @json($pemasars ?? $pembudidayas ?? []);
        
        console.log('Initializing map...', {
            totalData: pembudidayaData.length,
            data: pembudidayaData
        });
        
        // Initialize map centered on Jember
        const map = L.map('map').setView([-8.1700, 113.7000], 12);
        
        console.log('Map object created:', map);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);
        
        // Fix untuk memastikan peta ter-render dengan benar
        setTimeout(function() {
            map.invalidateSize();
            console.log('Map size invalidated');
        }, 100);

        // Custom marker icons untuk berbeda tipe (pembudidaya, pengolah, pemasar)
        const markerIcons = {
            pembudidaya: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            }),
            pengolah: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            }),
            pemasar: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        };

        let markers = [];

        // Function untuk membuat popup content yang detail
        function createPopupContent(item) {
            const type = item.type || 'pemasar';
            const typeLabel = type === 'pembudidaya' ? 'Pembudidaya' : 
                             type === 'pengolah' ? 'Pengolah' : 'Pemasar';

            return `
                <div class="p-3">
                    <div class="mb-3 pb-2 border-b border-gray-200">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded mb-2">
                            ${typeLabel}
                        </span>
                        <h3 class="font-bold text-blue-700 text-lg">${item.nama || 'Tidak ada nama'}</h3>
                    </div>
                    
                    <div class="space-y-1.5 text-sm">
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">NIK:</span>
                            <span class="text-gray-600">${item.nik || '-'}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Kecamatan:</span>
                            <span class="text-gray-600">${item.kecamatan?.nama || '-'}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Desa:</span>
                            <span class="text-gray-600">${item.desa?.nama || '-'}</span>
                        </div>
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Alamat:</span>
                            <span class="text-gray-600 flex-1">${item.alamat || '-'}</span>
                        </div>
                        ${item.nama_usaha ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Nama Usaha:</span>
                            <span class="text-gray-600 flex-1">${item.nama_usaha}</span>
                        </div>` : ''}
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Komoditas:</span>
                            <span class="text-gray-600">${item.komoditas || '-'}</span>
                        </div>
                        ${item.jenis_budidaya ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Jenis Budidaya:</span>
                            <span class="text-gray-600">${item.jenis_budidaya}</span>
                        </div>` : ''}
                        ${item.skala_usaha ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Skala Usaha:</span>
                            <span class="text-gray-600">${item.skala_usaha}</span>
                        </div>` : ''}
                        ${item.kontak ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Kontak:</span>
                            <span class="text-gray-600">${item.kontak}</span>
                        </div>` : ''}
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <a href="/pembudidaya/${item.id}" class="inline-block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition">
                            Lihat Detail Lengkap →
                        </a>
                    </div>
                </div>
            `;
        }

        // Function to add markers
        function addMarkers(data) {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Add new markers
            data.forEach(item => {
                if (item.latitude && item.longitude) {
                    // Pilih icon berdasarkan tipe
                    const iconType = item.type || 'pemasar';
                    const icon = markerIcons[iconType];

                    const marker = L.marker([item.latitude, item.longitude], {
                        icon: icon
                    }).addTo(map);

                    // Bind popup dengan content yang detail
                    marker.bindPopup(createPopupContent(item), {
                        maxWidth: 350
                    });

                    markers.push(marker);
                }
            });

            // Adjust map bounds to show all markers
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        }

        // Initial render
        addMarkers(pembudidayaData);

        // Filter functionality
        function applyFilters() {
            const kecamatanFilter = document.getElementById('filter-kecamatan').value;
            const komoditasFilter = document.getElementById('filter-komoditas').value;

            let filteredData = pembudidayaData;

            if (kecamatanFilter) {
                filteredData = filteredData.filter(p => p.kecamatan_id == kecamatanFilter);
            }

            if (komoditasFilter) {
                filteredData = filteredData.filter(p => 
                    p.komoditas && p.komoditas.toLowerCase().includes(komoditasFilter.toLowerCase())
                );
            }

            addMarkers(filteredData);
        }

        // Event listeners for filters
    document.getElementById('filter-kecamatan').addEventListener('change', applyFilters);
    document.getElementById('filter-komoditas').addEventListener('change', applyFilters);
        
        console.log('Map initialized successfully!');
    }); // End DOMContentLoaded
    </script>
    @endpush
</x-app-layout>
