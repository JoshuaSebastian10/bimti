@extends('layouts.dosen.app')
@section('title', 'Dashboard Dosen')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

         <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                  <div class="card">
                    <div class="row">
                      <div class="col-sm-10">
                        <div class="card-body">
                         <h5 class="card-title text-primary mb-3">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h5>
                            <p class="mb-6">
                                @if($jumlahAjuanBaru > 0)
                                    Anda memiliki <strong>{{ $jumlahAjuanBaru }} ajuan bimbingan baru</strong> yang menunggu persetujuan Anda. Segera tinjau untuk membantu progres mahasiswa.
                                @else
                                    Tidak ada ajuan bimbingan baru saat ini.
                                @endif
                            </p>
                            <a href="{{ route('dosen.daftarBimbingan')}}" class="btn btn-sm btn-primary">Tinjau Ajuan Sekarang</a>
                        </div>
                      </div>
    
                    </div>
                  </div>
                </div>
            </div>





</div>
@endsection