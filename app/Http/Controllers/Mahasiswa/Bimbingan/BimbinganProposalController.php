<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BimbinganProposalController extends Controller
{
        public function index()
    {
       
        if (!Auth::user()->mahasiswa || !Auth::user()->mahasiswa->pembimbing_skripsi_1_id) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Dosen Pembimbing Skripsi 1 Anda belum diatur oleh Admin, Hubungi Admin untuk menambahkan Pembimbing Skripsi 1.');
        }

        return view('mahasiswa.bimbinganProposal.index');
    }
}
