<?php

namespace App\Notifications;

use App\Models\Bimbingan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class NotifikasiPerubahanOtomatisKeMahasiswa extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable, SerializesModels;

    public function __construct(public Bimbingan $bimbingan, public string $alasan)
    {
        $this->afterCommit = true;
    }

    public function via($notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'jenis'             => 'perubahan_otomatis',
            'judul'             => 'Jadwal Diubah oleh Dosen',
            'pesan'             => 'Jadwal bimbingan Anda telah diubah. Alasan: '.$this->alasan,
            'bimbingan_id'      => $this->bimbingan->id,
            'tanggal_baru'      => $this->bimbingan->tanggal_bimbingan,
            'jam_mulai_baru'    => $this->bimbingan->jam_mulai,
            'jam_selesai_baru'  => $this->bimbingan->jam_selesai,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('mahasiswa.' . $this->bimbingan->mahasiswa_id);
    }

    public function broadcastAs(): string
    {
        return 'perubahan-otomatis';
    }
}
