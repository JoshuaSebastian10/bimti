<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePage extends Component
{
    use WithFileUploads;

    public User $user;
    public $mahasiswa;
    public $dosen;
    public bool $isPhotoModalOpen = false;
    public $photo;
    public bool $isEditModalOpen = false;
    public $name, $email, $nim, $nip; 

    public function mount()
    {
        // Muat data user dan relasinya sekali saja
        $this->user = Auth::user()->load(['mahasiswa.pembimbingSkripsi1.user', 'mahasiswa.pembimbingSkripsi2.user', 'dosen']);
        $this->mahasiswa = $this->user->mahasiswa;
        $this->dosen = $this->user->dosen;
    }

        public function openPhotoModal()
    {
        $this->reset(['photo']);
        $this->resetErrorBag();
        $this->isPhotoModalOpen = true;
    }

      public function closePhotoModal()
    {
        $this->isPhotoModalOpen = false;
    }

        public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:2048', // maks 2MB
        ]);
    }

     public function updatePhoto()
    {
        $this->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($this->user->profil_path) {
            Storage::disk('public')->delete($this->user->profil_path);
        }

        // Simpan foto baru dan update path di database
        $path = $this->photo->store('profile-photos', 'public');
        $this->user->update(['profil_path' => $path]);

        $this->closePhotoModal();
        session()->flash('success', 'Foto profil berhasil diperbarui.');
    }

    public function edit()
    {
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        if ($this->mahasiswa) {
            $this->nim = $this->mahasiswa->nim;
        }
        if ($this->dosen) {
            $this->nip = $this->dosen->nip; // Ganti nip jika nama kolom berbeda
        }
        $this->isEditModalOpen = true;
    }
    
    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // if ($this->mahasiswa) {
        //     $this->mahasiswa->update(['no_hp' => $this->no_hp]);
        // }
        // Tambahkan logika update untuk dosen jika ada field yang bisa diubah

        session()->flash('success', 'Profil berhasil diperbarui.');
        $this->isEditModalOpen = false;
    }
    
    public function closeModal()
    {
        $this->isEditModalOpen = false;
    }

    public function render()
    {
        return view('livewire.user.profile-page');
    }
}