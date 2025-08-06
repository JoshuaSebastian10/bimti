<?php

namespace App\Console\Commands;

use App\Models\Bimbingan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpirePendingBimbingan extends Command
{
    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'bimbingan:expire-pending';

    /**
     * Deskripsi dari command.
     */
    protected $description = 'Mengubah status bimbingan "menunggu" yang sudah lewat jadwal menjadi "kedaluwarsa"';

    /**
     * Jalankan logika command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa bimbingan yang kedaluwarsa...');

        // Cari bimbingan yang statusnya 'menunggu' DAN tanggal bimbingannya sudah lewat
        $expiredBimbingans = Bimbingan::where('status', 'menunggu')
            ->where('tanggal_bimbingan', '<', Carbon::today())
            ->get();

        if ($expiredBimbingans->isEmpty()) {
            $this->info('Tidak ada bimbingan yang kedaluwarsa ditemukan.');
            return 0; // Selesai
        }

        foreach ($expiredBimbingans as $bimbingan) {
            $bimbingan->status = 'kedaluwarsa'; 
            $bimbingan->save();
            $this->line('Bimbingan #' . $bimbingan->id . ' diubah menjadi kedaluwarsa.');
        }

        $this->info('Pemeriksaan selesai. ' . $expiredBimbingans->count() . ' bimbingan telah diperbarui.');
        return 0;
    }
}