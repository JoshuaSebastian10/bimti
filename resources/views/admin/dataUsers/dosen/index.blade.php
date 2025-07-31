@extends('layouts.admin.app')
@section('title', 'Data Dosen')

@push('styles')
<link rel="stylesheet" href="{{ asset('styleCssCostume/table.css') }}">
@endpush


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row align-items-center justify-content-end g-2 mb-5">
        <div class="col-auto">
            <a href="{{ route('admin.dataMahasiswa.index') }}" 
            class="btn btn-costume-responsive d-flex align-items-center {{ request()->routeIs('admin.dataMahasiswa.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                <span class=d-sm-inline ">Mahasiswa</span>
            </a>
        </div>

        {{-- Tombol Dosen --}}
        <div class="col-auto">
            <a href="{{ route('admin.dataDosen.index') }}"
            class="btn btn-costume-responsive  d-flex align-items-center {{ request()->routeIs('admin.dataDosen.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                <span class=d-sm-inline">Dosen</span>
            </a>
        </div>

        {{-- Tombol Admin --}}
        <div class="col-auto me-auto">
            <a href="{{ route('admin.dataAdmin.index') }}"
            class="btn btn-costume-responsive align-items-center {{ request()->routeIs('admin.dataAdmin.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                <span class=d-sm-inline">Admin</span>
            </a>
        </div>

        <!-- Button Column -->
        <div class="col-auto text-end">
            <a href="{{ route('admin.dataDosen.create') }}"
                class="btn btn-primary d-flex align-items-center"
                style="min-width: 42px; justify-content: center;">
                <i class="bx bx-plus d-flex d-sm-inline-flex"></i>
                <span class="d-none d-sm-inline ms-2">Tambah Dosen</span>
            </a>
        </div>

    </div>

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif




    @livewire('admin.data-dosen')
</div>
@endsection