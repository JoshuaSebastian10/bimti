
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
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-header">Semua Jadwal Bimbingan Dosen</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end p-3">
                    <div class="input-group input-group-merge" style="max-width: 300px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Nama Dosen atau Hari...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Dosen</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th>aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($semuaJadwal as $jadwal)
                        <tr>
                            <td><strong>{{ optional($jadwal->dosen->user)->name }}</strong></td>
                            <td>{{ $jadwal->hari }}</td>
                            <td>{{ $jadwal->JamMulaiFormat }} - {{ $jadwal->JamSelesaiFormat }}</td>
                             <td>{{ $jadwal->kuota }}</td>
                            <td style="width: 120px;">
                                {{-- Tombol Toggle Interaktif --}}
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                        wire:click="toggleStatus({{ $jadwal->id }})"
                                        {{ $jadwal->is_active ? 'checked' : '' }}>
                                </div>
                                @if ($jadwal->is_active == '1')
                                    Aktif
                                @else
                                Tidak Aktif
                                @endif
                            </td>
                            <td>
                                    <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" 
                                                wire:click="edit({{ $jadwal->id }})">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                       
                                    <button type="button" class="dropdown-item"
                                                wire:click="deleteJadwal({{ $jadwal->id }})"
                                                wire:confirm="Anda yakin ingin menghapus jadwal ini?">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                    </button>

                                         </div>
                                </div>
                                    
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data jadwal yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body">
            {{ $semuaJadwal->links() }}
        </div>
    </div>
    @if($isEditModalOpen)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jadwal Bimbingan</h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
 
                            <div class="mb-3">
                                <label for="edit_hari" class="form-label">Hari</label>
                                <select wire:model="hari" id="edit_hari" class="form-select @error('hari') is-invalid @enderror">
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                </select>
                                @error('hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_jam_mulai" class="form-label">Jam Mulai</label>
                                    <input wire:model="jam_mulai" type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="edit_jam_mulai">
                                    @error('jam_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_jam_selesai" class="form-label">Jam Selesai</label>
                                    <input wire:model="jam_selesai" type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="edit_jam_selesai">
                                    @error('jam_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_kuota" class="form-label">Kuota Mahasiswa</label>
                                <input wire:model="kuota" type="number" class="form-control @error('kuota') is-invalid @enderror" id="edit_kuota" min="1">
                                @error('kuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" wire:click="closeEditModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>