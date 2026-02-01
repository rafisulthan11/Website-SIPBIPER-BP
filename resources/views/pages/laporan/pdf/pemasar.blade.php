<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pemasar - {{ $pemasar->nama_lengkap }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #1e40af; padding-bottom: 10px; }
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
        <h1>DETAIL DATA PEMASAR IKAN</h1>
        <p>Dinas Perikanan Kabupaten Jember</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Jenis Usaha -->
    <div class="section">
        <div class="section-title">JENIS USAHA</div>
        <table class="info-table">
            <tr><td>Jenis Kegiatan Usaha</td><td>: {{ $pemasar->jenis_kegiatan_usaha ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Profil Pemilik -->
    <div class="section">
        <div class="section-title">PROFIL PEMILIK</div>
        <table class="info-table">
            <tr><td>Nama Lengkap</td><td>: {{ $pemasar->nama_lengkap ?? '-' }}</td></tr>
            <tr><td>NIK</td><td>: {{ $pemasar->nik_pemasar ?? '-' }}</td></tr>
            <tr><td>Jenis Kelamin</td><td>: {{ $pemasar->jenis_kelamin ?? '-' }}</td></tr>
            <tr><td>Tempat, Tanggal Lahir</td><td>: {{ $pemasar->tempat_lahir ?? '-' }}, {{ $pemasar->tanggal_lahir ? \Carbon\Carbon::parse($pemasar->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</td></tr>
            <tr><td>Pendidikan Terakhir</td><td>: {{ $pemasar->pendidikan_terakhir ?? '-' }}</td></tr>
            <tr><td>Status Perkawinan</td><td>: {{ $pemasar->status_perkawinan ?? '-' }}</td></tr>
            <tr><td>Tahun Mulai Usaha</td><td>: {{ $pemasar->tahun_mulai_usaha ?? '-' }}</td></tr>
            <tr><td>Aset Pribadi</td><td>: {{ $pemasar->aset_pribadi ? 'Rp. ' . number_format($pemasar->aset_pribadi, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Jumlah Tanggungan</td><td>: {{ $pemasar->jumlah_tanggungan ?? '-' }}</td></tr>
            <tr><td>Alamat Lengkap</td><td>: {{ $pemasar->alamat ?? '-' }}</td></tr>
            <tr><td>Kecamatan</td><td>: {{ $pemasar->kecamatan->nama_kecamatan ?? '-' }}</td></tr>
            <tr><td>Desa/Kelurahan</td><td>: {{ $pemasar->desa->nama_desa ?? '-' }}</td></tr>
            <tr><td>No. Telepon/HP</td><td>: {{ $pemasar->kontak ?? '-' }}</td></tr>
            <tr><td>Email</td><td>: {{ $pemasar->email ?? '-' }}</td></tr>
            <tr><td>No. NPWP</td><td>: {{ $pemasar->no_npwp ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Profil Usaha -->
    <div class="section">
        <div class="section-title">PROFIL USAHA</div>
        
        <div class="subsection-title">Informasi Umum</div>
        <table class="info-table">
            <tr><td>Nama Usaha</td><td>: {{ $pemasar->nama_usaha ?? '-' }}</td></tr>
            <tr><td>Nama Kelompok</td><td>: {{ $pemasar->nama_kelompok ?? '-' }}</td></tr>
            <tr><td>NPWP Usaha</td><td>: {{ $pemasar->npwp_usaha ?? '-' }}</td></tr>
            <tr><td>No. Telepon Usaha</td><td>: {{ $pemasar->telp_usaha ?? '-' }}</td></tr>
            <tr><td>Email Usaha</td><td>: {{ $pemasar->email_usaha ?? '-' }}</td></tr>
            <tr><td>Skala Usaha</td><td>: {{ $pemasar->skala_usaha ?? '-' }}</td></tr>
            <tr><td>Status Usaha</td><td>: {{ $pemasar->status_usaha ?? '-' }}</td></tr>
            <tr><td>Tahun Mulai Usaha</td><td>: {{ $pemasar->tahun_mulai_usaha ?? '-' }}</td></tr>
            <tr><td>Komoditas</td><td>: {{ $pemasar->komoditas ?? '-' }}</td></tr>
        </table>

        <div class="subsection-title">Lokasi Usaha</div>
        <table class="info-table">
            <tr><td>Kecamatan</td><td>: {{ $pemasar->kecamatanUsaha->nama_kecamatan ?? '-' }}</td></tr>
            <tr><td>Desa/Kelurahan</td><td>: {{ $pemasar->desaUsaha->nama_desa ?? '-' }}</td></tr>
            <tr><td>Alamat Lengkap Usaha</td><td>: {{ $pemasar->alamat_usaha ?? '-' }}</td></tr>
            <tr><td>Koordinat (Lat, Long)</td><td>: {{ $pemasar->latitude ?? '-' }}, {{ $pemasar->longitude ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Izin Usaha -->
    <div class="section">
        <div class="section-title">IZIN USAHA</div>
        <table class="info-table">
            <tr><td>NIB</td><td>: {{ $pemasar->nib ?? '-' }}</td></tr>
            <tr><td>NPWP</td><td>: {{ $pemasar->npwp_izin ?? '-' }}</td></tr>
            <tr><td>KUSUKA</td><td>: {{ $pemasar->kusuka ?? '-' }}</td></tr>
            <tr><td>Pengesahan MENKUMHAM</td><td>: {{ $pemasar->pengesahan_menkumham ?? '-' }}</td></tr>
            <tr><td>TDU/PHP</td><td>: {{ $pemasar->tdu_php ?? '-' }}</td></tr>
            <tr><td>SPPL</td><td>: {{ $pemasar->sppl ?? '-' }}</td></tr>
            <tr><td>SIUP Perdagangan</td><td>: {{ $pemasar->siup_perdagangan ?? '-' }}</td></tr>
            <tr><td>Akta Pendiri Usaha</td><td>: {{ $pemasar->akta_pendiri_usaha ?? '-' }}</td></tr>
            <tr><td>IMB</td><td>: {{ $pemasar->imb ?? '-' }}</td></tr>
            <tr><td>SIUP Perikanan</td><td>: {{ $pemasar->siup_perikanan ?? '-' }}</td></tr>
            <tr><td>UKL/UPL</td><td>: {{ $pemasar->ukl_upl ?? '-' }}</td></tr>
            <tr><td>AMDAL</td><td>: {{ $pemasar->amdal ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Investasi -->
    <div class="section">
        <div class="section-title">INVESTASI</div>
        
        <!-- Mesin/Peralatan -->
        @if($pemasar->mesin_peralatan && is_array($pemasar->mesin_peralatan) && count($pemasar->mesin_peralatan) > 0)
        <div class="subsection-title">Mesin/Peralatan</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Jenis Mesin</th>
                    <th>Kapasitas</th>
                    <th>Jumlah</th>
                    <th>Asal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasar->mesin_peralatan as $mesin)
                <tr>
                    <td>{{ $mesin['jenis_mesin'] ?? '-' }}</td>
                    <td>{{ $mesin['kapasitas'] ?? '-' }}</td>
                    <td>{{ $mesin['jumlah'] ?? '-' }}</td>
                    <td>{{ $mesin['asal'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- Modal Tetap -->
        <div class="subsection-title">Nilai Investasi (Modal Tetap)</div>
        <table class="info-table">
            <tr><td>Tanah</td><td>: {{ $pemasar->investasi_tanah ? 'Rp. ' . number_format($pemasar->investasi_tanah, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Gedung</td><td>: {{ $pemasar->investasi_gedung ? 'Rp. ' . number_format($pemasar->investasi_gedung, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Mesin & Peralatan</td><td>: {{ $pemasar->investasi_mesin_peralatan ? 'Rp. ' . number_format($pemasar->investasi_mesin_peralatan, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Kendaraan</td><td>: {{ $pemasar->investasi_kendaraan ? 'Rp. ' . number_format($pemasar->investasi_kendaraan, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Lain-lain</td><td>: {{ $pemasar->investasi_lain_lain ? 'Rp. ' . number_format($pemasar->investasi_lain_lain, 2, ',', '.') : '-' }}</td></tr>
            <tr><td><strong>Sub Jumlah</strong></td><td>: <strong>{{ $pemasar->investasi_sub_jumlah ? 'Rp. ' . number_format($pemasar->investasi_sub_jumlah, 2, ',', '.') : '-' }}</strong></td></tr>
        </table>

        <!-- Modal Kerja -->
        <div class="subsection-title">Nilai Investasi (Modal Kerja)</div>
        <table class="info-table">
            <tr><td>1 Bulan</td><td>: {{ $pemasar->modal_kerja_1_bulan ? 'Rp. ' . number_format($pemasar->modal_kerja_1_bulan, 2, ',', '.') : '-' }}</td></tr>
            <tr><td><strong>Sub Jumlah</strong></td><td>: <strong>{{ $pemasar->modal_kerja_sub_jumlah ? 'Rp. ' . number_format($pemasar->modal_kerja_sub_jumlah, 2, ',', '.') : '-' }}</strong></td></tr>
        </table>

        <!-- Sumber Pembiayaan -->
        <div class="subsection-title">Sumber Pembiayaan</div>
        <table class="info-table">
            <tr><td>Modal Sendiri</td><td>: {{ $pemasar->modal_sendiri ? 'Rp. ' . number_format($pemasar->modal_sendiri, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Laba Ditanam Kembali</td><td>: {{ $pemasar->laba_ditanam ? 'Rp. ' . number_format($pemasar->laba_ditanam, 2, ',', '.') : '-' }}</td></tr>
            <tr><td>Modal Pinjaman</td><td>: {{ $pemasar->modal_pinjam ? 'Rp. ' . number_format($pemasar->modal_pinjam, 2, ',', '.') : '-' }}</td></tr>
        </table>
    </div>

    <!-- Sertifikat Lahan -->
    <div class="section">
        <div class="section-title">SERTIFIKAT LAHAN</div>
        <table class="info-table">
            <tr>
                <td>Jenis Sertifikat</td>
                <td>: 
                    @if($pemasar->sertifikat_lahan)
                        @php
                            $sertifikatLahan = json_decode($pemasar->sertifikat_lahan, true);
                        @endphp
                        @if(is_array($sertifikatLahan))
                            {{ implode(', ', $sertifikatLahan) }}
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr><td>Luas Lahan</td><td>: {{ $pemasar->luas_lahan ? $pemasar->luas_lahan . ' m2' : '-' }}</td></tr>
            <tr><td>Nilai</td><td>: {{ $pemasar->nilai_lahan ? 'Rp. ' . number_format($pemasar->nilai_lahan, 2, ',', '.') : '-' }}</td></tr>
        </table>
    </div>

    <!-- Sertifikat Bangunan -->
    <div class="section">
        <div class="section-title">SERTIFIKAT BANGUNAN</div>
        <table class="info-table">
            <tr>
                <td>Jenis Sertifikat</td>
                <td>: 
                    @if($pemasar->sertifikat_bangunan)
                        @php
                            $sertifikatBangunan = json_decode($pemasar->sertifikat_bangunan, true);
                        @endphp
                        @if(is_array($sertifikatBangunan))
                            {{ implode(', ', $sertifikatBangunan) }}
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr><td>Luas Bangunan</td><td>: {{ $pemasar->luas_bangunan ? $pemasar->luas_bangunan . ' m2' : '-' }}</td></tr>
            <tr><td>Nilai</td><td>: {{ $pemasar->nilai_bangunan ? 'Rp. ' . number_format($pemasar->nilai_bangunan, 2, ',', '.') : '-' }}</td></tr>
        </table>
    </div>

    <!-- Kapasitas & Produksi -->
    <div class="section">
        <div class="section-title">KAPASITAS & PRODUKSI</div>
        <table class="info-table">
            <tr><td>Kapasitas Terpasang Setahun</td><td>: {{ $pemasar->kapasitas_terpasang_setahun ? $pemasar->kapasitas_terpasang_setahun . ' Kg' : '-' }}</td></tr>
            <tr><td>Jumlah Hari Produksi/bulan</td><td>: {{ $pemasar->jumlah_hari_produksi ? $pemasar->jumlah_hari_produksi . ' hari' : '-' }}</td></tr>
            <tr>
                <td>Bulan Produksi</td>
                <td>: 
                    @if($pemasar->bulan_produksi)
                        @php
                            $bulanProduksi = json_decode($pemasar->bulan_produksi, true);
                        @endphp
                        @if(is_array($bulanProduksi))
                            {{ implode(', ', $bulanProduksi) }}
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr><td>Distribusi Pemasaran</td><td>: {{ $pemasar->distribusi_pemasaran ?? '-' }}</td></tr>
        </table>
    </div>

    <!-- Data Aset & Kendaraan -->
    @php
        $asetList = [];
        if($pemasar->kapal) $asetList[] = 'Kapal';
        if($pemasar->truk) $asetList[] = 'Truk';
        if($pemasar->mobil_box) $asetList[] = 'Mobil Box';
        if($pemasar->sepeda_motor) $asetList[] = 'Sepeda Motor';
    @endphp
    @if(count($asetList) > 0)
    <div class="section">
        <div class="section-title">ASET & KENDARAAN</div>
        <table class="info-table">
            <tr><td>Jenis Kendaraan yang Dimiliki</td><td>: {{ implode(', ', $asetList) }}</td></tr>
        </table>
    </div>
    @endif

    <!-- Peralatan -->
    @if($pemasar->peralatan && is_array($pemasar->peralatan) && count($pemasar->peralatan) > 0)
    <div class="section">
        <div class="section-title">PERALATAN</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemasar->peralatan as $alat)
                <tr>
                    <td>{{ $alat['nama_alat'] ?? '-' }}</td>
                    <td>{{ $alat['jenis'] ?? '-' }}</td>
                    <td>{{ $alat['jumlah'] ?? '-' }}</td>
                    <td>{{ $alat['satuan'] ?? '-' }}</td>
                    <td>{{ $alat['kondisi'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Tenaga Kerja -->
    @if($pemasar->tenaga_kerja_data && is_array($pemasar->tenaga_kerja_data))
    <div class="section">
        <div class="section-title">TENAGA KERJA</div>
        @php $tk = $pemasar->tenaga_kerja_data; @endphp
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
    </div>
    @endif

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
            @if($pemasar->$key)
                @php 
                    $hasLampiran = true;
                    $filePath = str_starts_with($pemasar->$key, 'storage/') 
                        ? substr($pemasar->$key, 8) 
                        : $pemasar->$key;
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
