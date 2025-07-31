@extends('layouts.dosen.app')
@section('title', 'Jadwal Bimbingan')

@push('styles')
<link rel="stylesheet" href="{{ asset('styleCssCostume/table.css') }}">

@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @livewire('dosen.jadwal-bimbingan')
</div>
@endsection