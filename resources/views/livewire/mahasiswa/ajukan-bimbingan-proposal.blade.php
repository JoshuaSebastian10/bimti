<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">...</div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    
    <form wire:submit.prevent="store" enctype="multipart/form-data">
        <div class="row">
      
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Mahasiswa</label>
                <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
            </div>

                        <div class="col-md-6 mb-3">
                <label class="form-label">Nim</label>
                <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->nim }}" disabled>
            </div>

            <div class="col mb-3">
                <label class="form-label">Dosen Pembimbing Skripsi 1</label>
                <input type="text" class="form-control" value="{{ $pembimbing_skripsi_1_id->user->name }}" disabled>
            </div>

            <input wire:model="dosen_id" type="text" value="{{ $pembimbing_skripsi_1_id->id }}" hidden>
            
        </div>

     
        <div class="mb-3">
            <label for="topik" class="form-label">Topik Bimbingan<span class="text-danger">*</span></label>
            <textarea wire:model="topik" class="form-control @error('topik') is-invalid @enderror" id="topik" rows="3" placeholder="Contoh: Diskusi Rencana Studi Semester Depan"></textarea>
            @error('topik') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        
       
        <div class="mb-3">
            <label for="jadwal" class="form-label">Pilih Jadwal<span class="text-danger">*</span></label>
            <select wire:model="jadwal" class="form-select @error('jadwal') is-invalid @enderror" required>
                <option value="">-- Pilih jadwal yang tersedia --</option>
            
                 @forelse ($opsiDropdown as $opsi)
                          
                            @if ($opsi['sudah_dipesan'])
                                <option value="{{ $opsi['value'] }}" disabled>
                                    {{ $opsi['teks'] }} [SUDAH ANDA PESAN]
                                </option>
                            @elseif ($opsi['is_full'])
                                <option value="{{ $opsi['value'] }}" disabled>
                                    {{ $opsi['teks'] }} [PENUH]
                                </option>
                            @else
                                <option value="{{ $opsi['value'] }}" {{ old('jadwal') == $opsi['value'] ? 'selected' : '' }}>
                                    {{ $opsi['teks'] }}
                                </option>
                            @endif
                        @empty
                            <option value="" disabled>Saat ini tidak ada jadwal bimbingan yang tersedia.</option>
                        @endforelse
            </select>
            @error('jadwal') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

   
        <div class="mb-3">
            <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
            <input wire:model="lampiran" type="file" class="form-control @error('lampiran') is-invalid @enderror" id="lampiran">
            <div wire:loading wire:target="lampiran">Mengunggah...</div>
            @error('lampiran') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <span wire:loading wire:target="store">Mengirim...</span>
                <span wire:loading.remove wire:target="store">Ajukan Jadwal</span>
            </button>
        </div>
    </form>
</div>



