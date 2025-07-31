<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::whereHas('user', fn($q) => $q->where('status_akun', 'aktif'))->count();
        $totalDosen = Dosen::count();
        $totalBimbingan = Bimbingan::count();
        return view('admin.dashboard', compact('totalBimbingan', 'totalDosen', 'totalMahasiswa'));
    }
}
