<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Bimbingan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $jumlahAjuanBaru = Bimbingan::where('dosen_id', Auth::user()->dosen->id)
        ->where('status', 'menunggu')
        ->count();
        return view('dosen.dashboard',compact('jumlahAjuanBaru'));
    }
}
