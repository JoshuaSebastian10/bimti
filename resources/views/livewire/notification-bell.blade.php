<div class="nav-item dropdown" wire:poll.10s.keep-alive>
  <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
    <i class="ti ti-bell"></i>
    @if($unread)<span class="badge bg-danger">{{ $unread }}</span>@endif
  </a>
  <ul class="dropdown-menu dropdown-menu-end" style="min-width:340px">
    <li class="dropdown-header d-flex align-items-center">
      <span class="fw-bold">Notifikasi</span>
      <button class="btn btn-link btn-sm ms-auto" wire:click="markAllRead">Tandai semua dibaca</button>
    </li>
    <li><hr class="dropdown-divider"></li>

    @forelse($items as $n)
      <li class="{{ $n->read_at ? '' : 'bg-light' }}">
        <div class="dropdown-item d-flex justify-content-between">
          <div class="me-2">
            <div class="fw-semibold">{{ data_get($n->data,'title') }}</div>
            <div class="small">{{ data_get($n->data,'message') }}</div>
            <div class="text-muted small">
              {{ $n->created_at->timezone('Asia/Makassar')->diffForHumans() }}
            </div>
          </div>
          <button class="btn btn-sm btn-outline-secondary"
                  wire:click="markRead('{{ $n->id }}')">Selesai</button>
        </div>
      </li>
    @empty
      <li><div class="dropdown-item text-muted">Tidak ada notifikasi</div></li>
    @endforelse
  </ul>
</div>
