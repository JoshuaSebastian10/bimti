<?php

namespace App\Http\Controllers\Dosen;

use App\Exports\BimbinganExport; // Kita akan buat ini nanti
use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanBimbinganController extends Controller
{
    public function export(Request $request)
    {
        $dosenId = Auth::user()->dosen->id;
        $dosenNama = Auth::user()->name;
        $format = $request->input('format', 'pdf'); // Default ke pdf

        // 1. Ambil data bimbingan dengan filter
        $bimbinganQuery = Bimbingan::query()
            ->where('dosen_id', $dosenId)
            ->with(['mahasiswa.user']);

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

        $bimbingans = $bimbinganQuery->orderBy('tanggal_bimbingan', 'asc')->get();

        // 2. Generate file berdasarkan format
        if ($format === 'pdf') {
            // Generate PDF
            $pdf = Pdf::loadView('dosen.laporan.pdf', [
                'bimbingans' => $bimbingans,
                'dosenNama' => $dosenNama,
                'periodeMulai' => $request->tanggal_mulai,
                'periodeSelesai' => $request->tanggal_selesai,
            ]);
            return $pdf->stream('laporan-bimbingan-' . time() . '.pdf');
        
        } elseif ($format === 'excel') {
            // Generate Excel
            return Excel::download(new BimbinganExport($bimbingans), 'laporan-bimbingan-' . time() . '.xlsx');
        }
    }
}