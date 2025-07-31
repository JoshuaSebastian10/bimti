<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BimbinganSkripsiController extends Controller
{
    public function index()
    {
                if (!Auth::user()->mahasiswa || !Auth::user()->mahasiswa->pembimbing_skripsi_2_id) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Dosen Pembimbing Skripsi 2 Anda belum diatur oleh Admin, Hubungi Admin untuk menambahkan Pembimbing Skripsi 2.');
        }
        return view('mahasiswa.bimbinganSkripsi.index');
    }

    

}
