<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ListBimbinganSearch extends Component
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
     public $status_baru = '';
     public $pesan_penolakan = '';
     public bool $isModalStatusOpen = false;
 

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
         $this->validate([
             'status_baru' => 'required|in:disetujui,ditolak,selesai,dibatalkan',
             'pesan_penolakan' => 'required_if:status_baru,ditolak|string|nullable',
         ]);
         

         if (!$this->bimbinganTerpilih || $this->bimbinganTerpilih->dosen_id !== Auth::user()->dosen->id) {
             abort(403);
         }
 

         $this->bimbinganTerpilih->status = $this->status_baru;
         if ($this->status_baru === 'ditolak') {
             $this->bimbinganTerpilih->pesan = $this->pesan_penolakan;
         } else {
             $this->bimbinganTerpilih->pesan = null; 
         }
         
         $this->bimbinganTerpilih->save();
 
         $this->closeModalStatus();
         session()->flash('success', 'Status bimbingan berhasil diperbarui.');
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


        return view('livewire.Dosen.list-bimbingan-search', [
            'bimbingans' => $bimbingans,
        ]);
    }


}
