<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Bimbingan;
use App\Models\Dosen;
use App\Models\Jadwal_bimbingan;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AjukanBimbinganAkademik extends Component
{
    use WithFileUploads;

    public Dosen $dosenPa;
    public $opsiDropdown = [];

  
    public $topik = '';
    public $jadwal = '';
    public $lampiran;

        protected function rules()
    {
        return [
        'topik' => 'required|string|max:500',
        'jadwal' => 'required|string',
        'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
        ];
    }
    public function mount()
    {

        $this->dosenPa = Auth::user()->mahasiswa->dosenPa;

        $this->opsiDropdown = $this->generateJadwalOptions($this->dosenPa->id);
    }

    public function store()
    {
    $validatedData = $this->validate();

    $jadwalParts = explode('|', $validatedData['jadwal']);
    $tanggalBimbingan = $jadwalParts[0];
    $jamMulai = $jadwalParts[1];

    $mahasiswa = Auth::user()->mahasiswa;
    $dosenId = $this->dosenPa->id;


 
    $jadwalRutin = Jadwal_bimbingan::where('jadwal_dosen_id', $dosenId)
        ->where('hari', Carbon::parse($tanggalBimbingan)->locale('id')->translatedFormat('l'))
        ->where('jam_mulai', $jamMulai)
        ->firstOrFail();

        if (!$jadwalRutin || !$jadwalRutin->is_active ) {
            session()->flash('error', 'Maaf, jadwal yang Anda pilih tidak lagi tersedia.');
            return;
        }
           
    $terdaftar = Bimbingan::where('dosen_id', $dosenId)
        ->where('tanggal_bimbingan', $tanggalBimbingan)
        ->where('jam_mulai', $jamMulai)
        ->whereIn('status', ['menunggu', 'disetujui'])
        ->count();

    if ($terdaftar >= $jadwalRutin->kuota) {
            session()->flash('error', 'Maaf, slot bimbingan yang Anda pilih terisi penuh.');
            $this->opsiDropdown = $this->generateJadwalOptions($dosenId);
            return;
    }


    $lampiranPath = null;
    DB::beginTransaction();

    try {
         if ($this->lampiran) {
                $lampiranPath = $this->lampiran->store('lampiran-bimbingan', 'local');
        }

        Bimbingan::create([
            'mahasiswa_id'      => $mahasiswa->id,
            'dosen_id'          => $dosenId,
            'topik'             => $validatedData['topik'],
            'status'            => 'menunggu',
            'jenis_bimbingan'   => 'akademik',
            'pesan'             => null,
           'lampiran_path'     => $lampiranPath, 
            'tanggal_pengajuan' => now(),
            'tanggal_bimbingan' => $tanggalBimbingan,
            'jam_mulai'         => $jamMulai,
            'jam_selesai'       => $jadwalRutin->jam_selesai,
        ]);

        DB::commit();

    } catch (\Exception $e) {
         DB::rollBack();
        
        if ($lampiranPath) {
            Storage::disk('local')->delete($lampiranPath);
            }

        Log::error('Gagal membuat ajuan bimbingan: ' . $e->getMessage());
         session()->flash('error', 'Terjadi kesalahan teknis saat mengajukan bimbingan.');
         return;
    }
       redirect()->route('mahasiswa.bimbinganSaya')->with('success', 'Jadwal bimbingan berhasil diajukan. Mohon tunggu konfirmasi dari dosen.');
    }

    private function generateJadwalOptions($profilDosenId)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        $jadwalRutin = Jadwal_bimbingan::where('jadwal_dosen_id', $profilDosenId)
            ->where('is_active', true)
            ->get();
        
        $ajuanMahasiswa = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('dosen_id', $profilDosenId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->get()
            ->map(fn($b) => Carbon::parse($b->tanggal_bimbingan)->format('Y-m-d') . '_' . Carbon::parse($b->jam_mulai)->format('H:i:s'));
        
        $startDate = Carbon::tomorrow();

        $endDate = Carbon::now()->addWeeks(3);

        $jumlahTerdaftar = Bimbingan::where('dosen_id', $profilDosenId)
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
            $jadwalUntukHariIni = $jadwalRutin->where('hari', $namaHari);

            if ($jadwalUntukHariIni->isNotEmpty()) {
                foreach ($jadwalUntukHariIni as $jadwal) {
                    $jamMulaiFormatted = Carbon::parse($jadwal->jam_mulai)->format('H:i:s');
                    $lookupKey = $carbonDate->format('Y-m-d') . '_' . $jamMulaiFormatted;
                    
                    $terdaftar = $jumlahTerdaftar->get($lookupKey)?->total ?? 0;
                    $isFull = ($terdaftar >= $jadwal->kuota);
                    $sudahDipesanOlehUserIni = $ajuanMahasiswa->contains($lookupKey);
                    
                    $opsiTeks = sprintf(
                        '%s, %s | %s - %s (%d/%d terisi)',
                        $namaHari,
                        $carbonDate->format('d M Y'),
                        Carbon::parse($jadwal->jam_mulai)->format('H:i'),
                        Carbon::parse($jadwal->jam_selesai)->format('H:i'),
                        $terdaftar,
                        $jadwal->kuota
                    );

                    $opsiDropdown[] = [
                        'value' => $carbonDate->format('Y-m-d') . '|' . $jamMulaiFormatted,
                        'teks' => $opsiTeks,
                        'sudah_dipesan' => $sudahDipesanOlehUserIni,
                        'is_full' => $isFull,
                    ];
                }
            }
        }

        return $opsiDropdown;
    }

    public function render()
    {
        return view('livewire.mahasiswa.ajukan-bimbingan-akademik');
    }
}