@extends('layouts.app') {{-- Ganti dengan layout yang sesuai role --}}
@section('title', 'Profil Saya')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            {{-- Navigasi Tab (jika diperlukan) --}}
            <ul class="nav nav-pills flex-column flex-md-row mb-4">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);">
                        <i class="bx bx-user me-1"></i> Akun
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"> {{-- Arahkan ke tab ganti password --}}
                        <i class="bx bx-lock-alt me-1"></i> Ganti Password
                    </a>
                </li>
            </ul>

            {{-- Form untuk Informasi Profil --}}
            <div class="card mb-4">
                <h5 class="card-header">Detail Profil</h5>
                <div class="card-body">
                    {{-- Tampilkan pesan sukses/error --}}
                    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" />
                            </div>
                            
                            {{-- Field Spesifik untuk Mahasiswa --}}
                            @if($user->hasRole('mahasiswa') && $user->mahasiswa)
                                <div class="mb-3 col-md-6">
                                    <label for="nim" class="form-label">NIM</label>
                                    <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim', $user->mahasiswa->nim) }}" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="no_hp" class="form-label">No. HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->mahasiswa->no_hp) }}" />
                                </div>
                            @endif

                            {{-- Field Spesifik untuk Dosen --}}
                            @if($user->hasRole('dosen') && $user->dosen)
                                <div class="mb-3 col-md-6">
                                    <label for="nidn" class="form-label">NIDN/NIP</label>
                                    <input type="text" class="form-control" id="nidn" name="nidn" value="{{ old('nidn', $user->dosen->nidn) }}" />
                                </div>
                                {{-- ... field dosen lainnya ... --}}
                            @endif
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Form untuk Ganti Password --}}
            {{-- ... buat card dan form baru yang mengarah ke route profile.password.update ... --}}
        </div>
    </div>
</div>
@endsection