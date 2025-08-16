<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DaftarBimbingan extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public string $search = '';

    protected $queryString = ['search'];

    public string $tab_aktif = 'aktif';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    
    public $bimbinganTerpilih;
    public bool $isDetailModalOpen = false;

    public $status_baru = '';
    public $pesan_penolakan = '';
    public bool $isModalStatusOpen = false;

    public function lihatDetail($bimbinganId)
    {
     
        $bimbingan = Bimbingan::with(['dosen.user', 'mahasiswa.user'])->find($bimbinganId);

        if ($bimbingan && $bimbingan->dosen_id === Auth::user()->dosen->id) {
            $this->bimbinganTerpilih = $bimbingan;
            $this->isDetailModalOpen = true;
        }
    }    

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->bimbinganTerpilih = null;
    }



    public function bukaModalStatus($bimbinganId)
    {
         $this->bimbinganTerpilih = Bimbingan::with('mahasiswa.user')->find($bimbinganId);
         $this->isModalStatusOpen = true;
    }


    public function closeModalStatus()
    {
         $this->isModalStatusOpen = false;
         $this->reset(['bimbinganTerpilih', 'status_baru', 'pesan_penolakan']);
    }
     
    public function simpanPerubahanStatus()
    {
        // 1. Validasi (Sudah Benar)
        $this->validate([
            'status_baru' => 'required|in:disetujui,ditolak,selesai,dibatalkan',
            'pesan_penolakan' => 'required_if:status_baru,ditolak|string|nullable',
        ]);

        // 2. Otorisasi (Sudah Benar)
        if (!$this->bimbinganTerpilih || $this->bimbinganTerpilih->dosen_id !== Auth::user()->dosen->id) {
            abort(403);
        }

        // =======================================================
        //            AWAL DARI PERBAIKAN LOGIKA
        // =======================================================

        // 3. Update Status dan Timestamp
        $bimbingan = $this->bimbinganTerpilih;
        $bimbingan->status = $this->status_baru;

        // Reset semua timestamp status untuk memastikan hanya satu yang terisi
        $bimbingan->tanggal_disetujui = null;
        $bimbingan->tanggal_ditolak = null;
        $bimbingan->tanggal_dibatalkan = null;
        $bimbingan->tanggal_selesai = null;

        // Isi timestamp yang sesuai dengan status baru
        switch ($this->status_baru) {
        case 'disetujui':
            $bimbingan->tanggal_disetujui = now();
            break;
        case 'ditolak':
            $bimbingan->tanggal_ditolak = now();
            $bimbingan->pesan = $this->pesan_penolakan;
            break;
        case 'dibatalkan':
            $bimbingan->tanggal_dibatalkan = now();
            $bimbingan->pesan = 'Dibatalkan oleh dosen.';
            break;
        case 'selesai':
            // JIKA BELUM ADA TANGGAL PERSETUJUAN, ISI SEKARANG JUGA
            if (is_null($bimbingan->tanggal_disetujui)) {
                $bimbingan->tanggal_disetujui = now();
            }
            $bimbingan->tanggal_selesai = now();
            break;
    }

        $bimbingan->save();
        
        // =======================================================
        //             AKHIR DARI PERBAIKAN LOGIKA
        // =======================================================

        // 4. Beri Umpan Balik
        // Logika pesan Anda sudah bagus, ini hanya versi yang sedikit lebih ringkas
        if ($this->status_baru === 'disetujui') {
            session()->flash('success', 'Status bimbingan berhasil disetujui.');
        } else {
            session()->flash('success', 'Status bimbingan berhasil diperbarui dan dipindahkan ke riwayat.');
        }
        
        $this->closeModalStatus();
    }
        

    public function render()
    {
        $dosen = Auth::user()->dosen->id;

        $bimbinganQuery = Bimbingan::where('dosen_id', $dosen)
        ->with('mahasiswa.user');

        if ($this->tab_aktif === 'aktif') {
            $bimbinganQuery->whereIn('status', ['menunggu', 'disetujui']);
        } else { 
            $bimbinganQuery->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);
        }

        if (!empty($this->search)) {
            $bimbinganQuery->whereHas('mahasiswa.user', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $bimbingans = $bimbinganQuery->latest('tanggal_bimbingan')->paginate(10);


        return view('livewire.dosen.daftar-bimbingan', [
            'bimbingans' => $bimbingans,
        ]);
    }


}
