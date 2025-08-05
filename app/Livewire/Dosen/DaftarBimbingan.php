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
     
        if($this->bimbinganTerpilih->status == 'disetujui'){
             session()->flash('success', 'Status bimbingan berhasil diperbarui.');
        }else{
 session()->flash('success', 'Status bimbingan berhasil diperbarui dan dipindahkan ke halaman riwayat bimbingan .');
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


        return view('livewire.Dosen.daftar-bimbingan', [
            'bimbingans' => $bimbingans,
        ]);
    }


}
