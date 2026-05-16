<?php

namespace App\Exports;

use App\Models\Pembudidaya;
use App\Models\MasterDesa;
use App\Models\MasterKecamatan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiPembudidayaExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomValueBinder
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value) && preg_match('/^\d{16,}$/', $value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function collection()
    {
        $query = Pembudidaya::with(['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha', 'izin', 'investasi', 'produksi', 'kolam', 'ikan', 'tenagaKerja'])
            ->where('status', 'verified'); // Hanya data yang sudah diverifikasi

        // Apply filters - sama seperti di controller
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['komoditas'])) {
            $query->whereHas('ikan', function($q) {
                $q->where('jenis_ikan', $this->filters['komoditas']);
            });
        }

        if (!empty($this->filters['kategori'])) {
            $query->where('jenis_kegiatan_usaha', $this->filters['kategori']);
        }

        if (!empty($this->filters['jenis_kegiatan_usaha'])) {
            $query->where('jenis_kegiatan_usaha', $this->filters['jenis_kegiatan_usaha']);
        }

        // Filter berdasarkan tahun pendataan
        if (!empty($this->filters['tahun'])) {
            $query->where('tahun_pendataan', (int) $this->filters['tahun']);
        }

        // Filter pembudidaya yang memiliki produksi di bulan tertentu
        if (!empty($this->filters['bulan'])) {
            $query->whereHas('produksi', function($q) {
                $q->where('bulan', $this->filters['bulan']);
            });
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%'.$search.'%')
                  ->orWhere('nama_usaha', 'like', '%'.$search.'%')
                  ->orWhere('jenis_kegiatan_usaha', 'like', '%'.$search.'%');
            });
        }

        $verified = $query->get();

        $backup = DB::table('pembudidaya_verified_backup as backup')
            ->join('pembudidayas as current', 'current.id_pembudidaya', '=', 'backup.id_pembudidaya')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function ($row) {
                $data = json_decode($row->data_verified, true);
                if (!is_array($data)) {
                    return null;
                }

                $relationships = [];
                foreach (['kecamatan', 'desa', 'kecamatanUsaha', 'desaUsaha', 'izin', 'investasi', 'produksi', 'kolam', 'ikan', 'tenagaKerja'] as $rel) {
                    if (isset($data[$rel])) {
                        $relationships[$rel] = $data[$rel];
                        unset($data[$rel]);
                    }
                }

                $item = new Pembudidaya();
                $item->forceFill($data);
                $item->exists = true;
                $item->setAttribute('from_backup_snapshot', true);

                if (!empty($relationships['kecamatan'])) {
                    $kecamatan = new MasterKecamatan();
                    $kecamatan->forceFill($relationships['kecamatan']);
                    $kecamatan->exists = true;
                    $item->setRelation('kecamatan', $kecamatan);
                }
                if (!empty($relationships['desa'])) {
                    $desa = new MasterDesa();
                    $desa->forceFill($relationships['desa']);
                    $desa->exists = true;
                    $item->setRelation('desa', $desa);
                }
                if (!empty($relationships['kecamatanUsaha'])) {
                    $kecamatanUsaha = new MasterKecamatan();
                    $kecamatanUsaha->forceFill($relationships['kecamatanUsaha']);
                    $kecamatanUsaha->exists = true;
                    $item->setRelation('kecamatanUsaha', $kecamatanUsaha);
                }
                if (!empty($relationships['desaUsaha'])) {
                    $desaUsaha = new MasterDesa();
                    $desaUsaha->forceFill($relationships['desaUsaha']);
                    $desaUsaha->exists = true;
                    $item->setRelation('desaUsaha', $desaUsaha);
                }

                $singleMap = [
                    'izin' => \App\Models\PembudidayaIzin::class,
                    'investasi' => \App\Models\PembudidayaInvestasi::class,
                    'tenagaKerja' => \App\Models\PembudidayaTenagaKerja::class,
                ];
                foreach ($singleMap as $rel => $class) {
                    if (!empty($relationships[$rel]) && is_array($relationships[$rel])) {
                        $model = new $class();
                        $model->forceFill($relationships[$rel]);
                        $model->exists = true;
                        $item->setRelation($rel, $model);
                    }
                }

                $listMap = [
                    'produksi' => \App\Models\PembudidayaProduksi::class,
                    'kolam' => \App\Models\PembudidayaKolam::class,
                    'ikan' => \App\Models\PembudidayaIkan::class,
                ];
                foreach ($listMap as $rel => $class) {
                    if (!empty($relationships[$rel])) {
                        $rows = is_array($relationships[$rel]) ? (array_is_list($relationships[$rel]) ? $relationships[$rel] : [$relationships[$rel]]) : [];
                        $collection = collect($rows)->map(function ($row) use ($class) {
                            $model = new $class();
                            $model->forceFill((array) $row);
                            $model->exists = true;
                            return $model;
                        });
                        $item->setRelation($rel, $collection);
                    }
                }

                return $item;
            })
            ->filter(function ($item) {
                if (!$item) {
                    return false;
                }

                if (!empty($this->filters['kecamatan']) && (string) ($item->id_kecamatan ?? '') !== (string) $this->filters['kecamatan']) {
                    return false;
                }
                if (!empty($this->filters['kategori']) && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $this->filters['kategori']) {
                    return false;
                }
                if (!empty($this->filters['jenis_kegiatan_usaha']) && (string) ($item->jenis_kegiatan_usaha ?? '') !== (string) $this->filters['jenis_kegiatan_usaha']) {
                    return false;
                }
                if (!empty($this->filters['tahun']) && (int) ($item->tahun_pendataan ?? 0) !== (int) $this->filters['tahun']) {
                    return false;
                }
                if (!empty($this->filters['search'])) {
                    $search = strtolower((string) $this->filters['search']);
                    $haystack = strtolower((string) ($item->nama_lengkap ?? '')) . ' ' . strtolower((string) ($item->nama_usaha ?? '')) . ' ' . strtolower((string) ($item->jenis_kegiatan_usaha ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }
                if (!empty($this->filters['komoditas'])) {
                    $ikanRows = collect($item->ikan ?? []);
                    $hasKomoditas = $ikanRows->contains(function ($ikan) {
                        return (string) ($ikan->jenis_ikan ?? '') === (string) $this->filters['komoditas'];
                    });
                    if (!$hasKomoditas) {
                        return false;
                    }
                }
                if (!empty($this->filters['bulan'])) {
                    $produksiRows = collect($item->produksi ?? []);
                    $hasBulan = $produksiRows->contains(function ($row) {
                        return (string) ($row->bulan ?? '') === (string) $this->filters['bulan'];
                    });
                    if (!$hasBulan) {
                        return false;
                    }
                }

                return true;
            })
            ->values();

        $pembudidayas = $verified->keyBy('id_pembudidaya');
        foreach ($backup as $backupItem) {
            $pembudidayas[$backupItem->id_pembudidaya] = $backupItem;
        }
        $pembudidayas = $pembudidayas
            ->values()
            ->sortBy(function ($item) {
                return sprintf('%s|%s', $item->nik_pembudidaya ?? '', $item->tahun_pendataan ?? '');
            })
            ->values();

        // Hitung total produksi dan luas kolam untuk setiap pembudidaya
        $komoditasFilter = !empty($this->filters['komoditas']) ? strtolower(trim((string) $this->filters['komoditas'])) : null;

        $pembudidayas->each(function($item) use ($komoditasFilter) {
            $normalizeText = function ($value): string {
                return strtolower(trim((string) $value));
            };

            $isBackup = (bool) ($item->from_backup_snapshot ?? false);
            $kolams = collect($item->kolam ?? []);
            $ikans = collect($item->ikan ?? []);

            if ($komoditasFilter) {
                $matchedKolams = $kolams
                    ->filter(function ($kolam) use ($normalizeText, $komoditasFilter) {
                        return $normalizeText(data_get($kolam, 'komoditas')) === $komoditasFilter;
                    })
                    ->groupBy(function ($kolam) use ($normalizeText) {
                        return $normalizeText(data_get($kolam, 'komoditas')) . '|' . $normalizeText(data_get($kolam, 'jenis_kolam'));
                    })
                    ->map->first()
                    ->values();

                $item->detail_kolam = $matchedKolams;
                $item->total_luas_kolam = $matchedKolams->sum(function ($kolam) {
                    $ukuran = floatval(data_get($kolam, 'ukuran', 0));
                    $jumlah = floatval(data_get($kolam, 'jumlah', 0));
                    return $ukuran * $jumlah;
                });

                $matchedIkans = $ikans
                    ->filter(function ($ikan) use ($normalizeText, $komoditasFilter) {
                        return $normalizeText(data_get($ikan, 'jenis_ikan')) === $komoditasFilter;
                    })
                    ->values();

                $item->detail_produksi = $matchedIkans;
                $item->total_produksi = $matchedIkans->sum(function ($ikan) {
                    return floatval(data_get($ikan, 'jumlah', 0));
                });

                return;
            }

            // Ambil semua data produksi
            if ($isBackup) {
                $produksiRows = collect($item->produksi ?? []);
                if (!empty($this->filters['bulan'])) {
                    $produksiRows = $produksiRows->filter(function ($row) {
                        return (string) ($row->bulan ?? '') === (string) $this->filters['bulan'];
                    })->values();
                }
                $item->detail_produksi = $produksiRows;
                $item->total_produksi = $produksiRows->sum(function ($row) {
                    return floatval($row->total_produksi ?? 0);
                });

                $item->detail_kolam = $kolams;
            } else {
                $produksiQuery = DB::table('pembudidaya_produksis')
                    ->where('id_pembudidaya', $item->id_pembudidaya);

                if (!empty($this->filters['bulan'])) {
                    $produksiQuery->where('bulan', $this->filters['bulan']);
                }

                $item->detail_produksi = $produksiQuery->get();
                $item->total_produksi = $item->detail_produksi->sum('total_produksi');

                $item->detail_kolam = DB::table('pembudidaya_kolams')
                    ->where('id_pembudidaya', $item->id_pembudidaya)
                    ->get();
            }

            $item->total_luas_kolam = $item->detail_kolam->sum(function ($kolam) {
                $ukuran = floatval(is_array($kolam) ? ($kolam['ukuran'] ?? 0) : ($kolam->ukuran ?? 0));
                $jumlah = floatval(is_array($kolam) ? ($kolam['jumlah'] ?? 0) : ($kolam->jumlah ?? 0));
                return $ukuran * $jumlah;
            });
        });

        return $pembudidayas;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA LENGKAP',
            'NIK',
            'TAHUN PENDATAAN',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'STATUS PERKAWINAN',
            'ALAMAT LENGKAP',
            'KECAMATAN',
            'DESA',
            'NO TELEPON/HP',
            'EMAIL',
            'NO NPWP',
            'NAMA USAHA',
            'NAMA KELOMPOK',
            'NPWP USAHA',
            'NO TELP USAHA',
            'EMAIL USAHA',
            'TAHUN MULAI USAHA',
            'STATUS USAHA',
            'KECAMATAN USAHA',
            'DESA USAHA',
            'LATITUDE USAHA',
            'LONGITUDE USAHA',
            'ALAMAT LENGKAP USAHA',
            'JENIS KEGIATAN USAHA',
            'JENIS BUDIDAYA',
            'IZIN NIB',
            'IZIN NPWP',
            'IZIN KUSUKA',
            'IZIN PENGESAHAN MENKUMHAM',
            'IZIN CBIB',
            'IZIN SKAI',
            'IZIN SURAT PEMBUDIDAYAAN IKAN',
            'IZIN AKTA PENDIRIAN',
            'IZIN IMB',
            'IZIN SIUP PERIKANAN',
            'IZIN SIUP PERDAGANGAN',
            'INVESTASI NILAI ASSET',
            'INVESTASI LABA DITANAM',
            'INVESTASI SEWA',
            'INVESTASI PINJAMAN',
            'INVESTASI MODAL SENDIRI',
            'INVESTASI STATUS LAHAN',
            'INVESTASI LUAS LAHAN (M²)',
            'INVESTASI NILAI BANGUNAN',
            'INVESTASI BANGUNAN',
            'INVESTASI SERTIFIKAT',
            'JUMLAH KOLAM',
            'DETAIL KOLAM (Jenis | Ukuran | Jumlah | Komoditas)',
            'DETAIL PRODUKSI (Bulan | Tahun | Total Produksi | Satuan | Harga)',
            'TOTAL PRODUKSI (KG)',
            'TOTAL LUAS KOLAM (M²)',
            'JUMLAH JENIS IKAN',
            'DETAIL IKAN',
            'TENAGA KERJA',
            'LAMPIRAN FILES',
        ];
    }

    public function map($pembudidaya): array
    {
        static $no = 0;
        static $previousNIK = null;
        $no++;

        // Cek apakah NIK ini berbeda dari sebelumnya
        $isNewNIK = ($previousNIK !== $pembudidaya->nik_pembudidaya);
        $previousNIK = $pembudidaya->nik_pembudidaya;

        $izin = $pembudidaya->izin;
        $inv = $pembudidaya->investasi;

        // Detail Kolam - format yang lebih rinci
        $kolamDetail = collect($pembudidaya->detail_kolam ?? [])->map(function($k) {
            $ukuran = floatval($k->ukuran ?? 0);
            $jumlah = intval($k->jumlah ?? 0);
            $luasKolam = $ukuran * $jumlah;
            return implode(' | ', [
                'Jenis: ' . ($k->jenis_kolam ?? '-'),
                'Ukuran: ' . number_format($ukuran, 2, ',', '.') . ' m²',
                'Jumlah Kolam: ' . $jumlah,
                'Luas Total: ' . number_format($luasKolam, 2, ',', '.') . ' m²',
                'Komoditas: ' . ($k->komoditas ?? '-')
            ]);
        })->implode(' ; ');

        // Detail Produksi - format yang lebih rinci
        $produksiDetail = collect($pembudidaya->detail_produksi ?? [])->map(function($p) {
            return implode(' | ', [
                'Bulan: ' . ($p->bulan ?? '-'),
                'Tahun: ' . ($p->tahun ?? '-'),
                'Total Produksi: ' . number_format($p->total_produksi ?? 0, 2, ',', '.') . ' kg',
                'Satuan: ' . ($p->satuan_produksi ?? '-'),
                'Total Luas Kolam: ' . number_format($p->total_luas_kolam ?? 0, 2, ',', '.') . ' m²',
                'Harga: Rp ' . number_format($p->harga_per_satuan ?? 0, 0, ',', '.')
            ]);
        })->implode(' ; ');

        // Ikan Summary
        $ikanSummary = collect($pembudidaya->ikan)->map(function($i) {
            return implode(' | ', [
                'Jenis: ' . ($i->jenis_ikan ?? '-'),
                'Indukan: ' . ($i->jenis_indukan ?? '-'),
                'Jumlah Produksi: ' . ($i->jumlah ?? '-'),
                'Asal: ' . ($i->asal ?? '-')
            ]);
        })->implode(' ; ');

        // Lampiran files
        $lampiranKeys = ['foto_ktp','foto_sertifikat','foto_cpib_cbib','foto_unit_usaha','foto_kusuka','foto_nib'];
        $lampiranFiles = [];
        foreach($lampiranKeys as $k){
            if (!empty($pembudidaya->{$k})) {
                $lampiranFiles[] = $pembudidaya->{$k};
            }
        }

        // Tenaga Kerja Summary
        $tenaga = $pembudidaya->tenagaKerja;
        $tkSummary = '-';
        if ($tenaga) {
            $tkSummary = json_encode([
                'WNI Laki-laki' => [
                    'Tetap' => $tenaga->wni_laki_tetap ?? 0,
                    'Tidak Tetap' => $tenaga->wni_laki_tidak_tetap ?? 0,
                    'Keluarga' => $tenaga->wni_laki_keluarga ?? 0
                ],
                'WNI Perempuan' => [
                    'Tetap' => $tenaga->wni_perempuan_tetap ?? 0,
                    'Tidak Tetap' => $tenaga->wni_perempuan_tidak_tetap ?? 0,
                    'Keluarga' => $tenaga->wni_perempuan_keluarga ?? 0
                ],
                'WNA Laki-laki' => [
                    'Tetap' => $tenaga->wna_laki_tetap ?? 0,
                    'Tidak Tetap' => $tenaga->wna_laki_tidak_tetap ?? 0,
                    'Keluarga' => $tenaga->wna_laki_keluarga ?? 0
                ],
                'WNA Perempuan' => [
                    'Tetap' => $tenaga->wna_perempuan_tetap ?? 0,
                    'Tidak Tetap' => $tenaga->wna_perempuan_tidak_tetap ?? 0,
                    'Keluarga' => $tenaga->wna_perempuan_keluarga ?? 0
                ]
            ], JSON_UNESCAPED_UNICODE);
        }

        return [
            $no,
            $isNewNIK ? ($pembudidaya->nama_lengkap ?? '-') : '',
            $isNewNIK ? (string) ($pembudidaya->getRawOriginal('nik_pembudidaya') ?? $pembudidaya->nik_pembudidaya ?? $pembudidaya->nik ?? '-') : '',
            $pembudidaya->tahun_pendataan ?? '-',
            $isNewNIK ? ($pembudidaya->jenis_kelamin ?? '-') : '',
            $isNewNIK ? ($pembudidaya->tempat_lahir ?? '-') : '',
            $isNewNIK ? ($pembudidaya->tanggal_lahir ? Carbon::parse($pembudidaya->tanggal_lahir)->format('d-m-Y') : '-') : '',
            $isNewNIK ? ($pembudidaya->status_perkawinan ?? '-') : '',
            $isNewNIK ? ($pembudidaya->alamat ?? '-') : '',
            $isNewNIK ? (optional($pembudidaya->kecamatan)->nama_kecamatan ?? '-') : '',
            $isNewNIK ? (optional($pembudidaya->desa)->nama_desa ?? '-') : '',
            $isNewNIK ? ($pembudidaya->kontak ?? ($pembudidaya->no_hp ?? '-')) : '',
            $isNewNIK ? ($pembudidaya->email ?? '-') : '',
            $isNewNIK ? ($pembudidaya->no_npwp ?? '-') : '',
            $pembudidaya->nama_usaha ?? '-',
            $pembudidaya->nama_kelompok ?? '-',
            $pembudidaya->npwp_usaha ?? '-',
            $pembudidaya->telp_usaha ?? '-',
            $pembudidaya->email_usaha ?? '-',
            $pembudidaya->tahun_mulai_usaha ?? '-',
            $pembudidaya->status_usaha ?? '-',
            optional($pembudidaya->kecamatanUsaha)->nama_kecamatan ?? '-',
            optional($pembudidaya->desaUsaha)->nama_desa ?? '-',
            $pembudidaya->latitude_usaha ?? '-',
            $pembudidaya->longitude_usaha ?? '-',
            $pembudidaya->alamat_lengkap_usaha ?? ($pembudidaya->alamat_usaha ?? '-'),
            $pembudidaya->jenis_kegiatan_usaha ?? '-',
            $pembudidaya->jenis_budidaya ?? '-',
            $izin->nib ?? '-',
            $izin->npwp ?? '-',
            $izin->kusuka ?? '-',
            $izin->pengesahan_menkumham ?? '-',
            $izin->cbib ?? '-',
            $izin->skai ?? '-',
            $izin->surat_ijin_pembudidayaan_ikan ?? '-',
            $izin->akta_pendirian_usaha ?? '-',
            $izin->imb ?? '-',
            $izin->sup_perikanan ?? '-',
            $izin->sup_perdagangan ?? '-',
            is_null($inv->nilai_asset) ? '-' : $inv->nilai_asset,
            is_null($inv->laba_ditanam) ? '-' : $inv->laba_ditanam,
            is_null($inv->sewa) ? '-' : $inv->sewa,
            is_null($inv->pinjaman) ? '-' : $inv->pinjaman,
            is_null($inv->modal_sendiri) ? '-' : $inv->modal_sendiri,
            $inv->status_lahan ?? '-',
            is_null($inv->luas_lahan) ? '-' : $inv->luas_lahan,
            is_null($inv->nilai_bangunan) ? '-' : $inv->nilai_bangunan,
            $inv->bangunan ?? '-',
            $inv->sertifikat ?? '-',
            count($pembudidaya->detail_kolam ?? []),
            $kolamDetail ?: '-',
            $produksiDetail ?: '-',
            number_format($pembudidaya->total_produksi ?? 0, 2, ',', '.'),
            number_format($pembudidaya->total_luas_kolam ?? 0, 2, ',', '.'),
            $pembudidaya->ikan->count() ?? 0,
            $ikanSummary ?: '-',
            $tkSummary,
            implode(' ; ', $lampiranFiles) ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4299E1']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }
}
