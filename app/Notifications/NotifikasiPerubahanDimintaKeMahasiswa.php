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

class NotifikasiPerubahanDimintaKeMahasiswa extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable, SerializesModels;

    public function __construct(public Bimbingan $bimbingan)
    {
        // gunakan properti dari trait Queueable
        $this->afterCommit = true;
    }

    public function via($notifiable): array
    {
        return ['database','broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'jenis'            => 'perubahan_diminta',
            'judul'            => 'Konfirmasi Perubahan Jadwal',
            'pesan'            => 'Dosen mengusulkan perubahan jadwal bimbingan. Mohon konfirmasi.',
            'bimbingan_id'     => $this->bimbingan->id,
            'tanggal_baru'     => $this->bimbingan->usulan_tanggal_bimbingan,
            'jam_mulai_baru'   => $this->bimbingan->usulan_jam_mulai,
            'jam_selesai_baru' => $this->bimbingan->usulan_jam_selesai,
        ];
    }

    public function toBroadcast($notifiable): \Illuminate\Notifications\Messages\BroadcastMessage
    {
        return new \Illuminate\Notifications\Messages\BroadcastMessage($this->toArray($notifiable));
    }

    public function broadcastOn(): \Illuminate\Broadcasting\PrivateChannel
    {
        return new \Illuminate\Broadcasting\PrivateChannel('mahasiswa.' . $this->bimbingan->mahasiswa_id);
    }

    public function broadcastAs(): string
    {
        return 'perubahan-diminta';
    }
}