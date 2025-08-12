<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\Bimbingan;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\NotifikasiPerubahanDimintaKeMahasiswa;
use App\Notifications\NotifikasiPerubahanOtomatisKeMahasiswa;

class DaftarBimbingan extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // ====== Filter & pencarian ======
    public string $search = '';
    protected $queryString = ['search'];
    public string $filterStatus = '';
    public string $filterJenis = '';
    public string $filterDosen = '';

    // ====== Detail modal ======
    public $bimbinganTerpilih;
    public bool $isDetailModalOpen = false;

    // ====== Ubah status (existing) ======
    public $status_baru = '';
    public $pesan_penolakan = '';
    public bool $isModalStatusOpen = false;

    // ====== BULK actions ======
    public bool $bulkMode = false;
    /** @var array<int> */
    public array $selected = [];       // id bimbingan terpilih
    public bool $selectPage = false;   // pilih semua di halaman aktif
    public bool $selectAll = false;    // pilih semua hasil query
    public int $totalMatching = 0;     // total eligible (menunggu/disetujui)

    // Modal ubah jadwal (bulk)
    public bool $showUbahModal = false;
    public ?string $tanggalBaru = null;
    public ?string $jamMulaiBaru = null;
    public ?string $jamSelesaiBaru = null;
    public ?string $alasan = null;

    // ====== Pagination reset ======
    public function updatingSearch()       { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterJenis()  { $this->resetPage(); }
    public function updatingFilterDosen()  { $this->resetPage(); }

    // ====== Render ======
    public function render()
    {
        $query = $this->baseQuery();

        // total eligible sesuai filter & search
        $this->totalMatching = (clone $query)
            ->whereIn('status', ['menunggu','disetujui'])
            ->count();

        $bimbingans = (clone $query)
            ->latest('tanggal_bimbingan')
            ->paginate(10);

        return view('livewire.dosen.daftar-bimbingan', compact('bimbingans'));
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

    // ====== Detail ======
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

    // ====== Ubah status (existing) ======
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
                $bimbingan->tanggal_disetujui = now(); break;
            case 'ditolak':
                $bimbingan->tanggal_ditolak = now();
                $bimbingan->pesan = $this->pesan_penolakan; break;
            case 'dibatalkan':
                $bimbingan->tanggal_dibatalkan = now();
                $bimbingan->pesan = 'Dibatalkan oleh dosen.'; break;
            case 'selesai':
                if (is_null($bimbingan->tanggal_disetujui)) {
                    $bimbingan->tanggal_disetujui = now();
                }
                $bimbingan->tanggal_selesai = now(); break;
        }

        $bimbingan->save();

        session()->flash('success',
            $this->status_baru === 'disetujui'
                ? 'Status bimbingan berhasil disetujui.'
                : 'Status bimbingan berhasil diperbarui dan dipindahkan ke riwayat.'
        );

        $this->closeModalStatus();
    }

    // ====== BULK actions ======
    public function toggleBulkMode()
    {
        $this->bulkMode  = !$this->bulkMode;
        $this->selected  = [];
        $this->selectPage = false;
        $this->selectAll  = false;

        // saat masuk bulk mode & filterStatus bukan eligible → reset
        if ($this->bulkMode && !in_array($this->filterStatus, ['', 'menunggu', 'disetujui'], true)) {
            $this->filterStatus = '';
        }

        $this->resetPage();
    }

    public function toggleSelectPage()
    {
        if (!$this->selectPage) {
            // uncheck master -> kosongkan
            $this->selected = [];
            return;
        }

        // pilih semua id di HALAMAN AKTIF (eligible saja)
        $perPage = 10;
        $page = max(1, (int) ($this->page ?? 1));
        $offset = ($page - 1) * $perPage;

        $ids = (clone $this->baseQuery())
            ->whereIn('status', ['menunggu','disetujui'])
            ->orderBy('tanggal_bimbingan', 'desc')
            ->skip($offset)->take($perPage)
            ->pluck('id')->map(fn($v) => (int) $v)->all();

        $this->selected = $ids;
    }

    public function selectAllResults()
    {
        // NOTE: jika data besar, pertimbangkan “lazy select all”
        $this->selectAll = true;
        $this->selected = (clone $this->baseQuery())
            ->whereIn('status', ['menunggu','disetujui'])
            ->pluck('id')->map(fn($v) => (int) $v)->all();
    }

    public function openUbahModal()
    {
        if (count($this->selected) === 0) return;

        // validasi kepemilikan + eligible
        $valid = Bimbingan::whereIn('id', $this->selected)
            ->where('dosen_id', Auth::user()->dosen->id)
            ->whereIn('status', ['menunggu','disetujui'])
            ->count();

        if ($valid === 0) {
            session()->flash('error', 'Tidak ada bimbingan yang bisa diubah jadwalnya.');
            return;
        }

        $this->showUbahModal = true;
    }

    private function cekBentrokJadwal(int $dosenId, int $mahasiswaId, string $tanggal, string $mulai, string $selesai, ?int $abaikanId = null): bool
    {
        return Bimbingan::when($abaikanId, fn($q) => $q->where('id', '!=', $abaikanId))
            ->whereDate('tanggal_bimbingan', $tanggal)
            ->where(function ($q) use ($dosenId, $mahasiswaId) {
                $q->where('dosen_id', $dosenId)
                  ->orWhere('mahasiswa_id', $mahasiswaId);
            })
            ->where(function ($q) use ($mulai, $selesai) {
                $q->whereBetween('jam_mulai', [$mulai, $selesai])
                  ->orWhereBetween('jam_selesai', [$mulai, $selesai])
                  ->orWhere(function ($qq) use ($mulai, $selesai) {
                      $qq->where('jam_mulai', '<=', $mulai)
                         ->where('jam_selesai', '>=', $selesai);
                  });
            })
            ->exists();
    }

    public function submitBatch()
    {
        // 0) Validasi
        $this->validate([
            'selected'        => 'required|array|min:1',
            'tanggalBaru'     => 'required|date',
            'jamMulaiBaru'    => 'required|date_format:H:i',
            'jamSelesaiBaru'  => 'required|date_format:H:i|after:jamMulaiBaru',
            'alasan'          => 'required|string|max:255',
        ]);

        $dosenId = Auth::user()->dosen->id; // FIX: Auth:: (bukan auth::)

        // 1) Ambil & verifikasi item
        $items = Bimbingan::with('mahasiswa.user')
            ->whereIn('id', $this->selected)
            ->where('dosen_id', $dosenId)
            ->whereIn('status', ['menunggu','disetujui'])
            ->get();

        if ($items->count() !== count($this->selected)) {
            throw ValidationException::withMessages(['selected' => 'Beberapa pilihan tidak valid.']);
        }

        // 2) Aturan 24 jam (ALL-OR-NOTHING)
        $batas = now()->addHours(24);
        $langgar = [];
        foreach ($items as $b) {
            $mulaiLama = Carbon::parse($b->tanggal_bimbingan.' '.$b->jam_mulai);
            if ($mulaiLama->lte($batas)) {
                $langgar[] = "#{$b->id} ({$b->mahasiswa->user->name})";
            }
        }
        if ($langgar) {
            throw ValidationException::withMessages([
                'waktu' => 'Batch dibatalkan: jadwal lama ≤ 24 jam untuk: '.implode(', ', $langgar),
            ]);
        }

        // 3) Cek bentrok jadwal baru
        $bentrok = [];
        foreach ($items as $b) {
            if ($this->cekBentrokJadwal(
                $b->dosen_id, $b->mahasiswa_id,
                $this->tanggalBaru, $this->jamMulaiBaru, $this->jamSelesaiBaru,
                $b->id
            )) {
                $bentrok[] = "#{$b->id} ({$b->mahasiswa->user->name})";
            }
        }
        if ($bentrok) {
            throw ValidationException::withMessages([
                'jadwal' => 'Batch dibatalkan: jadwal baru bentrok pada: '.implode(', ', $bentrok),
            ]);
        }

        // 4) Update dalam transaksi, kumpulkan item untuk notifikasi
        $autoItems = [];    // status menunggu (auto apply)
        $pendingItems = []; // status disetujui (minta konfirmasi)

        DB::transaction(function () use ($items, &$autoItems, &$pendingItems) {
            foreach ($items as $b) {
                if ($b->status === 'menunggu') {
                    // langsung ubah jadwal utama
                    $b->tanggal_bimbingan = $this->tanggalBaru;
                    $b->jam_mulai         = $this->jamMulaiBaru;
                    $b->jam_selesai       = $this->jamSelesaiBaru;
                    $b->usulan_tanggal_bimbingan = null;
                    $b->usulan_jam_mulai         = null;
                    $b->usulan_jam_selesai       = null;
                    $b->status_perubahan         = null;
                    $b->waktu_perubahan_diajukan = null;
                    $b->pesan = 'Perubahan jadwal oleh dosen: '.$this->alasan;
                    $b->save();

                    $autoItems[] = $b->fresh(['mahasiswa.user']);
                } else {
                    // simpan usulan, tunggu konfirmasi mahasiswa
                    $b->usulan_tanggal_bimbingan = $this->tanggalBaru;
                    $b->usulan_jam_mulai         = $this->jamMulaiBaru;
                    $b->usulan_jam_selesai       = $this->jamSelesaiBaru;
                    $b->status_perubahan         = 'menunggu_mahasiswa';
                    $b->waktu_perubahan_diajukan = now();
                    $b->pesan = 'Usulan perubahan jadwal oleh dosen: '.$this->alasan;
                    $b->save();

                    $pendingItems[] = $b->fresh(['mahasiswa.user']);
                }
            }
        });

        // 5) Notifikasi setelah commit
        foreach ($autoItems as $b) {
            $b->mahasiswa->user?->notify(new NotifikasiPerubahanOtomatisKeMahasiswa($b, $this->alasan));
        }
        foreach ($pendingItems as $b) {
            $b->mahasiswa->user?->notify(new NotifikasiPerubahanDimintaKeMahasiswa($b));
        }

        // 6) UI bersih-bersih (FIX: jangan chain tipe beda)
        $this->showUbahModal = false;
        $this->selected      = [];
        $this->selectPage    = false;
        $this->selectAll     = false;

        session()->flash(
            'success',
            "Perubahan diproses: ".count($autoItems)." diubah langsung, ".count($pendingItems)." menunggu persetujuan mahasiswa."
        );
    }
}
