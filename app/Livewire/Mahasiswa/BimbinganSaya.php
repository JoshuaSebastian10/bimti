<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads; 

class BimbinganSaya extends Component
{
    use WithPagination;
    use WithFileUploads;
    
    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $tab_aktif = 'aktif';

    public $bimbinganTerpilih;
    public bool $isDetailModalOpen = false;

    public bool $isModalStatusOpen = false;

    public $bimbinganUntukDiedit;
    public bool $isEditModalOpen = false;
    public $topik;
    public $lampiranBaru; 

    
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
     public function editBimbingan($bimbinganId)
    {
        $bimbingan = Bimbingan::findOrFail($bimbinganId);

        if ($bimbingan->mahasiswa_id !== Auth::user()->mahasiswa->id || $bimbingan->status !== 'menunggu') {
            session()->flash('error', 'Anda tidak dapat mengedit ajuan bimbingan ini.');
            return;
        }

        $this->bimbinganUntukDiedit = $bimbingan;
        $this->topik = $bimbingan->topik;
        $this->isEditModalOpen = true;
    } 

     public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->reset(['bimbinganUntukDiedit', 'topik', 'lampiranBaru']);
    }

    public function updateBimbingan()
    {

        $this->validate([
            'topik' => 'required|string|max:500',
            'lampiranBaru' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120'
        ]);
        if (!$this->bimbinganUntukDiedit || $this->bimbinganUntukDiedit->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403);
        }

        try {
        
            $this->bimbinganUntukDiedit->topik = $this->topik;

            if ($this->lampiranBaru) {
                
                if ($this->bimbinganUntukDiedit->lampiran_path) {
                    Storage::disk('local')->delete($this->bimbinganUntukDiedit->lampiran_path);
                }
                
                $path = $this->lampiranBaru->store('lampiran-bimbingan', 'local');
                $this->bimbinganUntukDiedit->lampiran_path = $path;
            }

            $this->bimbinganUntukDiedit->save();

            $this->closeEditModal();
            session()->flash('success', 'Ajuan bimbingan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal update bimbingan: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

       public function lihatDetail($bimbinganId)
    {
     
        $bimbingan = Bimbingan::with(['dosen.user', 'mahasiswa.user'])->find($bimbinganId);
        

        if ($bimbingan && $bimbingan->mahasiswa_id === Auth::user()->mahasiswa->id) {
            $this->bimbinganTerpilih = $bimbingan;
            $this->isDetailModalOpen = true;
        }
    }

     public function closeDetailModal()
    {
        $this->isDetailModalOpen = false;
        $this->bimbinganTerpilih = null;
    }

    public function deleteBimbingan($bimbinganId)
    {
        try {

            $bimbingan = Bimbingan::findOrFail($bimbinganId);

            if ($bimbingan->mahasiswa_id !== Auth::user()->mahasiswa->id || $bimbingan->status !== 'menunggu') {
                 session()->flash('error', 'Anda tidak dapat menghapus ajuan bimbingan ini.');
                 return;
            }
 
            if ($bimbingan->lampiran_path) {
                Storage::disk('local')->delete($bimbingan->lampiran_path);
            }

            $bimbingan->delete();
            session()->flash('success', 'Ajuan bimbingan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus bimbingan: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus data.');
        }
    }

        public function render()
    {
        $mahasiswaId = Auth::user()->mahasiswa->id;

        $bimbinganQuery = Bimbingan::where('mahasiswa_id', $mahasiswaId)
        ->with('dosen.user');

        if ($this->tab_aktif === 'aktif') {
            $bimbinganQuery->whereIn('status', ['menunggu', 'disetujui']);
        } else { 
            $bimbinganQuery->whereIn('status', ['selesai', 'ditolak', 'dibatalkan']);
        }

        if (!empty($this->search)) {
            $bimbinganQuery->where(function($query) {
                $query->Where('topik', 'like', '%' . $this->search . '%')
                        ->orWhereHas('dosen.user', function($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%');
                      });
            });
        }

        $bimbingans = $bimbinganQuery->latest('created_at')->paginate(10);


        return view('livewire.Mahasiswa.bimbingan-saya', [
            'bimbingans' => $bimbingans,
        ]);
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
 
         $this->closeModalStatus();
         session()->flash('success', 'Status bimbingan berhasil diperbarui.');
     }


  
}
