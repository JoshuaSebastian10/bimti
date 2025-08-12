<?php

namespace App\Notifications;

use auth;
use App\Models\Bimbingan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifikasiResponMahasiswaKeDosen extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public function __construct(public Bimbingan $bimbingan, public string $respon, public ?string $catatanPenolakan = null) {}

    public function via($notifiable){ return ['database','broadcast']; }

    public function toArray($notifiable)
    {
        return [
            'judul' => $this->respon === 'disetujui' ? 'Perubahan Disetujui Mahasiswa' : 'Perubahan Ditolak Mahasiswa',
            'pesan' => $this->respon === 'disetujui'
                        ? 'Mahasiswa menyetujui usulan perubahan jadwal.'
                        : ('Mahasiswa menolak usulan perubahan. Alasan: '.$this->catatanPenolakan),
            'bimbingan_id' => $this->bimbingan->id,
            'tanggal_baru' => $this->bimbingan->usulan_tanggal_bimbingan ?? $this->bimbingan->tanggal_bimbingan,
            'jam_mulai_baru' => $this->bimbingan->usulan_jam_mulai ?? $this->bimbingan->jam_mulai,
            'jam_selesai_baru' => $this->bimbingan->usulan_jam_selesai ?? $this->bimbingan->jam_selesai,
            'tautan_aksi' => route('dosen.daftar-bimbingan'), // ganti sesuai rute dashboard dosen
            'jenis' => 'respon_mahasiswa',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn()
    {
        return new PrivateChannel('dosen.' . $this->bimbingan->dosen_id);
    }

    public function broadcastAs()
    {
        return 'respon-mahasiswa';
    }


public function responPerubahan(int $bimbinganId, string $respon, ?string $catatan = null)
{
    $b = Bimbingan::where('id', $bimbinganId)
        ->where('mahasiswa_id', auth::user()->mahasiswa->id)
        ->firstOrFail();

    if ($b->status_perubahan !== 'menunggu_mahasiswa') {
        abort(403);
    }

    if ($respon === 'disetujui') {
        $b->update([
            'tanggal_bimbingan' => $b->usulan_tanggal_bimbingan,
            'jam_mulai'         => $b->usulan_jam_mulai,
            'jam_selesai'       => $b->usulan_jam_selesai,
            'status_perubahan'  => 'disetujui_mahasiswa',
        ]);
    } else {
        $b->update([
            'status_perubahan'  => 'ditolak_mahasiswa',
        ]);
    }

    // Notifikasi ke dosen
    $b->dosen->user->notify(
        new NotifikasiResponMahasiswaKeDosen($b, $respon, $catatan)
    );

    session()->flash('success', 'Terima kasih, respons Anda sudah tersimpan.');
}

}
