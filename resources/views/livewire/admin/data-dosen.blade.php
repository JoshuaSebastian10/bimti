<div>
    @if (session()->has('successdelete'))
<div class="alert alert-warning alert-dismissible" role="alert">
    {{ session('successdelete') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session()->has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
    <div class="card">

        <div class="row align-items-center">
            <div class="col-auto me-auto">
                <h5 class="card-header">Data Dosen</h5>
            </div>
            <div class="col-10 mb-5 ms-5 col-sm-6 col-md-4 col-lg-4  my-sm-0 me-sm-5">
                
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari nama atau nip">
                    </div>
            </div>
        </div>
    
   
        <div class="table-responsive text-nowrap">

            <table class="table table-responsive-sm-text">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Status Akun</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($dosen as $index => $value)
                        <tr>
                            {{-- Nomor urut yang benar untuk pagination --}}
                            <td class="text-4">{{ $dosen->firstItem() + $index }}</td>
                            {{-- Gunakan optional() untuk keamanan jika relasi kosong --}}
                            <td>{{ optional($value->user)->name }}</td>
                            <td>{{ $value->nip }}</td>
                            {{-- Karena dosenPa() merujuk ke User, langsung ambil name --}}
                            <td><span class="badge bg-label-primary me-1">{{ ucfirst($value->status_akun) }}</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.dataDosen.edit', $value->id) }}">
                                            <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                        </a>

                                    <button type="button" class="dropdown-item" 
                                            wire:click="bukaModalStatus({{ $value->id }})">
                                            <i class="icon-base bx bx-transfer-alt me-1"></i> Ubah Status Akun
                                    </button>


                                    <button type="button" 
                                            class="dropdown-item" 
                                            wire:click="deleteDosen({{ $value->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus data ini?">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                    </button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data dosen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($isModalStatusOpen)
            <div class="modal fade show" style="display: block;" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Akun Dosen</h5>
                            <button type="button" class="btn-close" wire:click="closeModalStatus"></button>
                        </div>
                        <form wire:submit.prevent="simpanStatus">
                            <div class="modal-body">
                                <p><strong>Dosen:</strong> {{ $namaDosenTerpilih }}</p>
                                <hr>
                                <div class="mb-3">
                                    <label for="status_akun" class="form-label">Status Akun</label>
                                    <select wire:model="status_akun" id="status_akun" class="form-select @error('status_akun') is-invalid @enderror">
                                        <option value="aktif">Aktif</option>
                                        <option value="nonAktif">Non-Aktif</option>
                                    </select>
                                    @error('status_akun')<div class="invalid-feedback">{{ $message }}</div>@enderror
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



        <div class="card-body ">
         {{ $dosen->links() }}
        </div>
    </div>
</div>


