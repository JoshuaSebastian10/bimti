<?php

namespace App\Http\Controllers\Dosen\Bimbingan;

use App\Http\Controllers\Controller;

class daftarBimbinganController extends Controller
{
    public function index(){
        return view('dosen.daftarBimbingan.index');
    }
}
