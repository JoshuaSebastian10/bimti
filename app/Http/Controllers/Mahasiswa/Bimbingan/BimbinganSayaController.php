<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BimbinganSayaController extends Controller
{
    public function index(){
        return view('mahasiswa.bimbinganSaya.index');
    }
}
