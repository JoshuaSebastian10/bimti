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

@if (session()->has('successdelete'))
<div class="alert alert-warning alert-dismissible" role="alert">
    {{ session('successdelete') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

    <div class="card">

        <div class="row align-items-center">
            <div class="col-auto me-auto">
                <h5 class="card-header">Data Mahasiswa</h5>
            </div>
            <div class="col-10 mb-5 ms-5 col-sm-6 col-md-4 col-lg-4  my-sm-0 me-sm-5">
                
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari nama atau...">
                    </div>
            </div>
        </div>

    
   
        <div class="table-responsive text-nowrap">
            <table class="table table-responsive-sm-text    ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Dosen PA</th>
                        <th>Status Akun</th>
                        <th>Status Bimbingan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
        
                    <tbody class="table-border-bottom-0">
                        @forelse($mahasiswa as $index => $mhs)
                            <tr>
                                <td class="text-4">{{ $mahasiswa->firstItem() + $index }}</td>
                                <td>{{ optional($mhs->user)->name }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ optional($mhs->dosenPa->user)->name ?? 'Belum Diatur' }}</td>
                                <td><span class="badge bg-label-primary me-1">{{ ucfirst($mhs->status_akun) }}</span></td>
                                <td><span class="badge bg-label-info me-1">{{ ucfirst($mhs->status_bimbingan) }}</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.dataMahasiswa.edit', $mhs->id) }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </a>

                                            @if($mhs->status_bimbingan == 'proposal' || $mhs->status_bimbingan == 'skripsi')
                                            <button type="button" class="dropdown-item" 
                                            wire:click="bukaModalPembimbing1({{ $mhs->id }})">
                                            <i class="icon-base bx bx-user-check me-1"></i>
                                            
                                            @if($mhs->pembimbing_skripsi_1_id)
                                                Ubah Pembimbing Skripsi 1
                                            @else
                                                Tetapkan Pembimbing Skripsi 1
                                            @endif
                                        </button>
                                        @endif

                                        @if($mhs->status_bimbingan == 'skripsi')
                                            <button type="button" class="dropdown-item" 
                                            wire:click="bukaModalPembimbing2({{ $mhs->id }})">
                                            <i class="icon-base bx bx-user-check me-1"></i>
                                            
                                            @if($mhs->pembimbing_skripsi_2_id)
                                                Ubah Pembimbing Skripsi 2
                                            @else
                                                Tetapkan Pembimbing Skripsi 2
                                            @endif
                                        </button>
                                        @endif

                                        <button type="button" class="dropdown-item" 
                                        wire:click="bukaModalStatus({{ $mhs->id }})">
                                        <i class="icon-base bx bx-transfer-alt me-1"></i> Ubah Status Akun Dan Bimbingan
                                        </button>


                                            <button type="button" 
                                                    class="dropdown-item" 
                                                    wire:click="deleteMahasiswa({{ $mhs->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus data ini?">
                                                <i class="icon-base bx bx-trash me-1"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>

            @if($isModalPembimbing1Open)
            <div class="modal fade show" style="display: block;" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Manajemen Pembimbing Skripsi 1</h5>
                            <button type="button" class="btn-close" wire:click="closeModalPembimbing1"></button>
                        </div>
                        <form wire:submit.prevent="simpanPembimbing1">
                            <div class="modal-body">
                                <p><strong>Mahasiswa:</strong> {{ $namaMahasiswaTerpilih }}</p>
                                <p><strong>Dosen PA:</strong> {{ $namaPembimbingAkademik }}</p>
                                <hr>
                                <div class="mb-3">
                                    <label for="pembimbing1" class="form-label">Pembimbing Skripsi 1</label>
                                    <select wire:model="pembimbing1_id" id="pembimbing1" class="form-select @error('pembimbing1_id') is-invalid @enderror">
                                        <option value="">-- Kosongkan / Belum Ditetapkan --</option>
                                        @foreach($semuaDosen as $dosen)
                                            <option value="{{ $dosen->dosen->id }}">{{ $dosen->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pembimbing1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" wire:click="closeModalPembimbing1">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
            @endif

              @if($isModalPembimbing2Open)
            <div class="modal fade show" style="display: block;" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Manajemen Pembimbing Skripsi 2</h5>
                            <button type="button" class="btn-close" wire:click="closeModalPembimbing2"></button>
                        </div>
                        <form wire:submit.prevent="simpanPembimbing2">
                            <div class="modal-body">
                                <p><strong>Mahasiswa:</strong> {{ $namaMahasiswaTerpilih }}</p>
                                <p><strong>Dosen PA:</strong> {{ $namaPembimbingAkademik }}</p>
                                <hr>
                                <div class="mb-3">
                                    <label for="pembimbing1" class="form-label">Pembimbing Skripsi 1</label>
                                    <select wire:model="pembimbing1_id" id="pembimbing1" disabled class="form-select @error('pembimbing1_id') is-invalid @enderror">
                                        <option value="">-- Kosongkan / Belum Ditetapkan --</option>
                                        @foreach($semuaDosen as $dosen)
                                            <option value="{{ $dosen->dosen->id }}">{{ $dosen->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pembimbing1_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="pembimbing2" class="form-label">Pembimbing Skripsi 2</label>
                                    <select wire:model="pembimbing2_id" id="pembimbing2" class="form-select @error('pembimbing1_id') is-invalid @enderror">
                                        <option value="">-- Kosongkan / Belum Ditetapkan --</option>
                                        @foreach($semuaDosen as $dosen)
                                            <option value="{{ $dosen->dosen->id }}">{{ $dosen->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pembimbing2_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" wire:click="closeModalPembimbing2">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
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
                            <h5 class="modal-title">Ubah Status Mahasiswa</h5>
                            <button type="button" class="btn-close" wire:click="closeModalStatus"></button>
                        </div>
                        <form wire:submit.prevent="simpanStatus">
                            <div class="modal-body">
                                <p><strong>Mahasiswa:</strong> {{ $namaMahasiswaTerpilih }}</p>
                                <hr>
                                <div class="mb-3">
                                    <label for="status_akun" class="form-label">Status Akun</label>
                                    <select wire:model="status_akun" id="status_akun" class="form-select @error('status_akun') is-invalid @enderror">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonAktif">Non-Aktif</option>
                                    </select>
                                    @error('status_akun')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
    
                                <div class="mb-3">
                                    <label for="status_bimbingan" class="form-label">Status Bimbingan</label>
                                    <select wire:model="status_bimbingan" id="status_bimbingan" class="form-select @error('status_bimbingan') is-invalid @enderror">
                                        <option value="akademik">Akademik</option>
                                        <option value="proposal">Proposal</option>
                                        <option value="skripsi">Skripsi</option>
                                    </select>
                                    @error('status_bimbingan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
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
        <div class="card-body ">
         {{ $mahasiswa->links() }}
        </div>
    </div>
</div>


