@extends('layouts.dosen.app')
@section('title', 'Mahasiswa Bimbingan')

@push('styles')
<link rel="stylesheet" href="{{ asset('styleCssCostume/table.css') }}">
@endpush


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   
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
    @livewire('dosen.mahasiswa-bimbingan')
</div>
@endsection