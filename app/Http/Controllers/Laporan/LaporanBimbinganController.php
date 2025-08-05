<?php

namespace App\Http\Controllers\Laporan;

use App\Models\Bimbingan;
use App\Exports\BimbinganExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanBimbinganController extends Controller
{
    public function export(Request $request)
{
    $user = Auth::user();
    $bimbinganQuery = Bimbingan::query()->with(['mahasiswa.user', 'dosen.user']);
    $format = $request->input('format', 'pdf'); // Default pdf

    // TAHAP 1: Filter berdasarkan hak akses (Role)
    if ($user->hasRole('dosen')) {
        $bimbinganQuery->where('dosen_id', $user->dosen->id);
    } 
    elseif ($user->hasRole('mahasiswa')) {
        $bimbinganQuery->where('mahasiswa_id', $user->mahasiswa->id);
    }
    else{
        abort(403, 'Anda tidak memiliki hak akses untuk membuat laporan ini.');
    }
    // Jika admin, tidak ada filter tambahan, jadi dia bisa melihat semua.

    // TAHAP 2: Terapkan filter dari input modal (kode Anda yang sudah benar)
    // Filter Tanggal
    if ($request->filled('tanggal_mulai')) {
        $bimbinganQuery->whereDate('tanggal_bimbingan', '>=', $request->tanggal_mulai);
    }
    if ($request->filled('tanggal_selesai')) {
        $bimbinganQuery->whereDate('tanggal_bimbingan', '<=', $request->tanggal_selesai);
    }

    // Filter Status
    $status = $request->input('status');
    if ($status && $status != 'semua') {
        if ($status == 'aktif') {
            $bimbinganQuery->whereIn('status', ['menunggu', 'disetujui']);
        } else {
            $bimbinganQuery->where('status', $status);
        }
    }

        // =======================================================

        // Terapkan filter dari form (tanggal, status, dll.)
        // ... (logika filter Anda yang sudah ada) ...

        // Ambil data dari database
        $bimbingans = $bimbinganQuery->orderBy('tanggal_bimbingan', 'asc')->get();
        
        // Buat nama file dinamis
        $fileName = 'laporan-bimbingan-' . strtolower($user->getRoleNames()->first()) . '-' . time() . '.pdf';

        // Generate PDF (atau Excel)
        if($format === 'pdf'){
        $pdf = Pdf::loadView('laporan.pdf', [
            'bimbingans' => $bimbingans,
            'user' => $user,
            
        ]);
         return $pdf->stream($fileName);
        }elseif($format === 'excel'){
            return Excel::download(new BimbinganExport($bimbingans), 'laporan-bimbingan-' . time() . '.xlsx');
        }
    }
}