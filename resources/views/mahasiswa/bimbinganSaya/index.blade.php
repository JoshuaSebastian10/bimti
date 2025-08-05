@extends('layouts.mahasiswa.app')
@section('title', 'Bimbingan Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('styleCssCostume/costume.css') }}">
@endpush


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   
    @livewire('mahasiswa.bimbingan-saya')
</div>
@endsection