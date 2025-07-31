<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class JadwalDosenController extends Controller
{
    public function index(){
        return view('admin.jadwalDosen.index');
    }
}
