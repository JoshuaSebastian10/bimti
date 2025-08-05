@extends('layouts.dosen.app')
@section('title', 'Detail Progres Mahasiswa')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <a href="" class="btn btn-label-secondary mb-3">
        <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
    </a>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
  
                <img src="{{ optional($mahasiswa->user)->profile_photo_url ?? asset('img/logo.png') }}" alt="user-avatar" class="d-block rounded me-5" height="100" width="100" id="uploadedAvatar" />
                
                <div class="button-wrapper">
                    <h4 class="mb-1">{{ $mahasiswa->user->name }}</h4>
                    <p class="text-muted mb-1">{{ $mahasiswa->nim }}</p>
                    <p class="text-muted mb-2">Angkatan: {{ $mahasiswa->angkatan ?? 'N/A' }}</p>
                </div>
            </div>
            
        
            @if($mahasiswa->status_bimbingan == 'skripsi')
                <hr>
                <div class="row text-center">
                    <div class="mb-3 col-12 text-center">
                        <h3 class="card-title"><strong>Informasi Skripsi</strong></h3>
                        <h4><strong>Judul:</strong> {{ $mahasiswa->judul_skripsi ?? 'Belum ada judul.' }}</h4>
                    </div>
                    <div class="mb-3 col-md-6">
                      <strong>Pembimbing 1:</strong>
          
                      <p>{{ optional($mahasiswa->pembimbingSkripsi1)->user->name ?? 'Belum Ditetapkan' }}</p>
                  </div>
                  <div class="mb-3 col-md-6">
                      <strong>Pembimbing 2:</strong>
             
                      <p>{{ optional($mahasiswa->pembimbingSkripsi2)->user->name ?? 'Belum Ditetapkan' }}</p>
                  </div>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0"><strong>Statistik & Riwayat Bimbingan dengan Anda</strong></h5>
        </div>
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Bimbingan Akademik</h6>
                    <p>Total Ajuan: {{ $stats['akademik']['total'] }}</p>
                    <p>Selesai: {{ $stats['akademik']['selesai'] }}</p>
                    <p>Ditolak/Batal: {{ $stats['akademik']['ditolak'] }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Bimbingan Skripsi</h6>
                    <p>Total Ajuan: {{ $stats['skripsi']['total'] }}</p>
                    <p>Selesai: {{ $stats['skripsi']['selesai'] }}</p>
                    <p>Ditolak/Batal: {{ $stats['skripsi']['ditolak'] }}</p>
                </div>
            </div>

    
            <h6><strong>Riwayat Lengkap</strong></h6>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Bimbingan</th>
                            <th>Jenis</th>
                            <th>Topik</th>
                            <th>Status</th>
                            <th>Lampiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($semuaBimbingan as $bimbingan)
                            <tr>
                                <td>{{ $bimbingan->TanggalBimbinganFormat }}</td>
                                <td>{{ ucfirst($bimbingan->jenis_bimbingan) }}</td>
                                <td>{{ Str::limit($bimbingan->topik, 50) }}</td>
                                <td>
                                    @php
                                        $statusClass = ['menunggu'=>'warning','disetujui'=>'success','ditolak'=>'danger','selesai'=>'info','dibatalkan'=>'secondary'][$bimbingan->status] ?? 'dark';
                                    @endphp
                                    <span class="badge bg-label-{{$statusClass}}">{{ ucfirst($bimbingan->status) }}</span>
                                </td>
                                <td>
                                    @if($bimbingan->lampiran_path)
                                        <a href="" target="_blank">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Belum ada riwayat bimbingan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection