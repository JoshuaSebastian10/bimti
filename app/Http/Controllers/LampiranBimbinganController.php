<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LampiranBimbinganController extends Controller
{
    public function show(Bimbingan $bimbingan)
    {
        $user = Auth::user();
        $jikaMahasiswa = ($user->mahasiswa && $user->mahasiswa->id === $bimbingan->mahasiswa_id);
        $jikaDosen = ($user->dosen && $user->dosen->id === $bimbingan->dosen_id);

        if (!$jikaMahasiswa && !$jikaDosen) {
            abort(403, 'Akses Ditolak');
        }
        if ($bimbingan->lampiran_path && Storage::disk('local')->exists($bimbingan->lampiran_path)) {
            $pathToFile = Storage::disk('local')->path($bimbingan->lampiran_path);
            return response()->file($pathToFile);
        }
        return back()->with('error', 'File lampiran tidak ditemukan.');
    }
}
