<?php

namespace App\Livewire\Admin;

use App\Models\Bimbingan;
use App\Models\Dosen;
use Livewire\Component;
use Livewire\WithPagination;

class ManajemenBimbingan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Properti untuk filter
    public string $search = '';
    public string $filterStatus = '';
    public string $filterJenis = '';
    public string $filterDosen = '';
    
    public $dosens = []; 


    public $bimbinganTerpilih;
    public bool $isDetailModalOpen = false;

    public function mount()
    {

        $this->dosens = Dosen::with('user')->get();
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterJenis() { $this->resetPage(); }
    public function updatingFilterDosen() { $this->resetPage(); }

    public function lihatDetail($bimbinganId)
    {
        $this->bimbinganTerpilih = Bimbingan::with(['dosen.user', 'mahasiswa.user'])->find($bimbinganId);
        $this->isDetailModalOpen = true;
    }

    public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
    }

    public function render()
    {
        $bimbinganQuery = Bimbingan::query()
            ->with(['mahasiswa.user', 'dosen.user'])
            ->when($this->search, function($query) {
                $query->Where('topik', 'like', '%'.$this->search.'%')
                      ->orWhereHas('mahasiswa.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
            })
            ->when($this->filterStatus, fn($query, $status) => $query->where('status', $status))
            ->when($this->filterJenis, fn($query, $jenis) => $query->where('jenis_bimbingan', $jenis))
            ->when($this->filterDosen, fn($query, $dosenId) => $query->where('dosen_id', $dosenId));
            
        $bimbingans = $bimbinganQuery->latest('tanggal_pengajuan')->paginate(15);

        return view('livewire.admin.manajemen-bimbingan', [
            'bimbingans' => $bimbingans,
        ]);
    }
}