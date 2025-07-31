@extends('layouts.admin.app')
@section('title', 'Edit Data Mahasiswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Formulir Edit Mahasiswa</h5>
            <a href="{{ route('admin.dataMahasiswa.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
          
            <form action="{{ route('admin.dataMahasiswa.update',$mahasiswa->id) }}" method="POST">
                @csrf
                @method('PUT') 
                <div class="row">
                

                    <div class="col-md-6 mb-3">
                        <label for="nim" class="form-label required">NIM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim"
                            name="nim" value="{{ old('nim', $mahasiswa->nim) }}" placeholder="Contoh: 123456789" required>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">NIM akan digunakan sebagai username</small>
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label required">Nama Lengkap <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $mahasiswa->user->name) }}" placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label required">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $mahasiswa->user->email) }}" placeholder="Contoh: mahasiswa@unima.ac.id"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    
                    <div class="col-md-6 mb-3">
                        <label for="dosen_pa_id" class="form-label required">Dosen Pembimbing Akademik <span
                            class="text-danger">*</span></label>
                        <select class="form-select @error('dosen_pa_id') is-invalid @enderror" id="dosen_pa_id"
                            name="dosen_pa_id" required>
                            <option value="" class="text-light fw-medium">Pilih Dosen PA</option>
                            @foreach ($dosens as $value)
                            <option value="{{ $value->dosen->id }}" {{ old('dosen_pa_id', $mahasiswa->dosen_pa_id) == $value->dosen->id ? 'selected' : '' }}>{{ $value->name }}</option>
                            @endforeach
                        </select>
                        @error('dosen_pa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status_akun" class="form-label required">Status Akun <span class="text-danger">*</span></label>
                        <select class="form-select" id="status_akun" name="status_akun" required>
                            <option value="">Pilih Status Akun</option>
                            <option value="aktif" {{ old('status_akun', $mahasiswa->status_akun) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonAktif" {{ old('status_akun', $mahasiswa->status_akun) == 'nonAktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status_bimbingan" class="form-label required">Status Bimbingan <span
                            class="text-danger">*</span></label>
                        <select class="form-select" id="status_bimbingan"
                            name="status_bimbingan" required>
                            <option value="" class="text-light fw-medium">Pilih Status Akun</option>
                            <option value="akademik" {{ old('status_bimbingan', $mahasiswa->status_bimbingan) == 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="proposal" {{ old('status_bimbingan', $mahasiswa->status_bimbingan) == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="skripsi" {{ old('status_bimbingan', $mahasiswa->status_bimbingan) == 'skripsi' ? 'selected' : '' }}>Skripsi</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <span class="input-group-text cursor-pointer toggle-password">
                                <i class="bx bx-hide"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Data</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bx-hide', 'bx-show');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bx-show', 'bx-hide');
                    }
                });
            });
        });
    </script>
@endpush

