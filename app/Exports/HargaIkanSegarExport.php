<?php

namespace App\Exports;

use App\Models\HargaIkanSegar;
use App\Models\MasterDesa;
use App\Models\MasterKecamatan;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HargaIkanSegarExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithCustomValueBinder
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
        $query = HargaIkanSegar::with(['kecamatan', 'desa'])
            ->where('status', 'verified'); // Hanya data yang sudah diverifikasi

        // Apply filters
        if (!empty($this->filters['kecamatan'])) {
            $query->where('id_kecamatan', $this->filters['kecamatan']);
        }

        if (!empty($this->filters['jenis_ikan'])) {
            $query->where('jenis_ikan', 'like', '%' . $this->filters['jenis_ikan'] . '%');
        }

        if (!empty($this->filters['nama_pasar'])) {
            $query->where('nama_pasar', 'like', '%' . $this->filters['nama_pasar'] . '%');
        }

        if (!empty($this->filters['bulan'])) {
            $query->whereMonth('tanggal_input', $this->filters['bulan']);
        }

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_pasar', 'like', '%'.$search.'%')
                  ->orWhere('nama_pedagang', 'like', '%'.$search.'%')
                  ->orWhere('jenis_ikan', 'like', '%'.$search.'%');
            });
        }

        // filter single row when id provided
        if (!empty($this->filters['id'])) {
            $query->where('id_harga', $this->filters['id']);
        }

        $verified = $query->get();

        $backup = DB::table('harga_ikan_segar_verified_backup as backup')
            ->join('harga_ikan_segars as current', 'current.id_harga', '=', 'backup.id_harga')
            ->whereIn('current.status', ['pending', 'rejected'])
            ->select('backup.*')
            ->get()
            ->map(function ($row) {
                $data = json_decode($row->data_verified, true);
                if (!is_array($data)) {
                    return null;
                }

                $relations = [];
                foreach (['kecamatan', 'desa'] as $rel) {
                    if (isset($data[$rel])) {
                        $relations[$rel] = $data[$rel];
                        unset($data[$rel]);
                    }
                }

                $harga = new HargaIkanSegar();
                $harga->forceFill($data);
                $harga->exists = true;
                $harga->setAttribute('from_backup_snapshot', true);

                if (!empty($relations['kecamatan'])) {
                    $kecamatan = new MasterKecamatan();
                    $kecamatan->forceFill($relations['kecamatan']);
                    $kecamatan->exists = true;
                    $harga->setRelation('kecamatan', $kecamatan);
                }
                if (!empty($relations['desa'])) {
                    $desa = new MasterDesa();
                    $desa->forceFill($relations['desa']);
                    $desa->exists = true;
                    $harga->setRelation('desa', $desa);
                }

                return $harga;
            })
            ->filter(function ($item) {
                if (!$item) {
                    return false;
                }

                if (!empty($this->filters['kecamatan']) && (string) ($item->id_kecamatan ?? '') !== (string) $this->filters['kecamatan']) {
                    return false;
                }
                if (!empty($this->filters['jenis_ikan']) && !str_contains(strtolower((string) ($item->jenis_ikan ?? '')), strtolower((string) $this->filters['jenis_ikan']))) {
                    return false;
                }
                if (!empty($this->filters['nama_pasar']) && !str_contains(strtolower((string) ($item->nama_pasar ?? '')), strtolower((string) $this->filters['nama_pasar']))) {
                    return false;
                }
                if (!empty($this->filters['bulan'])) {
                    $bulan = (string) ($item->tanggal_input ? Carbon::parse($item->tanggal_input)->format('m') : '');
                    if ($bulan !== str_pad((string) $this->filters['bulan'], 2, '0', STR_PAD_LEFT)) {
                        return false;
                    }
                }
                if (!empty($this->filters['search'])) {
                    $search = strtolower((string) $this->filters['search']);
                    $haystack = strtolower((string) ($item->nama_pasar ?? '')) . ' ' . strtolower((string) ($item->nama_pedagang ?? '')) . ' ' . strtolower((string) ($item->jenis_ikan ?? ''));
                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }
                if (!empty($this->filters['id']) && (string) ($item->id_harga ?? '') !== (string) $this->filters['id']) {
                    return false;
                }

                return true;
            })
            ->values();

        $all = $verified->keyBy('id_harga');
        foreach ($backup as $backupItem) {
            $all[$backupItem->id_harga] = $backupItem;
        }

        return $all->values()->sortBy(function ($item) {
            return sprintf(
                '%s|%s|%s|%s',
                strtolower((string) ($item->nama_pedagang ?? '')),
                strtolower((string) ($item->nik_pedagang ?? '')),
                (string) ($item->tahun_pendataan ?? ''),
                (string) ($item->tanggal_input ?? '')
            );
        })->values();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA PEDAGANG',
            'NIK PEDAGANG',
            'TAHUN PENDATAAN',
            'TANGGAL INPUT',
            'KECAMATAN',
            'DESA',
            'NAMA PASAR',
            'ASAL IKAN',
            'JENIS IKAN',
            'UKURAN',
            'SATUAN',
            'HARGA PRODUSEN',
            'HARGA KONSUMEN',
            'KUANTITAS PERMINGGU',
            'KETERANGAN/CATATAN PASAR',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        static $previousGroupKey = null;
        $no++;

        $groupKey = implode('|', [
            strtolower((string) ($item->nama_pedagang ?? '')),
            strtolower((string) ($item->nik_pedagang ?? '')),
            (string) ($item->tahun_pendataan ?? ''),
        ]);

        $isNewGroup = $previousGroupKey !== $groupKey;
        $previousGroupKey = $groupKey;

        return [
            $no,
            $isNewGroup ? ($item->nama_pedagang ?? '-') : '',
            $isNewGroup ? ($item->nik_pedagang ?? '-') : '',
            $isNewGroup ? ($item->tahun_pendataan ?? '-') : '',
            $item->tanggal_input ? Carbon::parse($item->tanggal_input)->format('d/m/Y') : '-',
            optional($item->kecamatan)->nama_kecamatan ?? '-',
            optional($item->desa)->nama_desa ?? '-',
            $item->nama_pasar ?? '-',
            $item->asal_ikan ?? '-',
            $item->jenis_ikan ?? '-',
            $item->ukuran ?? '-',
            $item->satuan ?? '-',
            is_null($item->harga_produsen) ? '-' : $item->harga_produsen,
            is_null($item->harga_konsumen) ? '-' : $item->harga_konsumen,
            $item->kuantitas_perminggu ?? '-',
            $item->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 15,
            'I' => 10,
        ];
    }
}
