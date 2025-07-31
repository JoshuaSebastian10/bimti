<?php

namespace App\Livewire\Admin;

use App\Models\Dosen;
use App\Models\Jadwal_bimbingan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DataDosen extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public string $search = '';
    public $status_akun = '';
    public bool $isModalStatusOpen = false;
    public $dosenTerpilih;
    public $namaDosenTerpilih;

    protected $queryString = ['search'];

    public function bukaModalStatus($dosenId)
    {
        $dosen = Dosen::with('user')->findOrFail($dosenId);
        $this->dosenTerpilih = $dosen;
        $this->namaDosenTerpilih = $dosen->user->name;
        


        $this->status_akun = $dosen->status_akun;
        $this->isModalStatusOpen = true;
    }

    public function closeModalStatus()
    {
        $this->isModalStatusOpen = false;
        $this->reset(['dosenTerpilih', 'namaDosenTerpilih', 'status_akun']);
    }

    public function simpanStatus()
    {
        $this->validate([
            'status_akun' => 'required|in:aktif,nonAktif',
        ]);

        if ($this->dosenTerpilih) {
            $this->dosenTerpilih->update([
                'status_akun' => $this->status_akun,
            ]);

            if ($this->status_akun === 'nonAktif') {
              Jadwal_bimbingan::where('jadwal_dosen_id', $this->dosenTerpilih->id)
            ->update(['is_active' => 0]);
            } else {
                Jadwal_bimbingan::where('jadwal_dosen_id', $this->dosenTerpilih->id)
                    ->update(['is_active' => 1]);
            }


            $this->closeModalStatus();
            session()->flash('success', 'Status akun Dosen berhasil diperbarui.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $dosenQuery = Dosen::query()->with('user');

        if ($this->search) {
            $dosenQuery->where(function ($query) {
                $query->where('nip', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $dosen = $dosenQuery->latest()->paginate(10);

        return view('livewire.admin.data-dosen', [
            'dosen' => $dosen,
        ]);
    }
    
    public function deleteDosen($id)
    {
        try {

            $dosen = Dosen::findOrFail($id);
            $dosen->delete();

            
            session()->flash('successdelete', 'Data mahasiswa berhasil dihapus.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data.');
        }
    }
}
