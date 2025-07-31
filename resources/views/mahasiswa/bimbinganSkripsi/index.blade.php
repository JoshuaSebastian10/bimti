@extends('layouts.mahasiswa.app')
@section('title', 'Ajukan Bimbingan Skripsi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Formulir Ajuan Bimbingan Skripsi</h5>
        </div>
        <div class="card-body">
            {{-- Panggil Komponen Livewire dan kirim data dosen awal --}}
            @livewire('mahasiswa.ajukan-bimbingan-skripsi')
        </div>
    </div>
</div>
@endsection