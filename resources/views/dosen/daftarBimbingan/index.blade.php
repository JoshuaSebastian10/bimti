@extends('layouts.dosen.app')
@section('title', 'Daftar Binbingan')

@push('styles')
<link rel="stylesheet" href="{{ asset('styleCssCostume/table.css') }}">
@endpush


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   
    @livewire('dosen.daftar-bimbingan')
</div>
@endsection