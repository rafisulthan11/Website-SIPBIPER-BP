<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function trenHargaKomoditas()
    {
        return view('pages.grafik.tren-harga-komoditas');
    }

    public function hargaIkanSegar()
    {
        return view('pages.grafik.harga-ikan-segar');
    }

    public function pendataanWilayah()
    {
        return view('pages.grafik.pendataan-wilayah');
    }
}
