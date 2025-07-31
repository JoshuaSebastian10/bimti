@extends('layouts.admin.app')
@section('title', 'Edit Data Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Formulir Edit Admin</h5>
            <a href="{{ route('admin.dataMahasiswa.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>
        <div class="card-body">
          
            <form action="{{ route('admin.dataAdmin.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT') 
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label required">Nama Lengkap <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('nip', $admin->name) }}" placeholder="Masukkan nama lengkap" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label required">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $admin->email) }}" placeholder="Contoh: mahasiswa@unima.ac.id"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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

