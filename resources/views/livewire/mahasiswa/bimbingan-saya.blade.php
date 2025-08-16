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

        <div class="card">
        <h5 class="card-header">Pencarian & Filter</h5>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Topik, Dosen...">
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
                        <option value="proposal">Proposal</option>
                        <option value="skripsi">Skripsi</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mt-4">
     
        <div class="row">
            <div class="col-12 col-sm-6">
                <h5 class="card-header">Daftar Ajuan Bimbingan</h5>
            </div>
              <div class="col-12 col-sm-6  align-content-center text-start text-sm-end">
                     <button type="button" class="ms-5 me-sm-5 mb-sm-0 mb-5 btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalExportLaporan">
                        <i class="bx bx-printer me-1"></i> Cetak Rekap
                    </button>
              </div>
        </div>
        <div class="table-responsive text-nowrap table-small-mobile">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Bimbingan</th>
                        <th>Dosen</th>
                        <th>Topik</th>
                        <th>Tanggal Diajukan</th>
                        <th>Jadwal Bimbingan</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                                        {{-- @if($value->lampiran_path)
                                            <a class="dropdown-item" href="{{ route('bimbingan.lampiran.show', $value->id) }}" target="_blank">
                                                <i class="bx bx-paperclip me-1"></i> Lihat Lampiran
                                            </a>
                                        @endif --}}

                                        <button type="button" class="dropdown-item" 
                                                wire:click="lihatDetail({{ $value->id }})">
                                                <i class="bx bx-show me-1"></i> Lihat Detail
                                        </button>
                                            
                                        
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

                        {{-- Modal Cetak --}}
        <div class="modal fade" id="modalExportLaporan" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cetak Laporan Bimbingan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('laporan.bimbingan.export') }}" method="GET" target="_blank">
                        <div class="modal-body">
                            <p>Silakan atur filter untuk laporan yang ingin Anda generate.</p>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Filter Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="semua">Semua Status</option>
                                    <option value="aktif">Bimbingan Aktif (Menunggu & Disetujui)</option>
                                    <option value="selesai">Bimbingan Selesai</option>
                                    <option value="ditolak">Bimbingan Ditolak</option>
                                    <option value="dibatalkan">Bimbingan Dibatalkan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Format Laporan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" id="formatPDF" value="pdf" checked>
                                    <label class="form-check-label" for="formatPDF">PDF</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel">
                                    <label class="form-check-label" for="formatExcel">Excel</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Generate Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
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
                                
                                <p>
                                    @php
                                        $jenisBimbinganClass = ['akademik'=>'secondary','proposal'=>'info','skripsi'=>'primary'][$bimbinganTerpilih->jenis_bimbingan] ?? 'dark';
                                    @endphp

                                    <span class="badge bg-label-{{$jenisBimbinganClass}}">{{ ucfirst($bimbinganTerpilih->jenis_bimbingan) }}</span>
                                </p>
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

                        <h6>Riwayat Proses</h6>
                        <ul class="timeline-v2">
                            
                            {{-- 1. Diajukan --}}
                            @if($bimbinganTerpilih->tanggal_pengajuan)
                            <li class="timeline-item">
                                <div class="timeline-point timeline-point-primary">
                                    <i class='bx bx-paper-plane'></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">Diajukan</h6>
                                        <small class="text-muted">{{ $bimbinganTerpilih->tanggal_pengajuan->format('d M Y, H:i')  }}</small>
                                    </div>
                                    <p class="mb-0">Permintaan bimbingan dikirim.</p>
                                </div>
                            </li>
                            @endif

                            {{-- 2. Disetujui --}}
                            @if($bimbinganTerpilih->tanggal_disetujui)
                            <li class="timeline-item">
                                <div class="timeline-point timeline-point-success">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">Disetujui</h6>
                                        <small class="text-muted">{{ $bimbinganTerpilih->tanggal_disetujui->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-0">Jadwal dikonfirmasi oleh dosen.</p>
                                </div>
                            </li>
                            @endif

                            {{-- 3. Ditolak --}}
                            @if($bimbinganTerpilih->tanggal_ditolak)
                            <li class="timeline-item">
                                <div class="timeline-point timeline-point-danger">
                                    <i class='bx bx-x-circle'></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">Ditolak</h6>
                                        <small class="text-muted">{{ $bimbinganTerpilih->tanggal_ditolak->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-0 text-danger fst-italic">Alasan: "{{ $bimbinganTerpilih->pesan }}"</p>
                                </div>
                            </li>
                            @endif

                            {{-- 4. Dibatalkan --}}
                            @if($bimbinganTerpilih->tanggal_dibatalkan)
                            <li class="timeline-item">
                                <div class="timeline-point timeline-point-secondary">
                                    <i class='bx bx-block'></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">Dibatalkan</h6>
                                        <small class="text-muted">{{ $bimbinganTerpilih->tanggal_dibatalkan->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-0">Jadwal bimbingan telah dibatalkan.</p>
                                </div>
                            </li>
                            @endif

                            {{-- 5. Selesai --}}
                            @if($bimbinganTerpilih->tanggal_selesai)
                            <li class="timeline-item">
                                <div class="timeline-point timeline-point-info">
                                    <i class='bx bx-badge-check'></i>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0">Selesai</h6>
                                        <small class="text-muted">{{ $bimbinganTerpilih->tanggal_selesai->format('d M Y, H:i') }}</small>
                                    </div>
                                    <p class="mb-0">Sesi bimbingan telah selesai dilaksanakan.</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                        {{-- ... --}}
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


