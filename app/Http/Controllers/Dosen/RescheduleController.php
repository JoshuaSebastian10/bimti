<?php

namespace App\Http\Controllers\Dosen;

use Carbon\Carbon;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\RescheduleSuggested;


class RescheduleController extends Controller
{
    public function index()
    {
        $rows = Bimbingan::where('dosen_id', Auth::id())
            ->where('status', 'disetujui')
            ->orderBy('tanggal_bimbingan')
            ->orderBy('jam_mulai')
            ->paginate(15);

        return view('lecturer.bimbingan.index', compact('rows'));
    }

    public function create(Bimbingan $bimbingan)
    {
        abort_unless($bimbingan->dosen_id === Auth::id(), 403);
        return view('lecturer.reschedule.create', ['b' => $bimbingan]);
    }

    public function store(Request $r, Bimbingan $bimbingan)
    {
        abort_unless($bimbingan->dosen_id === Auth::id(), 403);

        $r->validate([
            'new_at' => ['required','date'],  // input datetime-local (WITA)
            'note'   => ['nullable','string','max:250'],
        ]);

        // Cutoff H-24 dari jadwal lama
        if ($bimbingan->scheduled_at && $bimbingan->scheduled_at->diffInHours(now()->utc()) < 24) {
            return back()->with('error','Tidak bisa ubah < H-24 dari jadwal lama. Batalkan saja.');
        }
        // Satu proposal aktif
        if ($bimbingan->reschedule_status === 'PENDING') {
            return back()->with('error','Masih ada usulan yang menunggu.');
        }

        // Konversi input (WITA) → tanggal & jam reschedule
        $newAtLocal = Carbon::parse($r->input('new_at'), 'Asia/Makassar');
        $dur = $bimbingan->duration_minutes ?? 60; // fallback 60 menit kalau kosong
        $newEndLocal = $newAtLocal->copy()->addMinutes($dur);

        // Simpan proposal ke kolom reschedule_*
        $bimbingan->reschedule_tanggal      = $newAtLocal->toDateString();     // YYYY-MM-DD
        $bimbingan->reschedule_jam_mulai    = $newAtLocal->format('H:i:s');    // HH:MM:SS
        $bimbingan->reschedule_jam_selesai  = $newEndLocal->format('H:i:s');   // HH:MM:SS
        $bimbingan->reschedule_expires_at   = now()->utc()->addDay();          // tenggat balasan
        $bimbingan->reschedule_status       = 'PENDING';
        $bimbingan->save();

        // Kirim notif ke MAHASISWA (ingat: Bimbingan -> mahasiswa -> user)
        $msg = 'Dosen mengusulkan perubahan: '.
            $bimbingan->scheduled_at?->timezone('Asia/Makassar')->format('d M Y H:i').
            ' → '.$bimbingan->reschedule_suggested_at?->timezone('Asia/Makassar')->format('d M Y H:i').
            ($r->filled('note') ? ' (Catatan: '.$r->input('note').')' : '');

        // asumsi: relasi Mahasiswa -> user() tersedia
        $bimbingan->mahasiswa?->user?->notify(new RescheduleSuggested($bimbingan, $msg));

        return redirect()->route('lecturer.bimbingan.index')->with('success','Usulan perubahan dikirim.');
    }
}