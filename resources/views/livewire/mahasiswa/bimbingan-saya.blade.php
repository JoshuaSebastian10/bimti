<div>
        @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
     <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item mx-3">
            <a class="btn btn-costume-responsive  {{ $tab_aktif === 'aktif' ? 'btn-primary' : 'btn-outline-primary' }}" href="#" wire:click.prevent="$set('tab_aktif', 'aktif')">
                <i class="bx bx-bell me-1"></i> Bimbingan Aktif
            </a>
        </li>
        <li class="nav-item">
            <a class="btn btn-costume-responsive {{ $tab_aktif === 'riwayat' ? 'btn-primary' : 'btn-outline-primary' }}" href="#" wire:click.prevent="$set('tab_aktif', 'riwayat')">
                <i class="bx bx-history me-1"></i> Riwayat Bimbingan
            </a>
        </li>
    </ul>

    
    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-header">Daftar Ajuan Bimbingan</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end p-3">
                    <div class="input-group input-group-merge" style="max-width: 300px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Nama, Topik...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-responsive-sm-text">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Bimbingan</th>
                        <th>Dosen</th>
                        <th>Topik</th>
                        <th>Tanggal Diajukan</th>
                        <th>Jadwal Bimbingan</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($bimbingans as $index => $value)
                        <tr>
                            <td class="text-4">{{ $bimbingans->firstItem() + $index }}</td>
                            <td>
                                @if($value->jenis_bimbingan == 'akademik')
                                    <span class="badge bg-label-secondary">Akademik</span>
                                @elseif($value->jenis_bimbingan == 'proposal')
                                    <span class="badge bg-label-info">Proposal</span>
                                @else
                                    <span class="badge bg-label-primary">Skripsi</span>   
                                @endif
                            </td>
                            <td><strong>{{ $value->dosen->user->name }}</strong></td>
                            <td>{{ Str::limit($value->topik, 30) }}</td>
                            <td>{{ $value->TanggalPengajuanFormat }}</td>
                            <td>
                                {{ $value->TanggalBimbinganFormat }}
                                <br>
                                <small class="text-muted">{{ $value->JamMulaiFormat }} - {{ $value->JamSelesaiFormat }}</small>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'menunggu' => 'bg-label-warning',
                                        'disetujui' => 'bg-label-success',
                                        'ditolak' => 'bg-label-danger',
                                        'selesai' => 'bg-label-info',
                                    ][$value->status] ?? 'bg-label-dark';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($value->status) }}</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        @if($value->lampiran)
                                            <a class="dropdown-item" href="{{ route('bimbingan.lampiran.show', $value->id) }}" target="_blank">
                                                <i class="bx bx-paperclip me-1"></i> Lihat Lampiran
                                            </a>
                                        @endif
                                        
                                        @if ($value->status == 'menunggu')
                                             <button type="button" class="dropdown-item" 
                                                wire:click="editBimbingan({{ $value->id }})">
                                            <i class="bx bx-edit-alt me-1"></i> Edit Bimbingan
                                        </button>

                                         <button type="button" class="dropdown-item" 
                                                wire:click="deleteBimbingan({{ $value->id }})"
                                                wire:confirm="Anda yakin ingin menghapus ajuan bimbingan ini?">
                                            <i class="bx bx-trash me-1"></i> Hapus 
                                        </button>

                                        
                                        @endif
                                            <button type="button" class="dropdown-item" 
                                                    wire:click="lihatDetail({{ $value->id }})">
                                                     <i class="bx bx-show me-1"></i> Lihat Detail
                                            </button>
                                            
                                       
                                        
                                     
                                      
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Tidak ada data ajuan bimbingan.</td>
                        </tr>
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
                        <h5 class="modal-title">Detail Bimbingan:</h5>
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

        @if($isEditModalOpen)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Ajuan Bimbingan</h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <form wire:submit.prevent="updateBimbingan">
                        <div class="modal-body">
                            <p><strong>Dosen:</strong> {{ $bimbinganUntukDiedit->dosen->user->name }}</p>
                            <hr>
                      
                            <div class="mb-3">
                                <label for="topik_edit" class="form-label">Topik Bimbingan</label>
                                <textarea wire:model="topik" class="form-control @error('topik') is-invalid @enderror" id="topik_edit" rows="3"></textarea>
                                @error('topik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                 
                            <div class="mb-3">
                                <label class="form-label">Lampiran Saat Ini</label>
                                <div>
                                    @if ($bimbinganUntukDiedit->lampiran_path)
                                        <a href="{{ route('bimbingan.lampiran.show', $bimbinganUntukDiedit->id) }}" target="_blank">Lihat File</a>
                                    @else
                                        <span class="text-muted">Tidak ada lampiran.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="lampiran_baru" class="form-label">Ganti Lampiran (Opsional)</label>
                                <input wire:model="lampiranBaru" type="file" class="form-control @error('lampiranBaru') is-invalid @enderror" id="lampiran_baru">
                                <div wire:loading wire:target="lampiranBaru" class="mt-2 text-muted">Mengunggah...</div>
                                @error('lampiranBaru') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" wire:click="closeEditModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    
</div>


