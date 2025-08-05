<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BimbinganAkademikController extends Controller
{

    public function index()
    {
       
        if (!Auth::user()->mahasiswa || !Auth::user()->mahasiswa->dosen_pa_id) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Dosen Pembimbing Akademik Anda belum diatur oleh admin.');
        }

        return view('mahasiswa.bimbinganAkademik.index');
    }
}