<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DaftarBimbingan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // === SEARCH & TAB ===
    public string $search = '';
    protected $queryString = ['search'];
    public string $tab_aktif = 'aktif';
    public string $filterStatus = '';
    public string $filterJenis = '';
    public string $filterDosen = '';
     /** @var array<int|string> */
    public array $selected = []; // id bimbingan terpilih (disimpan sebagai string agar stabil)

    public bool $bulkMode = false;
    
    public function updatingSearch()
    {
        $this->resetPage();
        $this->clearSelection(); // bersihkan pilihannya biar konsisten
    }

    public function updatedTabAktif()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    // === DETAIL & STATUS (existing) ===
    public $bimbinganTerpilih;
    public bool $isDetailModalOpen = false;

    public $status_baru = '';
    public $pesan_penolakan = '';
    public bool $isModalStatusOpen = false;

    
    // ==== MODAL UBAH JADWAL (BULK) ====
    public bool $isModalJadwalOpen = false;
    public ?string $tanggal_baru = null;      // Y-m-d
    public ?string $jam_mulai_baru = null;     // H:i
    public ?string $jam_selesai_baru = null;   // H:i
    public ?string $keterangan_baru = null;


public function bukaModalUbahJadwal(): void
{
    if (count($this->selected) === 0) {
        session()->flash('error', 'Pilih minimal satu bimbingan dulu.');
        return;
    }

    // Bersihkan error/validasi (tanpa argumen)
    $this->resetErrorBag();
    $this->resetValidation();

    // Kosongkan nilai form
    $this->reset(['tanggal_baru','jam_mulai_baru','jam_selesai_baru','keterangan_baru']);

    $this->isModalJadwalOpen = true;
}


public function tutupModalUbahJadwal(): void
{
    $this->isModalJadwalOpen = false;
    $this->reset(['tanggal_baru','jam_mulai_baru','jam_selesai_baru','keterangan_baru']);
}

// Cek bentrok jadwal dosen (exclude item yang lagi diubah)
private function findConflicts(string $tanggal, string $mulai, string $selesai)
{
    $dosenId = Auth::user()->dosen->id;

    return Bimbingan::where('dosen_id', $dosenId)
        ->where('tanggal_bimbingan', $tanggal)
        ->whereNotIn('id', array_map('intval', $this->selected))
        // hanya yang aktif (menunggu/disetujui)
        ->whereIn('status', ['menunggu','disetujui'])
        // overlap rule: startA < endB && endA > startB
        ->where(function ($q) use ($mulai, $selesai) {
            $q->where('jam_mulai', '<', $selesai)
              ->where('jam_selesai', '>', $mulai);
        })
        ->get(['id','tanggal_bimbingan','jam_mulai','jam_selesai']);
}

