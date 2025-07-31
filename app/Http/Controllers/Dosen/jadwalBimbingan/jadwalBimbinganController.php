<?php

namespace App\Http\Controllers\Dosen\jadwalBimbingan;

use Illuminate\Http\Request;
use App\Models\Jadwal_bimbingan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class jadwalBimbinganController extends Controller
{
    public function index()
{
    return view('dosen.jadwalBimbingan.index');
}
}
