<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Harga Ikan Segar</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #1e40af; padding-bottom: 10px; }
        .header h1 { font-size: 18px; margin: 5px 0; color: #1e40af; }
        .header p { margin: 3px 0; color: #666; }
        .section { margin-bottom: 15px; page-break-inside: avoid; }
        .section-title { background-color: #1e40af; color: white; padding: 6px 10px; font-size: 13px; font-weight: bold; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.info-table td { padding: 4px 8px; vertical-align: top; }
        table.info-table td:first-child { font-weight: bold; width: 35%; color: #555; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #666; padding: 5px; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL HARGA IKAN SEGAR</h1>
        <p>Dinas Perikanan Kabupaten Jember</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Profil Pasar -->
    <div class="section">
        <div class="section-title">PROFIL PASAR</div>
        <table class="info-table">
            <tr><td>Tanggal Input</td><td>: {{ $harga->tanggal_input ? \Carbon\Carbon::parse($harga->tanggal_input)->translatedFormat('d F Y') : '-' }}</td></tr>
            <tr><td>Nama Pasar</td><td>: {{ $harga->nama_pasar ?? '-' }}</td></tr>
            <tr><td>Nama Pedagang</td><td>: {{ $harga->nama_pedagang ?? '-' }}</td></tr>
            <tr><td>Kecamatan</td><td>: {{ $harga->kecamatan->nama_kecamatan ?? '-' }}</td></tr>
            <tr><td>Desa/Kelurahan</td><td>: {{ $harga->desa->nama_desa ?? '-' }}</td></tr>
            <tr><td>Asal Ikan</td><td>: {{ $harga->asal_ikan ?? '-' }}</td></tr>
            @if($harga->keterangan)
            <tr><td>Keterangan/Catatan Pasar</td><td>: {{ $harga->keterangan }}</td></tr>
            @endif
        </table>
    </div>

    <!-- Detail Ikan -->
    <div class="section">
        <div class="section-title">DETAIL IKAN</div>
        <table class="info-table">
            <tr><td>Jenis Ikan</td><td>: {{ $harga->jenis_ikan ?? '-' }}</td></tr>
            <tr><td>Ukuran</td><td>: {{ $harga->ukuran ?? '-' }}</td></tr>
            <tr><td>Satuan</td><td>: {{ $harga->satuan ?? '-' }}</td></tr>
            <tr><td>Harga Produsen</td><td>: {{ $harga->harga_produsen ? 'Rp ' . number_format($harga->harga_produsen, 0, ',', '.') : '-' }}</td></tr>
            <tr><td>Harga Konsumen</td><td>: {{ $harga->harga_konsumen ? 'Rp ' . number_format($harga->harga_konsumen, 0, ',', '.') : '-' }}</td></tr>
            <tr><td>Kuantitas Perminggu</td><td>: {{ $harga->kuantitas_perminggu ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Informasi Dinas Perikanan Kabupaten Jember</p>
    </div>
</body>
</html>
