@extends('layouts.admin.app')

@section('title', 'Tambah Admin')

@push('styles')
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom-icon">
                <li class="breadcrumb-item">
                    <a href="">Dashboard</a>
                    <i class="breadcrumb-icon icon-base bx bx-chevron-right align-middle"></i>
                </li>
                <li class="breadcrumb-item">
                    <a href="">Data User</a>
                    <i class="breadcrumb-icon icon-base bx bx-chevron-right align-middle"></i>
                </li>
                <li class="breadcrumb-item">
                    <a href="">Admin</a>
                    <i class="breadcrumb-icon icon-base bx bx-chevron-right align-middle"></i>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Data Adminw</li>
            </ol>
        </nav>
        <!-- /Breadcrumb -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted">Tambah Layanan Baru</span>
            </h4>
            <a href="{{ route('admin.dataDosen.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Tambah Admin</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">Ada Kesalahan!</h4>
        <p>Harap perbaiki error di bawah ini sebelum menyimpan data:</p>
        <hr>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form action="{{ route('admin.dataAdmin.store') }}" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label required">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label required">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" placeholder="Contoh: mahasiswa@unima.ac.id"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label required">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Minimal 8 karakter" required>
                                <span class="input-group-text cursor-pointer toggle-password">
                                    <i class="bx bx-hide"></i>
                                </span>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Gunakan kombinasi huruf, angka, dan simbol</small>
                        </div>
           

                    <div class="row">
                        <div class="col-12 text-end mt-3">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="bx bx-reset"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Simpan Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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

