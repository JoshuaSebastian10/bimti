@extends('layouts.admin.app')
@section('title', 'Dashboard Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

     <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                  <div class="card">
                    <div class="row">
                      <div class="col-sm-10">
                        <div class="card-body">
                         <h5 class="card-title text-primary mb-3">Selamat Datang di Halaman Admin! 👋</h5>
                            <p class="mb-6">
                                Saat ini sistem mengelola <strong>{{ $totalMahasiswa }}</strong> mahasiswa aktif dan <strong>{{ $totalDosen }}</strong> dosen.
                                Total <strong>{{ $totalBimbingan }}</strong> sesi bimbingan telah tercatat hingga saat ini.
                            </p>
                                <a href="{{ route('admin.dataMahasiswa.index')}}" class="btn btn-sm btn-outline-primary">Kelola Data User</a>
                        </div>
                      </div>
    
                    </div>
                  </div>
                </div>
            </div>

</div>
@endsection