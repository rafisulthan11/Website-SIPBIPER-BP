<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            Peta Lokasi
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Banner Header (single, full-width) -->
            <div class="bg-blue-600 text-white rounded-t-xl shadow-lg">
                <div class="px-6 py-4 lg:px-8">
                    <h1 class="font-extrabold text-xl sm:text-2xl">Peta Interaktif Pelaku Usaha</h1>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white shadow-lg">
                <div class="px-6 pt-4 pb-2">
                    <div class="flex flex-wrap gap-2" role="tablist">
                        <button type="button" class="tab-button active inline-flex items-center px-5 py-2 rounded font-medium text-sm transition-all" data-tab="semua">
                            Semua Pelaku Usaha
                        </button>
                        <button type="button" class="tab-button inline-flex items-center px-5 py-2 rounded font-medium text-sm transition-all" data-tab="pembudidaya">
                            Pembudidaya
                        </button>
                        <button type="button" class="tab-button inline-flex items-center px-5 py-2 rounded font-medium text-sm transition-all" data-tab="pengolah">
                            Pengolah
                        </button>
                        <button type="button" class="tab-button inline-flex items-center px-5 py-2 rounded font-medium text-sm transition-all" data-tab="pemasar">
                            Pemasar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="bg-white rounded-b-xl shadow-lg px-6 py-3">
                <!-- Inner Container with border -->
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                    <h2 class="text-lg font-semibold mb-4">Peta Lokasi Pelaku Usaha</h2>
                    
                    <!-- Filter Section -->
                    <div class="mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
                                @foreach($komoditas ?? [] as $item)
                                    <option value="{{ $item->nama_komoditas }}">{{ $item->nama_komoditas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Jenis Kegiatan Usaha -->
                        <div>
                            <label for="filter-jenis" class="block text-sm font-medium text-gray-700 mb-1">
                                Jenis Kegiatan Usaha
                            </label>
                            <select id="filter-jenis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                <optgroup label="Budidaya">
                                    <option value="Kolam">Kolam</option>
                                    <option value="Mina Padi">Mina Padi</option>
                                    <option value="Keramba">Keramba</option>
                                    <option value="Tambak">Tambak</option>
                                </optgroup>
                                <optgroup label="Pengolahan">
                                    <option value="Pengalengan">Pengalengan</option>
                                    <option value="Pembekuan">Pembekuan</option>
                                    <option value="Penggaraman/Pengeringan">Penggaraman/Pengeringan</option>
                                    <option value="Pemindangan">Pemindangan</option>
                                    <option value="Pengasapan/Pemanggangan">Pengasapan/Pemanggangan</option>
                                    <option value="Fermentasi/Peragian">Fermentasi/Peragian</option>
                                    <option value="Pereduksian/Ekstraksi">Pereduksian/Ekstraksi</option>
                                    <option value="Pelumatan Daging/Surimi">Pelumatan Daging/Surimi</option>
                                    <option value="Penanganan Produk Segar/Dingin">Penanganan Produk Segar/Dingin</option>
                                    <option value="Pengolahan Lainnya">Pengolahan Lainnya</option>
                                    <option value="Non Konsumsi/Ikan Hias">Non Konsumsi/Ikan Hias</option>
                                </optgroup>
                                <optgroup label="Pemasaran">
                                    <option value="Pemasar Ikan Segar Pengecer">Pemasar Ikan Segar Pengecer</option>
                                    <option value="Pemasar Ikan Segar Pedagang Besar">Pemasar Ikan Segar Pedagang Besar</option>
                                    <option value="Pemasar Ikan Pindang/Asap">Pemasar Ikan Pindang/Asap</option>
                                    <option value="Pemasar Ikan Hias">Pemasar Ikan Hias</option>
                                    <option value="Pemasar Ikan Asin">Pemasar Ikan Asin</option>
                                    <option value="Lainnya">Lainnya</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Filter Status Usaha -->
                        <div>
                            <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">
                                Status Usaha
                            </label>
                            <select id="filter-status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                            </select>
                        </div>
                        </div>
                    </div>

                    <!-- Notifikasi Data Tidak Ditemukan -->
                    <div id="no-data-notification" class="hidden mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-semibold">Data Tidak Ditemukan</p>
                            <p class="text-sm" id="no-data-message">Tidak ada data pelaku usaha yang sesuai dengan filter yang dipilih.</p>
                        </div>
                    </div>
                    </div>

                    <!-- Statistik -->
                    <div class="mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">Total Lokasi</p>
                                <p class="text-xl font-bold text-blue-600" id="stat-total-lokasi">{{ $totalLokasi ?? 0 }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">Luas Lahan (Investasi)</p>
                                <p class="text-xl font-bold text-green-600" id="stat-luas-lahan">{{ number_format($luasLahanInvestasi ?? 0, 2) }} m²</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">Luas Kolam</p>
                                <p class="text-xl font-bold text-cyan-600" id="stat-luas-kolam">{{ number_format($luasKolam ?? 0, 2) }} m²</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 mb-1">Total Gabungan</p>
                                <p class="text-xl font-bold text-indigo-600" id="stat-total-gabungan">{{ number_format($totalGabungan ?? 0, 2) }} m²</p>
                            </div>
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div id="map" class="w-full h-64 sm:h-80 md:h-[500px] rounded-lg shadow-inner border border-gray-300 z-0 relative"></div>
                </div>

                <!-- Legend / Keterangan -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
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
    <style>
        .tab-button {
            color: #374151;
            background-color: white;
            border: 1px solid #d1d5db;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .tab-button:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }
        .tab-button.active {
            color: white;
            background-color: #2563eb;
            border-color: #2563eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>
    @endpush

    @push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
    // Function global untuk redirect ke halaman detail (harus di luar DOMContentLoaded)
    function redirectToDetail(type, id) {
        let url = '';
        
        if (type === 'pembudidaya') {
            url = `/pembudidaya/${id}`;
        } else if (type === 'pengolah') {
            url = `/pengolah/${id}`;
        } else if (type === 'pemasar') {
            url = `/pemasar/${id}`;
        }
        
        if (url) {
            window.location.href = url;
        }
    }

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

        // Fungsi untuk menghitung dan update statistik
        function updateStatistics(data) {
            const totalLokasi = data.length;
            
            // Hitung luas lahan investasi (dari pemasar)
            const luasLahanInvestasi = data
                .filter(item => item.type === 'pemasar')
                .reduce((sum, item) => sum + (parseFloat(item.luas_lahan) || 0), 0);
            
            // Hitung luas kolam (dari pembudidaya)
            const luasKolam = data
                .filter(item => item.type === 'pembudidaya')
                .reduce((sum, item) => sum + (parseFloat(item.luas_kolam) || 0), 0);
            
            // Total gabungan
            const totalGabungan = luasLahanInvestasi + luasKolam;
            
            // Update tampilan
            document.getElementById('stat-total-lokasi').textContent = totalLokasi;
            document.getElementById('stat-luas-lahan').textContent = luasLahanInvestasi.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' m²';
            document.getElementById('stat-luas-kolam').textContent = luasKolam.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' m²';
            document.getElementById('stat-total-gabungan').textContent = totalGabungan.toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' m²';
        }
        
        // Tab Navigation Functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tab = this.dataset.tab;
                
                // Update active states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter berdasarkan tab yang dipilih
                applyFilters();
            });
        });
        
        // Data pelaku usaha (pembudidaya, pengolah, pemasar)
        let pembudidayaData = @json($allData ?? []);
        
        console.log('Initializing map...', {
            totalData: pembudidayaData.length
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

        // Re-invalidate map when viewport changes or sidebar toggles
        function scheduleInvalidate() {
            clearTimeout(window.__mapInvalidateTimer);
            window.__mapInvalidateTimer = setTimeout(function(){ map.invalidateSize(); console.log('Map invalidate on resize/sidebar'); }, 120);
        }

        window.addEventListener('resize', scheduleInvalidate);
        window.addEventListener('orientationchange', scheduleInvalidate);
        window.addEventListener('sincan.sidebarToggled', scheduleInvalidate);

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
            const type = item.type || 'pembudidaya';
            const typeLabel = type === 'pembudidaya' ? 'Pembudidaya' : 
                             type === 'pengolah' ? 'Pengolah' : 'Pemasar';
            
            const lokasiType = item.lokasi_type === 'usaha' ? 'Lokasi Usaha' : 'Lokasi Rumah';
            const lokasiIcon = item.lokasi_type === 'usaha' ? '🏢' : '🏠';

            return `
                <div class="p-3">
                    <div class="mb-3 pb-2 border-b border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-white bg-blue-600 rounded">
                                ${typeLabel}
                            </span>
                            <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded">
                                ${lokasiIcon} ${lokasiType}
                            </span>
                        </div>
                        <h3 class="font-bold text-blue-700 text-lg">${item.nama || 'Tidak ada nama'}</h3>
                    </div>
                    
                    <div class="space-y-1.5 text-sm">
                        ${item.nama_usaha ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Nama Usaha:</span>
                            <span class="text-gray-600 flex-1">${item.nama_usaha}</span>
                        </div>` : ''}
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
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Komoditas:</span>
                            <span class="text-gray-600">${item.komoditas || '-'}</span>
                        </div>
                        ${item.jenis_kegiatan ? `
                        <div class="flex">
                            <span class="font-semibold text-gray-700 w-28">Jenis Kegiatan:</span>
                            <span class="text-gray-600">${item.jenis_kegiatan}</span>
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
                        <button onclick="redirectToDetail('${type}', ${item.id})" class="inline-block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded transition cursor-pointer">
                            Lihat Detail Lengkap →
                        </button>
                    </div>
                </div>
            `;
        }

        // Function to add markers
        function addMarkers(data) {
            // Clear existing markers
            markers.forEach(marker => map.removeLayer(marker));
            markers = [];

            // Track koordinat yang sudah digunakan untuk mendeteksi duplikasi
            const usedCoordinates = {};

            // Add new markers
            data.forEach(item => {
                if (item.latitude && item.longitude) {
                    let lat = parseFloat(item.latitude);
                    let lng = parseFloat(item.longitude);
                    
                    // Cek apakah koordinat ini sudah digunakan
                    const coordKey = `${lat.toFixed(6)}_${lng.toFixed(6)}`;
                    if (usedCoordinates[coordKey]) {
                        // Tambahkan offset kecil agar marker tidak bertumpuk
                        const offsetCount = usedCoordinates[coordKey];
                        const angle = (offsetCount * 60) * Math.PI / 180; // 60 derajat per marker
                        const offsetDistance = 0.0001; // ~11 meter
                        lat += offsetDistance * Math.cos(angle);
                        lng += offsetDistance * Math.sin(angle);
                        usedCoordinates[coordKey]++;
                    } else {
                        usedCoordinates[coordKey] = 1;
                    }
                    
                    // Pilih icon berdasarkan tipe
                    const iconType = item.type || 'pembudidaya';
                    const icon = markerIcons[iconType];

                    const marker = L.marker([lat, lng], {
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

        // Koordinat pusat kecamatan di Kabupaten Jember (Data Resmi)
        const kecamatanCoordinates = {
            '1': { center: [-8.282225678229343, 113.3594902434237], name: 'Kencong' },
            '2': { center: [-8.321751502043044, 113.40634186378051], name: 'Gumukmas' },
            '3': { center: [-8.319292903829105, 113.48026881364152], name: 'Puger' },
            '4': { center: [-8.343743752947601, 113.5516795963218], name: 'Wuluhan' },
            '5': { center: [-8.364423129024694, 113.6116580365305], name: 'Ambulu' },
            '6': { center: [-8.393050001609398, 113.7526722322849], name: 'Tempurejo' },
            '7': { center: [-8.25514999542895, 113.86583361805705], name: 'Silo' },
            '8': { center: [-8.19755786495193, 113.79789822517287], name: 'Mayang' },
            '9': { center: [-8.254879461848638, 113.73539432057147], name: 'Mumbulsari' },
            '10': { center: [-8.281507458407429, 113.65260835851366], name: 'Jenggawah' },
            '11': { center: [-8.231380260363077, 113.65228757561769], name: 'Ajung' },
            '12': { center: [-8.211062010076493, 113.61173386690979], name: 'Rambipuji' },
            '13': { center: [-8.26885604124649, 113.53450677120202], name: 'Balung' },
            '14': { center: [-8.24942054952015, 113.41607980566896], name: 'Umbulsari' },
            '15': { center: [-8.21004773348576, 113.44902120206352], name: 'Semboro' },
            '16': { center: [-8.217093039160112, 113.36329927029954], name: 'Jombang' },
            '17': { center: [-8.122586599231846, 113.4802280027988], name: 'Tanggul' },
            '18': { center: [-8.156971749754748, 113.55523671764779], name: 'Bangsalsari' },
            '19': { center: [-8.107136492909099, 113.61757692825515], name: 'Panti' },
            '20': { center: [-8.125172496480625, 113.6610863288438], name: 'Sukorambi' },
            '21': { center: [-8.102612724078517, 113.73473925403547], name: 'Arjasa' },
            '22': { center: [-8.152288080599, 113.76861865629456], name: 'Pakusari' },
            '23': { center: [-8.119101031085952, 113.7989056831429], name: 'Kalisat' },
            '24': { center: [-8.147355409918719, 113.87846267658568], name: 'Ledokombo' },
            '25': { center: [-8.080478516770901, 113.92242439097728], name: 'Sumberjambe' },
            '26': { center: [-8.07050132914081, 113.82829189905276], name: 'Sukowono' },
            '27': { center: [-8.058960646240651, 113.72539515119054], name: 'Jelbuk' },
            '28': { center: [-8.183483259192707, 113.67525452912052], name: 'Kaliwates' },
            '29': { center: [-8.170159788909027, 113.72944187038041], name: 'Sumbersari' },
            '30': { center: [-8.138022079526799, 113.70495640240186], name: 'Patrang' },
            '32': { center: [-8.107479347015294, 113.40279174096219], name: 'Sumberbaru' }
        };

        // Initial render
        addMarkers(pembudidayaData);

        // Filter functionality
        function applyFilters() {
            const activeTab = document.querySelector('.tab-button.active').dataset.tab;
            const kecamatanFilter = document.getElementById('filter-kecamatan').value;
            const komoditasFilter = document.getElementById('filter-komoditas').value;
            const jenisFilter = document.getElementById('filter-jenis').value;
            const statusFilter = document.getElementById('filter-status').value;

            let filteredData = pembudidayaData;

            // Filter berdasarkan tab aktif
            if (activeTab !== 'semua') {
                filteredData = filteredData.filter(p => p.type === activeTab);
            }

            if (kecamatanFilter) {
                filteredData = filteredData.filter(p => p.kecamatan_id == kecamatanFilter);
            }

            if (komoditasFilter) {
                filteredData = filteredData.filter(p => 
                    p.komoditas && p.komoditas.toLowerCase().includes(komoditasFilter.toLowerCase())
                );
            }

            if (jenisFilter) {
                filteredData = filteredData.filter(p => 
                    p.jenis_kegiatan && p.jenis_kegiatan === jenisFilter
                );
            }

            if (statusFilter) {
                filteredData = filteredData.filter(p => {
                    // Normalisasi untuk perbandingan yang lebih robust
                    const itemStatus = (p.status_usaha || '').toString().trim();
                    const filterStatus = statusFilter.trim();
                    return itemStatus === filterStatus;
                });
            }

            addMarkers(filteredData);
            
            // Update statistik berdasarkan data yang difilter
            updateStatistics(filteredData);

            // Tampilkan/sembunyikan notifikasi berdasarkan hasil filter
            const notification = document.getElementById('no-data-notification');
            const notificationMessage = document.getElementById('no-data-message');
            
            // Cek apakah ada filter yang aktif
            const hasActiveFilter = bidangFilter || kecamatanFilter || komoditasFilter || jenisFilter || statusFilter;
            
            if (hasActiveFilter && filteredData.length === 0) {
                // Buat pesan yang lebih spesifik
                let message = 'Tidak ada data pelaku usaha';
                let filters = [];
                
                if (bidangFilter) {
                    const bidangText = bidangFilter === 'pembudidaya' ? 'Pembudidaya' : 
                                      bidangFilter === 'pengolah' ? 'Pengolah' : 'Pemasar';
                    filters.push(bidangText);
                }
                
                if (kecamatanFilter) {
                    const kecamatanName = kecamatanCoordinates[kecamatanFilter]?.name || '';
                    if (kecamatanName) filters.push('Kecamatan ' + kecamatanName);
                }
                
                if (komoditasFilter) {
                    filters.push('komoditas ' + komoditasFilter);
                }
                
                if (jenisFilter) {
                    filters.push(jenisFilter);
                }
                
                if (filters.length > 0) {
                    message += ' untuk ' + filters.join(', ');
                }
                
                message += '. Silakan ubah filter atau pilih kategori lain.';
                notificationMessage.textContent = message;
                notification.classList.remove('hidden');
            } else {
                notification.classList.add('hidden');
            }

            // Auto-zoom ke kecamatan yang dipilih
            if (kecamatanFilter && kecamatanCoordinates[kecamatanFilter]) {
                const kecamatan = kecamatanCoordinates[kecamatanFilter];
                console.log('Zooming to Kecamatan:', kecamatan.name, kecamatan.center);
                
                if (filteredData.length > 0) {
                    // Jika ada data, fit bounds ke data yang tersaring
                    const group = L.featureGroup(filteredData.map(item => {
                        if (item.latitude && item.longitude) {
                            return L.marker([parseFloat(item.latitude), parseFloat(item.longitude)]);
                        }
                    }).filter(marker => marker));
                    
                    if (group.getLayers().length > 0) {
                        map.fitBounds(group.getBounds().pad(0.1), { maxZoom: 14 });
                    } else {
                        // Tidak ada marker, zoom ke pusat kecamatan
                        map.setView(kecamatan.center, 14);
                    }
                } else {
                    // Jika tidak ada data, zoom ke pusat kecamatan dengan koordinat yang akurat
                    map.setView(kecamatan.center, 14);
                }
            } else if (!kecamatanFilter && filteredData.length > 0) {
                // Jika filter kecamatan dikosongkan dan ada data, fit bounds ke semua data
                const group = L.featureGroup(markers);
                if (markers.length > 0) {
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            } else if (!kecamatanFilter && filteredData.length === 0) {
                // Jika tidak ada filter kecamatan dan tidak ada data, kembali ke view default Jember
                map.setView([-8.1700, 113.7000], 12);
            }
        }

        // Event listeners for filters
        document.getElementById('filter-kecamatan').addEventListener('change', applyFilters);
        document.getElementById('filter-komoditas').addEventListener('change', applyFilters);
        document.getElementById('filter-jenis').addEventListener('change', applyFilters);
        document.getElementById('filter-status').addEventListener('change', applyFilters);
        
        console.log('Map initialized successfully!');
    }); // End DOMContentLoaded
    </script>
    @endpush
</x-app-layout>
