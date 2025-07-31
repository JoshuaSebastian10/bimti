<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Bimbingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $jadwalTerdekat = Bimbingan::where('mahasiswa_id', Auth::user()->mahasiswa->id)
        ->where('status', 'disetujui')
        ->where('tanggal_bimbingan', '>=', now())
        ->orderBy('tanggal_bimbingan', 'asc')
        ->first();
        return view('mahasiswa.dashboard', compact('jadwalTerdekat'));
    }
}
