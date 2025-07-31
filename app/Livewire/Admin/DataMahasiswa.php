<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\Mahasiswa;
use Livewire\WithPagination;

class DataMahasiswa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public string $search = '';

    protected $queryString = ['search'];

    public $mahasiswaTerpilih;
    public $namaMahasiswaTerpilih;
    public $namaPembimbingAkademik;
    public $pembimbing1_id = '';
    public $pembimbing2_id = '';
    public $semuaDosen = [];
    public bool $isModalPembimbing1Open = false;
    public bool $isModalPembimbing2Open = false;
    public $status_akun = '';
    public $status_bimbingan = '';
    public bool $isModalStatusOpen = false;

    public function mount()
    {
        $this->semuaDosen = User::role('dosen')->with('dosen')->orderBy('name')->get();
    }
    


    public function bukaModalPembimbing1($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswaId);
        $this->mahasiswaTerpilih = $mahasiswa;
        $this->namaMahasiswaTerpilih = $mahasiswa->user->name;
        $this->namaPembimbingAkademik = $mahasiswa->dosenPa->user->name;
        
        $this->pembimbing1_id = $mahasiswa->pembimbing_skripsi_1_id;

        $this->isModalPembimbing1Open = true;
    }

        public function bukaModalPembimbing2($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswaId);
        $this->mahasiswaTerpilih = $mahasiswa;
        $this->namaMahasiswaTerpilih = $mahasiswa->user->name;
        $this->namaPembimbingAkademik = $mahasiswa->dosenPa->user->name;
        
        $this->pembimbing1_id = $mahasiswa->pembimbing_skripsi_1_id;
        $this->pembimbing2_id = $mahasiswa->pembimbing_skripsi_2_id;

        $this->isModalPembimbing2Open = true;
    }



    public function closeModalPembimbing1()
    {
        $this->isModalPembimbing1Open = false;
        $this->reset(['mahasiswaTerpilih', 'namaMahasiswaTerpilih', 'pembimbing1_id']);
    }

    
    public function closeModalPembimbing2()
    {
        $this->isModalPembimbing2Open = false;
        $this->reset(['mahasiswaTerpilih', 'namaMahasiswaTerpilih', 'pembimbing1_id', 'pembimbing2_id']);
    }

    public function simpanPembimbing1()
    {
        $this->validate([
            'pembimbing1_id' => 'nullable|exists:dosens,id',
        ]);

        if ($this->mahasiswaTerpilih) {
            $p1_id = !empty($this->pembimbing1_id) ? $this->pembimbing1_id : null;
            $this->mahasiswaTerpilih->update([
                'pembimbing_skripsi_1_id' => $p1_id
            ]);

            $this->closeModalPembimbing1();
            session()->flash('success', 'Data pembimbing skripsi 1 berhasil diperbarui.');
        }
    }

     public function simpanPembimbing2()
    {
        $this->validate([
            'pembimbing1_id' => 'nullable|exists:dosens,id',
            'pembimbing2_id' => 'nullable|exists:dosens,id',
        ]);

        if ($this->mahasiswaTerpilih) {
            $p1_id = !empty($this->pembimbing1_id) ? $this->pembimbing1_id : null;
            $p2_id = !empty($this->pembimbing2_id) ? $this->pembimbing2_id : null;
            if($p1_id == $p2_id){
                $this->closeModalPembimbing2();
                session()->flash('error', 'pembimbing skripsi 1 dan pembimbing skripsi 2 tidak boleh sama!.');
                return;
            }
            $this->mahasiswaTerpilih->update([
                'pembimbing_skripsi_1_id' => $p1_id,
                'pembimbing_skripsi_2_id' => $p2_id
            ]);

            $this->closeModalPembimbing2();
            session()->flash('success', 'Data pembimbing skripsi 2 berhasil diperbarui.');
        }
    }

    public function bukaModalStatus($mahasiswaId)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($mahasiswaId);
        $this->mahasiswaTerpilih = $mahasiswa;
        $this->namaMahasiswaTerpilih = $mahasiswa->user->name;

        $this->status_akun = $mahasiswa->status_akun;
        $this->status_bimbingan = $mahasiswa->status_bimbingan;

        $this->isModalStatusOpen = true;
    }

    public function closeModalStatus()
    {
        $this->isModalStatusOpen = false;
        $this->reset(['mahasiswaTerpilih', 'namaMahasiswaTerpilih', 'status_akun', 'status_bimbingan']);
    }

    public function simpanStatus()
    {
        $this->validate([
            'status_akun' => 'required|in:aktif,nonAktif',
            'status_bimbingan' => 'required|in:akademik,proposal,skripsi',
        ]);

        if ($this->mahasiswaTerpilih) {
            $this->mahasiswaTerpilih->update([
                'status_akun' => $this->status_akun,
                'status_bimbingan' => $this->status_bimbingan,
            ]);

            $this->closeModalStatus();
            session()->flash('success', 'Status mahasiswa berhasil diperbarui.');
        }
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $mahasiswaQuery = Mahasiswa::query()->with('user', 'dosenPa');

        if ($this->search) {
            $mahasiswaQuery->where(function ($query) {
                $query->where('nim', 'like', '%' . $this->search . '%')
                     ->orWhereHas('user', function ($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%');
            });
            });
        }

        $mahasiswa = $mahasiswaQuery->latest()->paginate(10);

        return view('livewire.admin.data-mahasiswa', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function deleteMahasiswa($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
            $mahasiswa->delete();
            session()->flash('successdelete', 'Data mahasiswa berhasil dihapus.');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus data.');
        }
    }
}
