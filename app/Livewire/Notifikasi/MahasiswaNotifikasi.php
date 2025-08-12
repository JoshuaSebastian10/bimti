<?php

namespace App\Livewire\Notifikasi;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MahasiswaNotifikasi extends Component
{
    public string $tab = 'semua';      // 'semua' | 'belum'
    public string $pencarian = '';     // cari judul/pesan
    public int $jumlahBelumDibaca = 0; // badge
    public int $batas = 8;             // jumlah item di dropdown


    // app/Livewire/Notifikasi/NotifikasiMahasiswa.php
protected $listeners = ['notifMasuk' => 'refreshData'];

public function refreshData()
{
    $this->resetPage(); // atau langsung muat ulang query
}


    public function mount()
    {
        $this->jumlahBelumDibaca = Auth::user()->unreadNotifications()->count();
    }

    public function updatedTab()       { $this->render(); }
    public function updatedPencarian() { $this->render(); }

    public function render()
    {
        $q = Auth::user()->notifications()->latest();

        if ($this->tab === 'belum') {
            $q->whereNull('read_at');
        }
        if (trim($this->pencarian) !== '') {
            $term = '%'.trim($this->pencarian).'%';
            $q->where(function($qq) use ($term) {
                $qq->where('data->judul', 'like', $term)
                   ->orWhere('data->pesan', 'like', $term);
            });
        }

        $items = $q->limit($this->batas)->get();
        // refresh badge
        $this->jumlahBelumDibaca = Auth::user()->unreadNotifications()->count();

        return view('livewire.notifikasi.mahasiswa-notifikasi', [
            'items' => $items,
        ]);
    }

    public function tandaiDibaca(string $id)
    {
        $n = Auth::user()->notifications()->find($id);
        if ($n && is_null($n->read_at)) {
            $n->markAsRead();
            $this->jumlahBelumDibaca = max(0, $this->jumlahBelumDibaca - 1);
        }
        $this->dispatch('refreshNotifikasi');
    }

    public function hapus(string $id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        $this->dispatch('refreshNotifikasi');
    }
}
