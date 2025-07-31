<div>
    <div class="card">
        <h5 class="card-header">Filter & Pencarian</h5>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Topik, Mahasiswa...">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterDosen" class="form-select">
                        <option value="">Semua Dosen</option>
                        @foreach($dosens as $dosen)
                            <option value="{{ $dosen->id }}">{{ $dosen->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterJenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="akademik">Akademik</option>
                        <option value="skripsi">Skripsi</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <h5 class="card-header">Daftar Semua Bimbingan</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Jadwal Bimbingan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($bimbingans as $bimbingan)
                        <tr>
                            <td>{{ optional($bimbingan->mahasiswa->user)->name }}</td>
                            <td>{{ optional($bimbingan->dosen->user)->name }}</td>
                            <td>{{ $bimbingan->tanggalPengajuanFormat }}</td>
                            <td>{{ $bimbingan->tanggalBimbinganFormat }}</td>
                            <td>
                                @php
                                    $statusClass = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger','selesai'=>'info','dibatalkan'=>'secondary'][$bimbingan->status] ?? 'dark';
                                @endphp
                                <span class="badge bg-label-{{$statusClass}}">{{ ucfirst($bimbingan->status) }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="lihatDetail({{ $bimbingan->id }})">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
            {{ $bimbingans->links() }}
        </div>
    </div>

        @if($isDetailModalOpen && $bimbinganTerpilih)
        <div class="modal fade show" style="display: block;" tabindex="-1">
  
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Bimbingan</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
           
                        <div class="row mb-3">

                            <div class="col-md-6">
                                <small class="text-light fw-medium">Mahasiswa</small>
                                <p class="fw-bold">{{ $bimbinganTerpilih->mahasiswa->user->name }}</p>
                            </div>

                            <div class="col-md-6">
                                <small class="text-light fw-medium">Dosen</small>
                                <p class="fw-bold">{{ $bimbinganTerpilih->dosen->user->name }}</p>
                            </div>

                             <div class="col-md-6">
                                <small class="text-light fw-medium">Jadwal Diajukan</small>
                                <p class="fw-bold">{{ $bimbinganTerpilih->tanggalPengajuanFormat}}</p>
                            </div>

                            <div class="col-md-6">
                                <small class="text-light fw-medium">Jadwal Bimbingan</small>
                                <p class="fw-bold">{{ $bimbinganTerpilih->TanggalBimbinganFormat  }} Jam {{ $bimbinganTerpilih->jamMulaiFormat }} - {{ $bimbinganTerpilih->jamSelesaiFormat}}</p>
                            </div>

                            <div class="col-md-6">
                                <small class="text-light fw-medium">Jenis Bimbingan</small>
                                <p><span class="badge bg-label-primary">{{ ucfirst($bimbinganTerpilih->jenis_bimbingan) }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-light fw-medium">Status</small>
                                <p>
                                    @php
                                        $statusClass = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger','selesai'=>'info','dibatalkan'=>'secondary'][$bimbinganTerpilih->status] ?? 'dark';
                                    @endphp
                                    <span class="badge bg-label-{{$statusClass}}">{{ ucfirst($bimbinganTerpilih->status) }}</span>
                                </p>
                            </div>
                        </div>
                        <hr>
                 
                        <div>
                            <h6>Topik Pembahasan</h6>
                            <p>{{ $bimbinganTerpilih->topik }}</p>
                        </div>
                        <hr>
                   
                        @if($bimbinganTerpilih->pesan)
                            <div>
                                <h6>Pesan dari Dosen</h6>
                                <p class="text-danger fst-italic">"{{ $bimbinganTerpilih->pesan }}"</p>
                            </div>
                            <hr>
                        @endif

    
                        <div>
                            <h6>Lampiran</h6>
                            @if($bimbinganTerpilih->lampiran_path)
                                <a href="{{ route('bimbingan.lampiran.show', $bimbinganTerpilih->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show-alt me-1"></i> Lihat Lampiran
                                </a>
                            @else
                                <p class="text-muted">Tidak ada lampiran.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" wire:click="closeDetailModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>