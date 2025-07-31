@extends('layouts.mahasiswa.app')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    </script>
    @endif


             <div class="row">
                <div class="col-xxl-12 mb-6 order-0">
                  <div class="card">
                    <div class="row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary mb-3">Selamat Datang Kembali, {{ Auth::user()->name }}! 👋</h5>
                          <p class="mb-6">
                          @if($jadwalTerdekat)
                                Jangan lupa, jadwal bimbingan Anda berikutnya adalah pada hari 
                                <strong>{{ $jadwalTerdekat->tanggalBImbinganFormat }}</strong>
                                pukul <strong>{{ $jadwalTerdekat->jamMulaiFormat }}</strong>.
                            @else
                                Saat ini belum ada jadwal bimbingan yang akan datang. Tetap produktif dan jangan ragu untuk mengajukan sesi baru.
                            @endif
                          </p>

                          <a href="{{ route('mahasiswa.bimbinganSaya') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Jadwal Saya</a>
                        </div>
                      </div>
    
                    </div>
                  </div>
                </div>
            </div>
</div>
@endsection