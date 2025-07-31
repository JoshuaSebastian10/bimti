@extends('layouts.dosen.app')

@section('title', 'Edit Jadwal Bimbingan')

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
                    <a href="">Jadwal Bimbingan</a>
                    <i class="breadcrumb-icon icon-base bx bx-chevron-right align-middle"></i>
                </li>

                <li class="breadcrumb-item active" aria-current="page">Edit Jadwal</li>
            </ol>
        </nav>
        <!-- /Breadcrumb -->

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">
                <span class="text-muted">Edit Jadwal Bimbingan</span>
            </h4>
            <a href="{{ route('dosen.jadwalBimbingan') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Jadwal Bimbingan</h5>
            </div>
            <div class="card-body">

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('dosen.jadwalBimbingan.update', $jadwalBimbingan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">

                        <div class="col-md-3 col-sm-6 mb-3">
                            <label for="hari" class="col-form-label required">Hari<span class="text-danger">*</span></label>
                            <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" id="hari" required>
                                <option value="" class="text-light fw-medium">Pilih Hari</option>
                                <option value="Senin" {{ old('hari', $jadwalBimbingan->hari) == 'Senin' ? 'selected' : '' }}>Senin</option>
                                <option value="Selasa" {{ old('hari', $jadwalBimbingan->hari) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                <option value="Rabu" {{ old('hari', $jadwalBimbingan->hari) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                <option value="Kamis" {{ old('hari', $jadwalBimbingan->hari) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                <option value="Jumat" {{ old('hari', $jadwalBimbingan->hari) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            </select>
                            @error('hari')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label for="jam_mulai" class="col-form-label required">Jam Mulai<span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwalBimbingan->jam_mulai) }}" class="form-control" id="jam_mulai" required>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label for="jam_selesai" class="col-form-label required">Jam Selesai<span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwalBimbingan->jam_selesai) }}" class="form-control" id="jam_selesai" required>
                        @error('jam_selesai')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label for="kouta" class="col-form-label required">Kouta Bimbingan<span class="text-danger">*</span></label>
                            <input type="number" name="kuota" value="{{ old('kuota', $jadwalBimbingan->kuota) }}" min="1"  max="100" class="form-control" id="kouta" required>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-12 text-end mt-3">

                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Update Data
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

@endsection


