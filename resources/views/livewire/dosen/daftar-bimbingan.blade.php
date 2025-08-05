
<div>
    {{-- Area untuk menampilkan notifikasi sukses/error --}}
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

     {{-- BARIS UNTUK TOMBOL TAB --}}
     <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item mx-3">
            {{-- Tambahkan class 'active' secara dinamis berdasarkan properti $tab_aktif --}}
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
                <div class="d-flex justify-content-end p-3 align-items-center gap-3">
                    {{-- Search Bar --}}
                    <div class="input-group input-group-merge" style="max-width: 300px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Nama Mahasiswa, Topik...">
                    </div>
                    
                    {{-- Tombol Cetak Rekap --}}
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalExportLaporan">
                        <i class="bx bx-printer me-1"></i> Cetak Rekap
                    </button>
                </div>
            </div>


            
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-responsive-sm-text">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Bimbingan</th>
                        <th>Nama</th>
                        <th>NIM</th>
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
                            <td>{{ optional($value->mahasiswa->user)->name }}</td>
                            <td>{{ $value->mahasiswa->nim }}</td>
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

                                        <button type="button" class="dropdown-item" 
                                                wire:click="lihatDetail({{ $value->id }})">
                                                <i class="bx bx-show me-1"></i> Lihat Detail
                                        </button>
                                                                                    
                                    @if ($value->status == 'menunggu' || $value->status == 'disetujui' )
                                        
                                        <button type="button" class="dropdown-item" 
                                                wire:click="bukaModalStatus({{ $value->id }})">
                                            <i class="bx bx-edit-alt me-1"></i> Ubah Status
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
                    {{-- Form ini akan menggunakan method GET --}}
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


    @if($isModalStatusOpen)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status Bimbingan</h5>
                    <button type="button" class="btn-close" wire:click="closeModalStatus"></button>
                </div>
                <form wire:submit.prevent="simpanPerubahanStatus">
                    <div class="modal-body">
                 
                        <div>
                            <p class="mb-1"><strong>Mahasiswa:</strong> {{ $bimbinganTerpilih->mahasiswa->user->name }}</p>
                            <p><strong>Topik:</strong> {{ $bimbinganTerpilih->topik }}</p>
                        </div>
                        <hr>

                        <div class="mb-3">
                            <label for="status_baru" class="form-label">Status Baru</label>
                            <select wire:model.live="status_baru" id="status_baru" class="form-select @error('status_baru') is-invalid @enderror">
                                <option value="">Pilih Status</option>
                                @if($bimbinganTerpilih->status == 'menunggu')
                                    <option value="disetujui">Setujui</option>
                                    <option value="ditolak">Tolak</option>
                                @elseif($bimbinganTerpilih->status == 'disetujui')
                                    <option value="selesai">Tandai Selesai</option>
                                    <option value="dibatalkan">Batalkan</option>
                                @endif
                            </select>
                            @error('status_baru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

              
                        @if ($status_baru === 'ditolak')
                            <div class="mb-3">
                                <label for="pesan_penolakan" class="form-label">Alasan Penolakan</label>
                                <textarea wire:model="pesan_penolakan" id="pesan_penolakan" class="form-control @error('pesan_penolakan') is-invalid @enderror" rows="3" placeholder="Contoh: Jadwal bentrok, mohon ajukan di hari lain."></textarea>
                                @error('pesan_penolakan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" wire:click="closeModalStatus">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@endif
</div>


