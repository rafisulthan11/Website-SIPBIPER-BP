<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pembudidaya - {{ $pembudidaya->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 5px 0;
            color: #1e40af;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #1e40af;
            color: white;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .subsection-title {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            margin: 10px 0 6px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        table.info-table td:first-child {
            font-weight: bold;
            width: 35%;
            color: #555;
        }
        table.data-table {
            border: 1px solid #ddd;
        }
        table.data-table th {
            background-color: #f3f4f6;
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table.data-table td {
            padding: 5px 6px;
            border: 1px solid #ddd;
        }
        .grid-2 {
            display: table;
            width: 100%;
        }
        .grid-2 > div {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
            padding: 5px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL DATA PEMBUDIDAYA IKAN</h1>
        <p>Dinas Perikanan Kabupaten Jember</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Jenis Usaha -->
    <div class="section">
        <div class="section-title">JENIS USAHA</div>
        <table class="info-table">
            <tr>
                <td>Jenis Kegiatan Usaha</td>
                <td>: {{ $pembudidaya->jenis_kegiatan_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Budidaya</td>
                <td>: {{ $pembudidaya->jenis_budidaya ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Profil Pemilik -->
    <div class="section">
        <div class="section-title">PROFIL PEMILIK</div>
        <table class="info-table">
            <tr>
                <td>Nama Lengkap</td>
                <td>: {{ $pembudidaya->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: {{ $pembudidaya->nik_pembudidaya ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>: {{ $pembudidaya->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>: {{ $pembudidaya->tempat_lahir ?? '-' }}, {{ $pembudidaya->tanggal_lahir ? \Carbon\Carbon::parse($pembudidaya->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Status Perkawinan</td>
                <td>: {{ $pembudidaya->status_perkawinan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Lengkap</td>
                <td>: {{ $pembudidaya->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kecamatan</td>
                <td>: {{ $pembudidaya->kecamatan->nama_kecamatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Desa/Kelurahan</td>
                <td>: {{ $pembudidaya->desa->nama_desa ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon/HP</td>
                <td>: {{ $pembudidaya->kontak ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $pembudidaya->email ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. NPWP</td>
                <td>: {{ $pembudidaya->no_npwp ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Profil Usaha -->
    <div class="section">
        <div class="section-title">PROFIL USAHA</div>
        
        <div class="subsection-title">Informasi Umum</div>
        <table class="info-table">
            <tr>
                <td>Nama Usaha</td>
                <td>: {{ $pembudidaya->nama_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Kelompok</td>
                <td>: {{ $pembudidaya->nama_kelompok ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPWP Usaha</td>
                <td>: {{ $pembudidaya->npwp_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. Telepon Usaha</td>
                <td>: {{ $pembudidaya->telp_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email Usaha</td>
                <td>: {{ $pembudidaya->email_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tahun Mulai Usaha</td>
                <td>: {{ $pembudidaya->tahun_mulai_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status Usaha</td>
                <td>: {{ $pembudidaya->status_usaha ?? '-' }}</td>
            </tr>
        </table>

        <div class="subsection-title">Lokasi Usaha</div>
        <table class="info-table">
            <tr>
                <td>Kecamatan Usaha</td>
                <td>: {{ $pembudidaya->kecamatanUsaha->nama_kecamatan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Desa Usaha</td>
                <td>: {{ $pembudidaya->desaUsaha->nama_desa ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat Lengkap Usaha</td>
                <td>: {{ $pembudidaya->alamat_lengkap_usaha ?? ($pembudidaya->alamat_usaha ?? '-') }}</td>
            </tr>
            <tr>
                <td>Koordinat (Lat, Long)</td>
                <td>: {{ $pembudidaya->latitude_usaha ?? '-' }}, {{ $pembudidaya->longitude_usaha ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Izin Usaha -->
    @php $iz = $pembudidaya->izin; @endphp
    <div class="section">
        <div class="section-title">IZIN USAHA</div>
        @if($iz)
        <table class="info-table">
            <tr>
                <td>NIB</td>
                <td>: {{ $iz->nib ?? '-' }}</td>
            </tr>
            <tr>
                <td>NPWP</td>
                <td>: {{ $iz->npwp ?? '-' }}</td>
            </tr>
            <tr>
                <td>KUSUKA</td>
                <td>: {{ $iz->kusuka ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pengesahan MENKUMHAM</td>
                <td>: {{ $iz->pengesahan_menkumham ?? '-' }}</td>
            </tr>
            <tr>
                <td>CBIB</td>
                <td>: {{ $iz->cbib ?? '-' }}</td>
            </tr>
            <tr>
                <td>SKAI</td>
                <td>: {{ $iz->skai ?? '-' }}</td>
            </tr>
            <tr>
                <td>Surat Ijin Pembudidayaan Ikan</td>
                <td>: {{ $iz->surat_ijin_pembudidayaan_ikan ?? '-' }}</td>
            </tr>
            <tr>
                <td>AKTA PENDIRIAN USAHA</td>
                <td>: {{ $iz->akta_pendirian_usaha ?? '-' }}</td>
            </tr>
            <tr>
                <td>IMB</td>
                <td>: {{ $iz->imb ?? '-' }}</td>
            </tr>
            <tr>
                <td>SUP Perikanan</td>
                <td>: {{ $iz->sup_perikanan ?? '-' }}</td>
            </tr>
            <tr>
                <td>SUP Perdagangan</td>
                <td>: {{ $iz->sup_perdagangan ?? '-' }}</td>
            </tr>
        </table>
        @else
        <p style="padding: 8px; color: #666;">Belum ada data izin usaha.</p>
        @endif
    </div>

    <!-- Investasi -->
    @php $inv = $pembudidaya->investasi; @endphp
    <div class="section">
        <div class="section-title">INVESTASI</div>
        @if($inv)
        <table class="info-table">
            <tr>
                <td>Nilai Asset</td>
                <td>: Rp. {{ number_format($inv->nilai_asset ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Laba Ditanam</td>
                <td>: Rp. {{ number_format($inv->laba_ditanam ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Sewa</td>
                <td>: Rp. {{ number_format($inv->sewa ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pinjaman</td>
                <td>: {{ is_null($inv->pinjaman) ? '-' : ($inv->pinjaman ? 'Ada' : 'Tidak') }}</td>
            </tr>
            <tr>
                <td>Modal Sendiri</td>
                <td>: Rp. {{ number_format($inv->modal_sendiri ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Lahan (Status Kepemilikan)</td>
                <td>: @php 
                    $ls = $inv->lahan_status ?? []; 
                    if (is_string($ls)) { $ls = json_decode($ls, true) ?? []; }
                    if (!is_array($ls)) { $ls = []; }
                @endphp
                {{ $ls ? implode(', ', $ls) : '-' }}</td>
            </tr>
            <tr>
                <td>Luas</td>
                <td>: {{ $inv->luas_m2 ? $inv->luas_m2 . ' m²' : '-' }}</td>
            </tr>
            <tr>
                <td>Nilai Bangunan</td>
                <td>: Rp. {{ number_format($inv->nilai_bangunan ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bangunan</td>
                <td>: {{ $inv->bangunan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Sertifikat</td>
                <td>: {{ $inv->sertifikat === 'IMB' ? 'IMB' : ($inv->sertifikat === 'NON_IMB' ? 'Non IMB' : '-') }}</td>
            </tr>
        </table>
        @else
        <p style="padding: 8px; color: #666;">Belum ada data investasi.</p>
        @endif
    </div>

    <!-- Produksi -->
    @php $prod = $pembudidaya->produksi; @endphp
    <div class="section">
        <div class="section-title">PRODUKSI</div>
        
        @if($prod)
            <div class="subsection-title">Total Keseluruhan</div>
            <table class="info-table">
                <tr>
                    <td>Total Luas Kolam</td>
                    <td>: {{ $prod->total_luas_kolam ?? '-' }} m²</td>
                </tr>
                <tr>
                    <td>Total Produksi</td>
                    <td>: {{ $prod->total_produksi ?? '-' }} {{ $prod->satuan_produksi ?? '' }}</td>
                </tr>
                <tr>
                    <td>Harga per Satuan</td>
                    <td>: Rp. {{ number_format($prod->harga_per_satuan ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>
        @else
            <p style="padding: 8px; color: #666;">Belum ada data produksi.</p>
        @endif

        @if($pembudidaya->kolam->count() > 0)
            <div class="subsection-title">Data Kolam</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis Kolam</th>
                        <th>Ukuran (m²)</th>
                        <th>Jumlah</th>
                        <th>Komoditas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembudidaya->kolam as $kolam)
                    <tr>
                        <td>{{ $kolam->jenis_kolam }}</td>
                        <td>{{ $kolam->ukuran ?? '-' }}</td>
                        <td>{{ $kolam->jumlah ?? '-' }}</td>
                        <td>{{ $kolam->komoditas ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if($pembudidaya->ikan->count() > 0)
            <div class="subsection-title">Data Ikan</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis Ikan</th>
                        <th>Jenis Indukan</th>
                        <th>Jumlah</th>
                        <th>Asal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembudidaya->ikan as $ikan)
                    <tr>
                        <td>{{ $ikan->jenis_ikan }}</td>
                        <td>{{ $ikan->jenis_indukan ?? '-' }}</td>
                        <td>{{ $ikan->jumlah ?? '-' }}</td>
                        <td>{{ $ikan->asal ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Tenaga Kerja -->
    @php $tk = $pembudidaya->tenagaKerja; @endphp
    <div class="section">
        <div class="section-title">TENAGA KERJA</div>
        @if($tk)
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">Kategori</th>
                    <th colspan="3">Laki-laki</th>
                    <th colspan="3">Perempuan</th>
                </tr>
                <tr>
                    <th>Tetap</th>
                    <th>Tidak Tetap</th>
                    <th>Keluarga</th>
                    <th>Tetap</th>
                    <th>Tidak Tetap</th>
                    <th>Keluarga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>WNI</strong></td>
                    <td>{{ $tk->wni_laki_tetap }}</td>
                    <td>{{ $tk->wni_laki_tidak_tetap }}</td>
                    <td>{{ $tk->wni_laki_keluarga }}</td>
                    <td>{{ $tk->wni_perempuan_tetap }}</td>
                    <td>{{ $tk->wni_perempuan_tidak_tetap }}</td>
                    <td>{{ $tk->wni_perempuan_keluarga }}</td>
                </tr>
                <tr>
                    <td><strong>WNA</strong></td>
                    <td>{{ $tk->wna_laki_tetap }}</td>
                    <td>{{ $tk->wna_laki_tidak_tetap }}</td>
                    <td>{{ $tk->wna_laki_keluarga }}</td>
                    <td>{{ $tk->wna_perempuan_tetap }}</td>
                    <td>{{ $tk->wna_perempuan_tidak_tetap }}</td>
                    <td>{{ $tk->wna_perempuan_keluarga }}</td>
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
            @if($pembudidaya->$key)
                @php 
                    $hasLampiran = true;
                    $filePath = str_starts_with($pembudidaya->$key, 'storage/') 
                        ? substr($pembudidaya->$key, 8) 
                        : $pembudidaya->$key;
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
