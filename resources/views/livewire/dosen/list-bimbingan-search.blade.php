
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
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Topik</th>
                        <th>Jenis</th>
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
                            <td>{{ optional($value->mahasiswa->user)->name }}</td>
                            <td>{{ $value->mahasiswa->nim }}</td>
                            <td>{{ Str::limit($value->topik, 30) }}</td>
                            <td>
                            
                                @if($value->jenis_bimbingan == 'akademik')
                                    <span class="badge bg-label-secondary">Akademik</span>
                                @else
                                    <span class="badge bg-label-primary">Skripsi</span>
                                @endif
                            </td>
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
                              
                                        @if($value->lampiran_path)
                                            <a class="dropdown-item" href="{{ route('bimbingan.lampiran.show', $value->id) }}" target="_blank">
                                                <i class="bx bx-paperclip me-1"></i> Lihat Lampiran
                                            </a>
                                        @endif
                                        
                                    
                                        <button type="button" class="dropdown-item" 
                                                wire:click="bukaModalStatus({{ $value->id }})">
                                            <i class="bx bx-edit-alt me-1"></i> Ubah Status
                                        </button>

                                
                                        <button type="button" class="dropdown-item" 
                                                wire:click="deleteBimbingan({{ $value->id }})"
                                                wire:confirm="Anda yakin ingin menghapus ajuan bimbingan ini?">
                                            <i class="bx bx-trash me-1"></i> Hapus
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