public function simpanUbahJadwal(): void
{
    $this->validate([
        'tanggal_baru'     => ['required','date'],
        'jam_mulai_baru'   => ['required','date_format:H:i'],
        'jam_selesai_baru' => ['required','date_format:H:i','after:jam_mulai_baru'],
        'keterangan_baru'  => ['nullable','string','max:500'],
    ],[
        'jam_selesai_baru.after' => 'Jam selesai harus setelah jam mulai.',
    ]);

    // cek bentrok dosen
    $conflicts = $this->findConflicts($this->tanggal_baru, $this->jam_mulai_baru, $this->jam_selesai_baru);
    if ($conflicts->count() > 0) {
        $this->addError('jam_mulai_baru', 'Jadwal bentrok dengan '.$conflicts->count().' bimbingan lain di tanggal ini.');
        return;
    }

    $applied = 0;  // langsung diterapkan (status menunggu)
    $proposed = 0; // dikirim sebagai usulan (status disetujui)

    DB::transaction(function () use (&$applied, &$proposed) {
        $ids = array_map('intval', $this->selected);

        $items = Bimbingan::whereIn('id', $ids)->lockForUpdate()->get();

        foreach ($items as $b) {
            // otorisasi
            if ($b->dosen_id !== Auth::user()->dosen->id) {
                continue;
            }

            if ($b->status === 'menunggu') {
                // auto-apply
                $b->tanggal_bimbingan = $this->tanggal_baru;
                $b->jam_mulai = $this->jam_mulai_baru;
                $b->jam_selesai = $this->jam_selesai_baru;
                if ($this->keterangan_baru) {
                    $b->pesan = $this->keterangan_baru;
                }

                // bersihkan usulan jika ada
                $b->usulan_tanggal_bimbingan = null;
                $b->usulan_jam_mulai = null;
                $b->usulan_jam_selesai = null;
                $b->status_perubahan = null;
                $b->waktu_perubahan_diajukan = null;

                $b->save();
                $applied++;
            } elseif ($b->status === 'disetujui') {
                 // simpan sebagai USULAN (menunggu persetujuan mahasiswa)
                $b->usulan_tanggal_bimbingan = $this->tanggal_baru;
                $b->usulan_jam_mulai = $this->jam_mulai_baru;
                $b->usulan_jam_selesai = $this->jam_selesai_baru;

                $b->status_perubahan = 'menunggu_mahasiswa';

                $b->waktu_perubahan_diajukan = now();
                if ($this->keterangan_baru) {
                    $b->pesan = $this->keterangan_baru;
                }
                $b->save();
                $proposed++;
            }
            // status lain (selesai/ditolak/dibatalkan) tidak diapa-apakan
        }
    });

    $msg = [];
    if ($applied)  $msg[] = "$applied diterapkan langsung";
    if ($proposed) $msg[] = "$proposed menunggu persetujuan mahasiswa";
    session()->flash('success', 'Perubahan jadwal tersimpan: '.implode(' & ', $msg));

    // tutup modal & bereskan state
    $this->tutupModalUbahJadwal();
    $this->clearSelection();
    // refresh halaman
    $this->dispatch('$refresh');
}



    // === BULK SELECTION (NEW) ===
    public function toggleBulkMode(): void
{
    $this->bulkMode = !$this->bulkMode;
    $this->clearSelection();
}

   

    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }

    public function selectPageIds(array $ids): void
    {
        // gabungkan & unik
        $merge = array_map('strval', $ids);
        $this->selected = array_values(array_unique(array_merge($this->selected, $merge)));
    }

    public function unselectPageIds(array $ids): void
    {
        $remove = array_map('strval', $ids);
        $this->selected = array_values(array_diff($this->selected, $remove));
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    
    // === EXISTING METHODS ===
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

        $bimbingan = $this->bimbinganTerpilih;
        $bimbingan->status = $this->status_baru;

        $bimbingan->tanggal_disetujui = null;
        $bimbingan->tanggal_ditolak = null;
        $bimbingan->tanggal_dibatalkan = null;
        $bimbingan->tanggal_selesai = null;

        switch ($this->status_baru) {
            case 'disetujui':
                $bimbingan->tanggal_disetujui = now();
                break;
            case 'ditolak':
                $bimbingan->tanggal_ditolak = now();
                $bimbingan->pesan = $this->pesan_penolakan;
                break;
            case 'dibatalkan':
                $bimbingan->tanggal_dibatalkan = now();
                $bimbingan->pesan = 'Dibatalkan oleh dosen.';
                break;
            case 'selesai':
                if (is_null($bimbingan->tanggal_disetujui)) {
                    $bimbingan->tanggal_disetujui = now();
                }
                $bimbingan->tanggal_selesai = now();
                break;
        }

        $bimbingan->save();

        if ($this->status_baru === 'disetujui') {
            session()->flash('success', 'Status bimbingan berhasil disetujui.');
        } else {
            session()->flash('success', 'Status bimbingan berhasil diperbarui dan dipindahkan ke riwayat.');
        }

        $this->closeModalStatus();
    }

      private function baseQuery()
    {
        $dosenId = Auth::user()->dosen->id;

        return Bimbingan::where('dosen_id', $dosenId)
            ->with('mahasiswa.user')
            ->when($this->search, function ($query) {
                $search = $this->search;
                $query->where(function ($q) use ($search) {
                    $q->where('topik', 'like', "%{$search}%")
                      ->orWhereHas('mahasiswa.user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->filterStatus, fn ($q, $status) => $q->where('status', $status))
            ->when($this->filterJenis,  fn ($q, $jenis)  => $q->where('jenis_bimbingan', $jenis))
            // saat bulk mode, tampilkan hanya yang eligible
            ->when($this->bulkMode && $this->filterStatus === '', fn($q) => $q->whereIn('status', ['menunggu','disetujui']));
    }
    
   public function render()
{
    $bimbingans = $this->baseQuery()
        ->latest('tanggal_bimbingan')
        ->paginate(10);

    return view('livewire.dosen.daftar-bimbingan', compact('bimbingans'));
}
public function updatedFilterStatus() { $this->resetPage(); $this->clearSelection(); }
public function updatedFilterJenis()  { $this->resetPage(); $this->clearSelection(); }


     
}
