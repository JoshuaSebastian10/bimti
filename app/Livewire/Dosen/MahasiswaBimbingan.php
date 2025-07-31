<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Mahasiswa;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbingan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = ''; 
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $dosenId = Auth::user()->dosen->id;

       
        $mahasiswas = Mahasiswa::query()->with(['user']) 
        ->where(function ($query) use ($dosenId) {
            $query->where('dosen_pa_id', $dosenId)
                ->orWhere('pembimbing_skripsi_1_id', $dosenId)
                ->orWhere('pembimbing_skripsi_2_id', $dosenId);
        })
        ->when($this->search, function ($query, $search) {
            $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            })->orWhere('nim', 'like', "%{$search}%");
        })
        ->withCount([

        'bimbingan as total_ajuan_count' => function ($query) use ($dosenId) {
        $query->where('dosen_id', $dosenId);
    },
        
            'bimbingan as bimbingan_selesai_count' => function ($query) use ($dosenId) {
        $query->where('status', 'selesai')->where('dosen_id', $dosenId);
    }
        ])

        ->orderBy('status_bimbingan', 'desc')
        ->orderBy('total_ajuan_count', 'desc')
        ->orderBy('bimbingan_selesai_count', 'desc')


        ->paginate(15);
        return view('livewire.dosen.mahasiswa-bimbingan', [
            'mahasiswas' => $mahasiswas,
            'dosenId' => $dosenId
        ]);
    }
    }
