<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembudidaya;
use App\Models\MasterKecamatan;

class PetaLokasiController extends Controller
{
    /**
     * Display peta interaktif pembudidaya
     */
    public function index()
    {
        // Ambil data pembudidaya dengan koordinat
        $pembudidayas = Pembudidaya::with(['kecamatan', 'desa'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_pembudidaya,
                    'nama' => $item->nama_lengkap,
                    'nik' => $item->nik_pembudidaya,
                    'kecamatan_id' => $item->id_kecamatan,
                    'kecamatan' => [
                        'nama' => $item->kecamatan->nama_kecamatan ?? null,
                    ],
                    'desa' => [
                        'nama' => $item->desa->nama_desa ?? null,
                    ],
                    'alamat' => $item->alamat,
                    'komoditas' => $item->komoditas,
                    'jenis_budidaya' => $item->jenis_budidaya,
                    'skala_usaha' => $item->skala_usaha,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                    'type' => 'pembudidaya'
                ];
            });

        // Ambil daftar kecamatan untuk filter
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        return view('pages.peta-lokasi.index', compact('pembudidayas', 'kecamatans'));
    }

    /**
     * Display peta interaktif pengolah
     */
    public function pengolah()
    {
        // Jika model Pengolah belum ada, kembalikan koleksi kosong
        // Tetap kirimkan variabel $pengolahs agar view bisa mengaksesnya
        $pengolahs = collect([]);
        $pembudidayas = collect([]);
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        return view('pages.peta-lokasi.pengolah', compact('pengolahs', 'pembudidayas', 'kecamatans'));
    }

    /**
     * Display peta interaktif pemasar
     */
    public function pemasar()
    {
        // Jika model Pemasar belum ada, kembalikan koleksi kosong
        // Tetap kirimkan variabel $pemasars agar view bisa mengaksesnya
        $pemasars = collect([]);
        $pembudidayas = collect([]);
        $kecamatans = MasterKecamatan::orderBy('nama_kecamatan')->get();

        return view('pages.peta-lokasi.pemasar', compact('pemasars', 'pembudidayas', 'kecamatans'));
    }
}
