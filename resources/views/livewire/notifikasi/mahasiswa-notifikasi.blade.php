<div class="dropdown" wire:ignore.self>
  {{-- TOMBOL LONCENG --}}
  <button class="btn position-relative" type="button" id="btnNotif"
          data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bx bx-bell fs-4"></i>
    @php $badge = min($jumlahBelumDibaca ?? 0, 9); @endphp
    @if(($jumlahBelumDibaca ?? 0) > 0)
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        {{ $badge }}@if(($jumlahBelumDibaca??0)>9)+@endif
        <span class="visually-hidden">unread</span>
      </span>
    @endif
  </button>

  {{-- DROPDOWN --}}
  <div class="dropdown-menu dropdown-menu-end p-0 shadow" aria-labelledby="btnNotif" style="width: 360px;">
    {{-- Header --}}
    <div class="px-3 pt-3 pb-2 border-bottom d-flex align-items-center gap-2">
      <h6 class="mb-0">Notifikasi</h6>
      <span class="badge bg-primary ms-1">Belum: {{ $jumlahBelumDibaca }}</span>
      <div class="ms-auto btn-group btn-group-sm" role="group" aria-label="Filter">
        <button class="btn btn-outline-secondary {{ ($tab==='semua')?'active':'' }}"
                wire:click="$set('tab','semua')">Semua</button>
        <button class="btn btn-outline-secondary {{ ($tab==='belum')?'active':'' }}"
                wire:click="$set('tab','belum')">Belum</button>
      </div>
    </div>

    {{-- Pencarian mini --}}
    <div class="p-2 border-bottom">
      <div class="input-group input-group-sm">
        <span class="input-group-text"><i class="bx bx-search"></i></span>
        <input type="text" class="form-control" placeholder="Cari..."
               wire:model.live.debounce.300ms="pencarian">
      </div>
    </div>

    {{-- DAFTAR ITEM --}}
    <div class="list-group list-group-flush" wire:poll.keep-alive.45s>
      @forelse($items as $n)
        @php
          $data = is_array($n->data) ? $n->data : (array)$n->data;
          $unread = is_null($n->read_at);
          $jenis  = $data['jenis'] ?? 'lainnya';
          $judul  = $data['judul'] ?? 'Notifikasi';
          $pesan  = $data['pesan'] ?? '';
          $ikon = match($jenis){
            'perubahan_diminta'  => 'bx-calendar-exclamation',
            'perubahan_otomatis' => 'bx-calendar-check',
            'respon_mahasiswa'   => 'bx-message-dots',
            default              => 'bx-bell'
          };
          $badgeKls = match($jenis){
            'perubahan_diminta'  => 'bg-label-warning',
            'perubahan_otomatis' => 'bg-label-success',
            'respon_mahasiswa'   => 'bg-label-info',
            default              => 'bg-label-secondary'
          };
        @endphp

        <div class="list-group-item d-flex gap-3 align-items-start notif-row {{ $unread ? 'bg-light' : '' }}">
          <div class="notif-icon rounded-circle d-flex align-items-center justify-content-center">
            <i class="bx {{ $ikon }}"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <strong class="small mb-0">{{ $judul }}</strong>
                <span class="badge {{ $badgeKls }} text-capitalize">{{ str_replace('_',' ', $jenis) }}</span>
                @if($unread) <span class="notif-dot"></span> @endif
              </div>
              <small class="text-muted">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</small>
            </div>
            <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($pesan, 90) }}</div>
            <div class="mt-2 d-flex gap-2">
              {{-- “Buka” nanti diarahkan ke halaman detail/konfirmasi sesuai jenis --}}
              <a href="{{ url('/mahasiswa/notifikasi') }}" class="btn btn-sm btn-primary">Buka</a>
              @if($unread)
                <button class="btn btn-sm btn-outline-secondary"
                        wire:click="tandaiDibaca('{{ $n->id }}')">Tandai dibaca</button>
              @endif
              <button class="btn btn-sm btn-outline-danger"
                      wire:click="hapus('{{ $n->id }}')">Hapus</button>
            </div>
          </div>
        </div>
      @empty
        <div class="p-4 text-center text-muted">
          <i class="bx bx-bell mb-2" style="font-size:1.5rem;"></i>
          <div class="small">Belum ada notifikasi.</div>
        </div>
      @endforelse
    </div>

    {{-- Footer --}}
    <div class="p-2 border-top d-flex justify-content-between align-items-center">
      <a class="btn btn-link btn-sm text-decoration-none" href="{{ url('/mahasiswa/notifikasi') }}">
        Lihat semua
      </a>
      <button class="btn btn-sm btn-outline-secondary"
              wire:click="$set('tab','belum')"
              {{ ($jumlahBelumDibaca ?? 0) > 0 ? '' : 'disabled' }}>
        Tandai semua dibaca
      </button>
    </div>
  </div>

  {{-- STYLE KECIL --}}
  <style>
    .notif-icon { width: 36px; height: 36px; background: rgba(var(--bs-primary-rgb), .08); color: var(--bs-primary); }
    .notif-row:hover { background: rgba(var(--bs-primary-rgb), .06) !important; transition: background .15s; }
    .notif-dot { width:8px; height:8px; background: var(--bs-danger); border-radius: 50%; display:inline-block; }
  </style>

  {{-- Realtime hook (opsional): tambah count saat event masuk --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const kanal = @json("mahasiswa.".(auth()->user()->mahasiswa->id ?? 0));
      if (window.Echo && kanal) {
        // Hindari double subscribe
        if (!window.__bellSub) {
          window.__bellSub = window.Echo.private(kanal)
            .listen('.perubahan-diminta', () => Livewire.dispatch('refreshNotifikasi'))
            .listen('.perubahan-otomatis', () => Livewire.dispatch('refreshNotifikasi'));
        }
      }
    });
  </script>
</div>
