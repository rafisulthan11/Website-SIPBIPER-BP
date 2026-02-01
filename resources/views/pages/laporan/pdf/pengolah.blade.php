<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pengolah - {{ $pengolah->nama_lengkap }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid: #1e40af; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 5px 0; color: #1e40af; }
        .header p { margin: 3px 0; color: #666; }
        .section { margin-bottom: 15px; page-break-inside: avoid; }
        .section-title { background-color: #1e40af; color: white; padding: 6px 10px; font-size: 13px; font-weight: bold; margin-bottom: 8px; }
        .subsection-title { background-color: #dbeafe; color: #1e40af; padding: 4px 8px; font-size: 11px; font-weight: bold; margin: 10px 0 6px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info-table td { padding: 4px 8px; vertical-align: top; }
        table.info-table td:first-child { font-weight: bold; width: 35%; color: #555; }
        table.data-table { border: 1px solid #ddd; }
        table.data-table th { background-color: #f3f4f6; padding: 6px; text-align: left; border: 1px solid #ddd; font-weight: bold; }
        table.data-table td { padding: 5px 6px; border: 1px solid #ddd; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #666; padding: 5px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL DATA PENGOLAH IKAN</h1>
        <p>Dinas Perikanan Kabupaten Jember</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Jenis Usaha -->
    <div class="section">
        <div class="section-title">JENIS USAHA</div>
        <table class="info-table">
            <tr><td>Jenis Kegiatan Usaha</td><td>: {{ $pengolah->jenis_kegiatan_usaha ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Profil Pemilik -->
    <div class="section">
        <div class="section-title">PROFIL PEMILIK</div>
        <table class="info-table">
            <tr><td>Nama Lengkap</td><td>: {{ $pengolah->nama_lengkap ?? '-' }}</td></tr>
            <tr><td>NIK</td><td>: {{ $pengolah->nik_pengolah ?? '-' }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{ $pengolah->jenis_kelamin ?? '-' }}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: {{ $pengolah->tempat_lahir ?? '-' }}, {{ $pengolah->tanggal_lahir ? \Carbon\Carbon::parse($pengolah->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td></tr>
            <tr><td>Pendidikan Terakhir</td><td>: {{ $pengolah->pendidikan_terakhir ?? '-' }}</td></tr>
            <tr><td>Status Perkawinan</td><td>: {{ $pengolah->status_perkawinan ?? '-' }}</td></tr>
            <tr><td>Jumlah Tanggungan</td><td>: {{ $pengolah->jumlah_tanggungan ?? '-' }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>: {{ $pengolah->alamat ?? '-' }}</td></tr>
            <tr><td>Kecamatan</td><td>: {{ $pengolah->kecamatan->nama_kecamatan ?? '-' }}</td></tr>
            <tr><td>Desa/Kelurahan</td><td>: {{ $pengolah->desa->nama_desa ?? '-' }}</td></tr>
            <tr><td>No. Telepon/HP</td><td>: {{ $pengolah->kontak ?? '-' }}</td></tr>
            <tr><td>Email</td><td>: {{ $pengolah->email ?? '-' }}</td></tr>
            <tr><td>No. NPWP</td><td>: {{ $pengolah->no_npwp ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Profil Usaha -->
    <div class="section">
        <div class="section-title">PROFIL USAHA</div>
        
        <div class="subsection-title">Informasi Umum</div>
        <table class="info-table">
            <tr><td>Nama Usaha</td><td>: {{ $pengolah->nama_usaha ?? '-' }}</td></tr>
            <tr><td>Nama Kelompok</td><td>: {{ $pengolah->nama_kelompok ?? '-' }}</td></tr>
            <tr><td>Skala Usaha</td><td>: {{ $pengolah->skala_usaha ?? '-' }}</td></tr>
            <tr><td>Status Usaha</td><td>: {{ $pengolah->status_usaha ?? '-' }}</td></tr>
            <tr><td>Tahun Mulai Usaha</td><td>: {{ $pengolah->tahun_mulai_usaha ?? '-' }}</td></tr>
        </table>

        <div class="subsection-title">Lokasi Usaha</div>
        <table class="info-table">
            <tr><td>Kecamatan Usaha</td><td>: {{ $pengolah->kecamatanUsaha->nama_kecamatan ?? '-' }}</td></tr>
            <tr><td>Desa Usaha</td><td>: {{ $pengolah->desaUsaha->nama_desa ?? '-' }}</td></tr>
            <tr><td>Alamat Lengkap Usaha</td><td>: {{ $pengolah->alamat_usaha ?? '-' }}</td></tr>
            <tr><td>Koordinat (Lat, Long)</td><td>: {{ $pengolah->latitude ?? '-' }}, {{ $pengolah->longitude ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Produksi -->
    <div class="section">
        <div class="section-title">DATA PRODUKSI</div>
        @if($pengolah->produksi_data && is_array($pengolah->produksi_data) && count($pengolah->produksi_data) > 0)
            @foreach($pengolah->produksi_data as $index => $produksi)
                @if(isset($produksi['nama_merk']) || isset($produksi['periode']))
                <div class="subsection-title">Produk {{ $index + 1 }}</div>
                <table class="info-table">
                    <tr><td>Nama Merk</td><td>: {{ $produksi['nama_merk'] ?? '-' }}</td></tr>
                    <tr><td>Periode</td><td>: {{ $produksi['periode'] ?? '-' }}</td></tr>
                    <tr><td>Kapasitas Terpasang</td><td>: {{ isset($produksi['kapasitas_terpasang']) ? number_format($produksi['kapasitas_terpasang'], 2) . ' Kg' : '-' }}</td></tr>
                    <tr><td>Jumlah Hari Produksi/Bulan</td><td>: {{ $produksi['jumlah_hari_produksi'] ?? '-' }} hari</td></tr>
                    @if(isset($produksi['bulan_produksi']) && is_array($produksi['bulan_produksi']) && count($produksi['bulan_produksi']) > 0)
                        @php $bulanNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; @endphp
                        <tr><td>Bulan Produksi</td><td>: @foreach($produksi['bulan_produksi'] as $bulan){{ $bulanNames[$bulan - 1] ?? $bulan }}@if(!$loop->last), @endif @endforeach</td></tr>
                    @endif
                    @if(isset($produksi['sertifikat_lahan']) && is_array($produksi['sertifikat_lahan']) && count($produksi['sertifikat_lahan']) > 0)
                        <tr><td>Sertifikat Lahan</td><td>: {{ implode(', ', $produksi['sertifikat_lahan']) }}</td></tr>
                    @endif
                    <tr><td>Biaya Produksi</td><td>: Rp. {{ isset($produksi['biaya_produksi']) ? number_format($produksi['biaya_produksi'], 0, ',', '.') : '-' }}</td></tr>
                    <tr><td>Biaya Lain-lain</td><td>: Rp. {{ isset($produksi['biaya_lain']) ? number_format($produksi['biaya_lain'], 0, ',', '.') : '-' }}</td></tr>
                    <tr><td>Harga Jual</td><td>: Rp. {{ isset($produksi['harga_jual']) ? number_format($produksi['harga_jual'], 0, ',', '.') : '-' }}</td></tr>
                    <tr><td>Harga Produksi</td><td>: {{ isset($produksi['harga_produksi_qty']) ? number_format($produksi['harga_produksi_qty'], 2) . ' Kg' : '-' }} - Rp. {{ isset($produksi['harga_produksi_harga']) ? number_format($produksi['harga_produksi_harga'], 0, ',', '.') : '-' }}</td></tr>
                </table>
                
                @if(isset($produksi['bahan_baku']) && is_array($produksi['bahan_baku']) && count($produksi['bahan_baku']) > 0)
                <p style="font-weight: bold; margin: 8px 0 4px 0;">Bahan Baku:</p>
                <table class="data-table" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Bahan</th>
                            <th>Asal</th>
                            <th>Harga</th>
                            <th>Qty (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produksi['bahan_baku'] as $bahanIndex => $bahan)
                        <tr>
                            <td>{{ $bahanIndex + 1 }}</td>
                            <td>{{ $bahan['bahan'] ?? '-' }}</td>
                            <td>{{ $bahan['asal'] ?? '-' }}</td>
                            <td>Rp. {{ isset($bahan['harga']) ? number_format($bahan['harga'], 0, ',', '.') : '-' }}</td>
                            <td>{{ isset($bahan['qty']) ? number_format($bahan['qty'], 2) : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
                
                <table class="info-table">
                    <tr><td>Pemasaran</td><td>: {{ $produksi['pemasaran'] ?? '-' }}</td></tr>
                    <tr><td>Jumlah Produk</td><td>: {{ isset($produksi['jumlah_produk_qty']) ? number_format($produksi['jumlah_produk_qty'], 2) . ' Kg' : '-' }} - {{ $produksi['jumlah_produk_pack'] ?? '-' }} pack</td></tr>
                    <tr><td>Harga Jual/pack</td><td>: Rp. {{ isset($produksi['harga_jual_pack']) ? number_format($produksi['harga_jual_pack'], 0, ',', '.') : '-' }}</td></tr>
                </table>
                @endif
            @endforeach
        @else
            <p style="padding: 8px; color: #666;">Belum ada data produksi.</p>
        @endif
    </div>

    <!-- Tenaga Kerja -->
    <div class="section">
        <div class="section-title">TENAGA KERJA</div>
        @if($pengolah->tenaga_kerja_data && is_array($pengolah->tenaga_kerja_data))
            @php $tk = $pengolah->tenaga_kerja_data; @endphp
            <table class="data-table">
                <thead>
                    <tr>
                        <th rowspan="2">Kategori</th>
                        <th colspan="3">Laki-laki</th>
                        <th colspan="3">Perempuan</th>
                    </tr>
                    <tr>
                        <th>Tetap</th><th>Tidak Tetap</th><th>Keluarga</th>
                        <th>Tetap</th><th>Tidak Tetap</th><th>Keluarga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>WNI</strong></td>
                        <td>{{ $tk['wni_laki_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wni_laki_tidak_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wni_laki_keluarga'] ?? 0 }}</td>
                        <td>{{ $tk['wni_perempuan_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wni_perempuan_tidak_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wni_perempuan_keluarga'] ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td><strong>WNA</strong></td>
                        <td>{{ $tk['wna_laki_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wna_laki_tidak_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wna_laki_keluarga'] ?? 0 }}</td>
                        <td>{{ $tk['wna_perempuan_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wna_perempuan_tidak_tetap'] ?? 0 }}</td>
                        <td>{{ $tk['wna_perempuan_keluarga'] ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="padding: 8px; color: #666;">Belum ada data tenaga kerja.</p>
        @endif
    </div>

    <!-- Lampiran -->
    <div class="section">
        <div class="section-title">LAMPIRAN DOKUMEN</div>
        @php
            $lampiran = [
                'foto_ktp' => 'Foto KTP',
                'foto_sertifikat' => 'Foto Sertifikat',
                'foto_cpib_cbib' => 'Foto CPIB/CBIB',
                'foto_unit_usaha' => 'Foto Unit Usaha',
                'foto_kusuka' => 'Foto KUSUKA',
                'foto_nib' => 'Foto NIB',
            ];
            $hasLampiran = false;
        @endphp
        
        <table class="info-table">
        @foreach($lampiran as $key => $label)
            @if($pengolah->$key)
                @php 
                    $hasLampiran = true;
                    $filePath = str_starts_with($pengolah->$key, 'storage/') 
                        ? substr($pengolah->$key, 8) 
                        : $pengolah->$key;
                @endphp
                <tr>
                    <td>{{ $label }}</td>
                    <td>: ✓ Tersedia ({{ basename($filePath) }})</td>
                </tr>
            @endif
        @endforeach
        </table>
        
        @if(!$hasLampiran)
            <p style="padding: 8px; color: #666;">Belum ada dokumen lampiran.</p>
        @endif
        
        <p style="padding: 8px; color: #666; font-size: 10px; font-style: italic; margin-top: 10px;">
            * Untuk melihat gambar lampiran, silakan akses halaman detail di sistem.
        </p>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Dinas Perikanan Kabupaten Jember</p>
    </div>
</body>
</html>
