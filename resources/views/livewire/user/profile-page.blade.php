
<div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">

                <div class="col-12 col-md-3 text-center">
                    <img src="{{ $user->profil_path ? asset('storage/' . $user->profil_path) : asset('img/default_photo.png') }}"
                         alt="user-avatar" class="d-block rounded-circle mb-3 mx-auto "  height="200" width="200" />
                    
                   <div class="">
                    <button class="btn btn-dark btn-sm mb-2" wire:click="openPhotoModal">
                        <i class="bx bx-camera me-1"></i> Ganti Foto
                    </button>
                   </div>
                   <div class="">
                    <button class="btn btn-primary btn-sm mb-2" wire:click="edit">
                        <i class="bx bx-edit-alt me-1"></i> Edit Profil
                    </button>
                   </div>
                   <div class="">
                    <button class="btn btn-secondary btn-sm mb-2">
                        <i class="bx bx-lock-alt me-1"></i> Ganti Password
                    </button>
                   </div>
                    
                </div>

            <span class="d-block d-md-none"><hr></span>
            
                <div class="col-12 col-md-7">
                    <h4 class="mb-0">{{ $user->name }}</h4>
                    <small class="text-muted">
                        @if($mahasiswa) <span class="badge bg-label-primary">Mahasiswa</span>   @elseif($dosen) <span class="badge bg-label-primary">Dosen</span> @else  <span class="badge bg-label-primary">Admin</span>@endif
                    </small>
                    
                    <ul class="list-unstyled mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-id-card"></i><span class="fw-medium mx-2">@if($mahasiswa) NIM: @elseif($dosen) NIP:  @elseif($dosen) ID:  @endif</span> 
                            <span>{{ $mahasiswa->nim ?? $dosen->nip  ?? Auth::user()->id ?? 'N/A' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-envelope"></i><span class="fw-medium mx-2">Email:</span> 
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="bx bx-check-shield"></i><span class="fw-medium mx-2">Status Akun:</span> 
                            <span class="badge bg-label-success">{{ ucfirst($mahasiswa->status_akun ?? $dosen->status_akun ?? 'Aktif') }}</span>
                        </li>
                    </ul>

                    {{-- Informasi Khusus Skripsi --}}
                    @if($mahasiswa && $mahasiswa->status_bimbingan == 'skripsi')
                        <h5 class="mt-4">Informasi Skripsi</h5>
                        <hr>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bxs-user-detail"></i><span class="fw-medium mx-2">Pembimbing 1:</span> 
                                <span>{{ optional($mahasiswa->pembimbingSkripsi1->user)->name ?? 'Belum Ditetapkan' }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bxs-user-detail"></i><span class="fw-medium mx-2">Pembimbing 2:</span> 
                                <span>{{ optional($mahasiswa->pembimbingSkripsi2->user)->name ?? 'Belum Ditetapkan' }}</span>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($isEditModalOpen)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Profil</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="update">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name_edit" class="form-label">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="form-control" id="name_edit">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                             <div class="mb-3">
                                <label for="email_edit" class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control" id="email_edit">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if($isPhotoModalOpen)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ganti Foto Profil</h5>
                        <button type="button" class="btn-close" wire:click="closePhotoModal"></button>
                    </div>
                    <form wire:submit.prevent="updatePhoto">
                        <div class="modal-body">
                            {{-- Area Pratinjau Gambar --}}
                            @if ($photo)
                                <p>Foto Yang Di Ubah:</p>
                                <img src="{{ $photo->temporaryUrl() }}" class="d-block rounded-circle mb-3 mx-auto" height="200" width="200" >
                            @else
                                <p>Foto Saat Ini:</p>
                                <img src="{{ $user->profil_path ? asset('storage/ptofi' . $user->profil_path) : asset('img/logo.png') }}" class="d-block rounded-circle mb-3 mx-auto" height="200" width="200" >

                            @endif

                            {{-- Input File --}}
                            <div class="form-group">
                                <label for="photo_upload" class="form-label">Pilih File Gambar</label>
                                <input type="file" wire:model="photo" id="photo_upload" class="form-control @error('photo') is-invalid @enderror">
                                <div wire:loading wire:target="photo" class="mt-2 text-muted">Mengunggah...</div>
                                @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <small class="text-muted">Diizinkan JPG atau PNG. Ukuran maksimal 2MB.</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" wire:click="closePhotoModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Foto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>