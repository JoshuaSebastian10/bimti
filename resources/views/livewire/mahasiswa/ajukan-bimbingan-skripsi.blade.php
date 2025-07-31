<div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">{{ session('success') }} ... </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">{{ session('error') }} ... </div>
    @endif


    <form wire:submit.prevent="store">
        <div class="row">
        
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Mahasiswa</label>
                <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">NIM</label>
                <input type="text" class="form-control" value="{{ Auth::user()->mahasiswa->nim }}" disabled>
            </div>

     
            <div class="col-md-12 mb-3">
                <label for="dosen_id" class="form-label required">Dosen Pembimbing Skripsi</label> <span class="text-danger">*</span></label>
                <select wire:model.live="dosen_id" id="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Pembimbing Anda --</option>
                 
                    @foreach ($pembimbingTersedia as $dosen)
        
                        <option value="{{ $dosen->id }}">{{ $dosen->user->name }}</option>
                    @endforeach
                </select>
                @error('dosen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        

        {{-- <div class="col-md-6 mb-3">
            <label class="form-label">Judul Skripsi (Opsional)</label>
            <input wire:model="judul" type="text" class="form-control" name="judul" disabled>
        </div> --}}

        <div class="mb-3">
            <label for="topik" class="form-label">Topik Bimbingan <span class="text-danger">*</span></label>
            <textarea wire:model="topik" class="form-control @error('topik') is-invalid @enderror" id="topik" name="topik" rows="3" placeholder="Contoh: Diskusi Rencana Studi Semester Depan"></textarea>
            @error('topik')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jadwal" class="form-label">Pilih Jadwal Bimbingan <span class="text-danger">*</span></label>

            <select wire:model="jadwal" id="jadwal" class="form-select @error('jadwal') is-invalid @enderror" @if(empty($dosen_id)) disabled @endif required>
                
                @if(empty($dosen_id))
                    <option value="">-- Pilih Dosen terlebih dahulu --</option>
                @else
                    <option value="">-- Pilih jadwal yang tersedia --</option>
                    @forelse ($opsiDropdown as $opsi)
                        @if ($opsi['is_full'] || $opsi['sudah_dipesan'])
                            <option value="{{ $opsi['value'] }}" disabled>{{ $opsi['teks'] }} {{ $opsi['is_full'] ? '[PENUH]' : '[DIPESAN]' }}</option>
                        @else
                            <option value="{{ $opsi['value'] }}">{{ $opsi['teks'] }}</option>
                        @endif
                    @empty
  
                        <option value="" disabled>Dosen ini tidak memiliki jadwal tersedia.</option>
                    @endforelse
                @endif
            </select>
            

            <div wire:loading wire:target="dosen_id">
                <small class="text-muted fst-italic">Mencari jadwal...</small>
            </div>
        
        <div class="mb-3">
            <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
            <input wire:model="lampiran" class="form-control @error('lampiran') is-invalid @enderror" type="file" id="lampiran" name="lampiran">
            @error('lampiran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Jenis file: JPG, PNG, PDF, DOCX, XLSX. Maksimal 5MB.</small>
        </div>
    </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Ajukan Jadwal</button>
        </div>
    </form>
</div>