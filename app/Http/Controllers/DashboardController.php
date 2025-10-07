<?php

namespace App\Http\Controllers;

use App\Models\Pembudidaya;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Safe counts with fallbacks
        $pembudidayaCount = Pembudidaya::query()->count();

        // If roles not configured, counts will be 0 without error
        $pemasarCount = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'Pemasar');
        })->count();

        $pengolahCount = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'Pengolah');
        })->count();

        return view('dashboard', compact('pembudidayaCount', 'pemasarCount', 'pengolahCount'));
    }
}
