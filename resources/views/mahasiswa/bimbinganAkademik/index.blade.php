@extends('layouts.mahasiswa.app')
@section('title', 'Ajukan Bimbingan Akademik')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Formulir Ajuan Bimbingan Akademik</h5>
        </div>
        <div class="card-body">
            @livewire('mahasiswa.ajukan-bimbingan-akademik')
        </div>
    </div>
</div>
@endsection