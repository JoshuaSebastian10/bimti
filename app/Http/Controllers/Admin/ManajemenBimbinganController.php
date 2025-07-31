<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ManajemenBimbinganController extends Controller
{
public function index()
{
    return view('admin.manajemenbimbingan.index');
}
}
