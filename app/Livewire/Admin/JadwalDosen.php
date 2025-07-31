<?php

namespace App\Livewire\Admin;

use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Dosen;
use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use App\Models\Jadwal_bimbingan;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JadwalDosen extends Component
    {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public bool $isPreviewModalOpen = false;
    public string $dosenNamaPratinjau = '';
    public array $jadwalUntukPratinjau = [];

    public $hari, $jam_mulai, $jam_selesai, $kuota;
    public bool $isEditModalOpen = false;
    public $jadwal_id;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

        public function toggleStatus($jadwalId)
    {
        $jadwal = Jadwal_bimbingan::find($jadwalId);
       
            $jadwal->is_active = !$jadwal->is_active;
            $jadwal->save();
        
    }


    public function edit($jadwalId)
    {
        $jadwal = Jadwal_bimbingan::findOrFail($jadwalId);

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
        $jadwal = Jadwal_bimbingan::findOrFail($this->jadwal_id);
        $dosenId = $jadwal->jadwal_dosen_id;

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

        $jadwal->update($validatedData);
        session()->flash('success', 'Jadwal bimbingan berhasil diperbarui.');
        $this->closeEditModal();
    }

        public function deleteJadwal($jadwal_id)
    {
        try {
            $jadwal = Jadwal_bimbingan::findOrFail($jadwal_id);


            $jadwal->delete();
            session()->flash('success', 'Jadwal bimbingan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Gagal hapus jadwal: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus data.');
        }
    }



       public function bukaModalPratinjau($jadwalId)
    {
   
        $jadwalSpesifik = Jadwal_bimbingan::with('dosen.user')->find($jadwalId);
        if (!$jadwalSpesifik) return;

        $this->dosenNamaPratinjau = $jadwalSpesifik->dosen->user->name;
        $dosenId = $jadwalSpesifik->jadwal_dosen_id;

      
        $startDate = Carbon::tomorrow();
        $endDate = Carbon::now()->addWeeks(3);
        $jumlahTerdaftar = Bimbingan::where('dosen_id', $dosenId)
            ->whereBetween('tanggal_bimbingan', [$startDate, $endDate])
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->select('tanggal_bimbingan', 'jam_mulai', DB::raw('count(*) as total'))
            ->groupBy('tanggal_bimbingan', 'jam_mulai')
            ->get()
            ->keyBy(fn($item) => Carbon::parse($item->tanggal_bimbingan)->format('Y-m-d') . '_' . Carbon::parse($item->jam_mulai)->format('H:i:s'));
     
        $opsiDropdown = [];
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate->addDay());


        foreach ($period as $tanggal) {
            $carbonDate = Carbon::instance($tanggal);
            $namaHari = $carbonDate->locale('id')->translatedFormat('l');
            
            if ($namaHari === $jadwalSpesifik->hari) {
                

                $jamMulaiFormatted = Carbon::parse($jadwalSpesifik->jam_mulai)->format('H:i:s');
                $lookupKey = $carbonDate->format('Y-m-d') . '_' . $jamMulaiFormatted;
                $terdaftar = $jumlahTerdaftar->get($lookupKey)?->total ?? 0;
                
                $opsiTeks = sprintf(
                    '%s, %s Jam %s - %s (%d/%d Mahasiswa)',
                    $namaHari,
                    $carbonDate->format('d M Y'),
                    Carbon::parse($jadwalSpesifik->jam_mulai)->format('H:i'),
                    Carbon::parse($jadwalSpesifik->jam_selesai)->format('H:i'),
                    $terdaftar,
                    $jadwalSpesifik->kuota
                );

                $opsiDropdown[] = [
                    'teks' => $opsiTeks,
                    'is_full' => ($terdaftar >= $jadwalSpesifik->kuota),
                ];
            }
        }
        
        $this->jadwalUntukPratinjau = $opsiDropdown;
        $this->isPreviewModalOpen = true;
    }

    public function closeModalPratinjau()
    {
        $this->isPreviewModalOpen = false;
        $this->reset(['dosenNamaPratinjau', 'jadwalUntukPratinjau']);
    }

    public function render()
    {
        $jadwalQuery = Jadwal_bimbingan::query()
          
            ->with(['Dosen.user'])

            ->when($this->search, function ($query, $search) {
               
                $query->whereHas('Dosen.user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('hari', 'like', "%{$search}%");
            })

            ->join('dosens', 'jadwal_bimbingans.jadwal_dosen_id', '=', 'dosens.id')
            ->join('users', 'dosens.user_id', '=', 'users.id')
            ->select('jadwal_bimbingans.*')
            ->orderBy('users.name', 'asc')
            ->orderBy('jadwal_bimbingans.hari', 'asc');

        $semuaJadwal = $jadwalQuery->paginate(15);
        return view('livewire.admin.jadwal-dosen', [
            'semuaJadwal' => $semuaJadwal
        ]);
    }




    }
