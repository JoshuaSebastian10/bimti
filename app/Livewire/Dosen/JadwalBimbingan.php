<?php

namespace App\Livewire\Dosen;

use App\Models\Jadwal_bimbingan; 
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class JadwalBimbingan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public bool $isCreateModalOpen = false;
    public $hari, $jam_mulai, $jam_selesai, $kuota;
    public bool $isEditModalOpen = false;
    public $jadwal_id;

       public function create()
    {
        $this->reset(['hari', 'jam_mulai', 'jam_selesai', 'kuota']);
        $this->isCreateModalOpen = true;
    }

    public function closeModal()
    {
        $this->isCreateModalOpen = false;
    }

     public function store()
    {
        $validatedData = $this->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota' => 'required|integer|min:1|max:100',
        ]);

        $dosenId = Auth::user()->dosen->id;

       
        $existing = Jadwal_bimbingan::where('jadwal_dosen_id', $dosenId)
            ->where('hari', $this->hari)
            ->exists();

        if ($existing) {
            $this->addError('hari', 'Anda sudah memiliki jadwal bimbingan di hari ' . $this->hari);
            return;
        }

        Jadwal_bimbingan::create([
            'jadwal_dosen_id' => $dosenId,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kuota' => $this->kuota,
            'is_active' => true
        ]);

        session()->flash('success', 'Jadwal bimbingan baru berhasil ditambahkan.');
        $this->closeModal();
    }

     public function edit($jadwalId)
    {
        $jadwal = Jadwal_bimbingan::findOrFail($jadwalId);

        if ($jadwal->jadwal_dosen_id !== Auth::user()->dosen->id) {
            session()->flash('error', 'Aksi tidak diizinkan.');
            return;
        }

        $this->jadwal_id = $jadwal->id;
        $this->hari = $jadwal->hari;
        $this->jam_mulai = $jadwal->jamMulaiFormat;
        $this->jam_selesai = $jadwal->jamSelesaiFormat;
        $this->kuota = $jadwal->kuota;
        
        $this->resetErrorBag();
        $this->isEditModalOpen = true;
    }

     public function closeEditModal()
    {
        $this->isEditModalOpen = false;
    }

    public function update()
    {
        $dosenId = Auth::user()->dosen->id;

        $validatedData = $this->validate([
          
            'hari' => [
                'required',
                'in:Senin,Selasa,Rabu,Kamis,Jumat',
                Rule::unique('jadwal_bimbingans')->where(function ($query) use ($dosenId) {
                    return $query->where('jadwal_dosen_id', $dosenId);
                })->ignore($this->jadwal_id),
            ],
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kuota' => 'required|integer|min:1|max:100',
        ]);

        $jadwal = Jadwal_bimbingan::find($this->jadwal_id);
        
        if ($jadwal && $jadwal->jadwal_dosen_id === $dosenId) {
            $jadwal->update($validatedData);
            session()->flash('success', 'Jadwal bimbingan berhasil diperbarui.');
            $this->closeEditModal();
        }
    }



    
    public function toggleStatus($jadwalId)
    {
        $jadwal = Jadwal_bimbingan::find($jadwalId);

        if ($jadwal && $jadwal->jadwal_dosen_id === Auth::user()->dosen->id) {
            $jadwal->is_active = !$jadwal->is_active;
            $jadwal->save();
        }
    }

    public function deleteJadwal($jadwalId)
    {
        try {
            $jadwal = Jadwal_bimbingan::findOrFail($jadwalId);

            if ($jadwal->jadwal_dosen_id !== Auth::user()->dosen->id) {
                session()->flash('error', 'Aksi tidak diizinkan.');
                return;
            }

            $jadwal->delete();
            session()->flash('success', 'Jadwal bimbingan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus jadwal: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus data.');
        }
    }

    public function render()
    {
        $dosenId = Auth::user()->dosen->id;

        $jadwalBimbingan = Jadwal_bimbingan::where('jadwal_dosen_id', $dosenId)
            ->orderBy('hari') // Urutkan berdasarkan hari
            ->paginate(10);

        return view('livewire.dosen.jadwal-bimbingan', [
            'jadwalBimbingan' => $jadwalBimbingan
        ]);
    }
}