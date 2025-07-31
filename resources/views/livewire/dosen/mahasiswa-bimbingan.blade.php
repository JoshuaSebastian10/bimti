<div>
    <div class="card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="card-header">Progres Mahasiswa Bimbingan</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end p-3">
           
                    <div class="input-group input-group-merge" style="max-width: 300px;">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                      
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control" placeholder="Cari Nama atau NIM...">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
          
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Peran Anda</th>
                        <th>Total Bimbingan</th>
                        <th>Bimbingan Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($mahasiswas as $index => $value)
                        <tr>
                            <td class="text-4">{{ $mahasiswas->firstItem() + $index }}</td>
                            <td>{{ optional($value->user)->name }}</td>
                            <td>{{ $value->nim }}</td>
                            <td>
                                @if($value->dosen_pa_id == $dosenId)
                                    <span class="badge bg-label-secondary d-block mb-1">Pembimbing Akademik</span>
                                @endif
                                @if($value->pembimbing_skripsi_1_id == $dosenId)
                                    <span class="badge bg-label-primary d-block mb-1">Pembimbing Skripsi 1</span>
                                @endif
                                @if($value->pembimbing_skripsi_2_id == $dosenId)
                                    <span class="badge bg-label-info d-block">Pembimbing Skripsi 2</span>
                                @endif
                            </td>
                            <td><span class="badge bg-dark">{{ $value->total_ajuan_count }} Ajuan</span></td>
                            <td><span class="badge bg-success">{{ $value->bimbingan_selesai_count }} Selesai</span></td>

                            <td><a href="{{ route('dosen.mahasiswaBimbingan.detail', $value->id) }}" class="btn btn-sm btn-outline-secondary">Lihat Detail ➔</a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada mahasiswa bimbingan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body">
     
            {{ $mahasiswas->links() }}
        </div>
    </div>
</div>