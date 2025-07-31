<?php

namespace App\Http\Controllers\Dosen\Bimbingan;

use App\Models\Bimbingan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class mahasiswaBimbinganController extends Controller
{
    public function index(){
        

        return view('dosen.mahasiswaBimbingan.index');
    }

        public function detail(Mahasiswa $mahasiswa)
    {
        $dosenId = Auth::user()->dosen->id;

        if (
            $mahasiswa->dosen_pa_id !== $dosenId &&
            $mahasiswa->pembimbing_skripsi_1_id !== $dosenId &&
            $mahasiswa->pembimbing_skripsi_2_id !== $dosenId
        ) {
            abort(403, 'Anda tidak berhak melihat detail progres mahasiswa ini.');
        }

        
        $semuaBimbingan = $mahasiswa->bimbingan()
            ->where('dosen_id', $dosenId)
            ->orderBy('tanggal_bimbingan', 'desc')
            ->get();

        $stats = [
            'akademik' => [
                'total' => $semuaBimbingan->where('jenis_bimbingan', 'akademik')->count(),
                'selesai' => $semuaBimbingan->where('jenis_bimbingan', 'akademik')->where('status', 'selesai')->count(),
                'ditolak' => $semuaBimbingan->where('jenis_bimbingan', 'akademik')->where('status', 'ditolak')->count(),
            ],
            'skripsi' => [
                'total' => $semuaBimbingan->where('jenis_bimbingan', 'skripsi')->count(),
                'selesai' => $semuaBimbingan->where('jenis_bimbingan', 'skripsi')->where('status', 'selesai')->count(),
                'ditolak' => $semuaBimbingan->where('jenis_bimbingan', 'skripsi')->where('status', 'ditolak')->count(),
            ]
        ];

        return view('dosen.mahasiswaBimbingan.detail', [
            'mahasiswa' => $mahasiswa,
            'semuaBimbingan' => $semuaBimbingan,
            'stats' => $stats
        ]);
    }
}
