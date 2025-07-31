@extends('layouts.admin.app')
@section('title', 'Import Data Mahasiswa')

@push('styles')
    {{-- CSS custom Anda di sini --}}
    <style>
        #uploadArea {
            border: 2px dashed #ccc;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        #uploadArea.drag-over {
            background-color: #f0f8ff;
            border-color: #0d6efd;
        }
        #fileInfo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .file-icon { font-size: 2rem; }
    </style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Data Pengguna /</span> Import Mahasiswa
    </h4>
    
    {{-- AREA UNTUK MENAMPILKAN NOTIFIKASI HASIL IMPORT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    @if (session('warning_summary'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <h5 class="alert-heading">Informasi Impor</h5>
            <p>{{ session('warning_summary') }}</p>
            @if(session('import_warnings'))
                <hr>
                <a class="btn btn-sm btn-dark" data-bs-toggle="collapse" href="#detailDuplikat" role="button">
                    Lihat Detail Baris yang Dilewati
                </a>
                <div class="collapse mt-3" id="detailDuplikat">
                    <ul class="list-group">
                        @foreach(session('import_warnings') as $warning)
                            <li class="list-group-item">{{ $warning }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <h5 class="alert-heading">Import Gagal!</h5>
            <p>{{ session('error') }}</p>
            @if(session('import_errors'))
                <hr>
                <p class="mb-0">Detail masalah:</p>
                <ul class="mb-0 ps-4">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- AKHIR AREA NOTIFIKASI --}}


    <form id="importForm" action="{{ route('admin.mahasiswa.import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Unggah File</h5>
            </div>
            <div class="card-body">
                <div class="row g-4 mb-4">
                    {{-- Kolom Kiri: Petunjuk --}}
                    <div class="col-md-7">
                        <h6 class="fw-bold"><i class="tf-icons bx bxs-info-circle text-primary me-2"></i>Petunjuk Penting</h6>
                        <ul class="list-unstyled text-muted ps-1">
                            <li class="mb-2"><i class="tf-icons bx bx-check text-success me-2"></i>Pastikan file dalam format <strong>.XLSX</strong> atau <strong>.CSV</strong>.</li>
                            <li class="mb-2"><i class="tf-icons bx bx-check text-success me-2"></i>Ukuran file maksimal <strong>5 MB</strong>.</li>
                            <li class="mb-2"><i class="tf-icons bx bx-check text-success me-2"></i>Baris pertama (header) harus berisi: <strong>nim, nama_mahasiswa, email, dosen_pa</strong>.</li>
                            <li class="mb-2"><i class="tf-icons bx bx-check text-success me-2"></i>Pastikan tidak ada nim atau Email yang duplikat di dalam file.</li>
                        </ul>
                    </div>
                    {{-- Kolom Kanan: Template --}}
                    <div class="col-md-5">
                        <h6 class="fw-bold"><i class="tf-icons bx bx-download text-success me-2"></i>Unduh Template</h6>
                        <p class="text-muted">Gunakan template ini untuk memastikan format data Anda sudah benar dan menghindari error.</p>
                        {{-- Ganti '#' dengan route untuk download template --}}
                        <a href="" class="btn btn-outline-success w-100">
                            <i class="tf-icons bx bxs-file-spreadsheet me-2"></i>Unduh Template Excel
                        </a>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text">Area Unggah</div>
                </div>

                {{-- Area Upload --}}
                <div id="uploadContainer" class="mt-3">
                    <div id="uploadArea">
                        <i class="tf-icons bx bxs-cloud-upload upload-icon" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 mb-1 fw-bold">Seret & Lepas File Di Sini</h5>
                        <p class="text-muted mb-0">atau klik untuk memilih file</p>
                    </div>
                    <input type="file" id="fileInput" name="file_mahasiswa" class="d-none" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    <div id="fileInfo" class="d-none mt-4">
                        <div class="d-flex align-items-center">
                            <i class="tf-icons bx bxs-file-doc file-icon text-primary"></i>
                            <span id="fileName" class="fw-bold ms-2"></span>
                        </div>
                        <button id="removeFileBtn" type="button" class="btn-close" aria-label="Hapus file"></button>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" id="importBtn" class="btn btn-primary" disabled>
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                    <i class="tf-icons bx bx-import me-2" id="importIcon"></i>
                    <span id="btnText">Import Sekarang</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>

document.addEventListener("DOMContentLoaded", function () {
    // Ambil semua elemen yang diperlukan dari DOM
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const btnSpinner = importBtn.querySelector('.spinner-border');
    const btnText = importBtn.querySelector('.text'); 
    const uploadArea = document.getElementById("uploadArea");
    const fileInput = document.getElementById("fileInput");
    const fileInfo = document.getElementById("fileInfo");
    const fileName = document.getElementById("fileName");
    const removeFileBtn = document.getElementById("removeFileBtn");


    function resetAll() {
        fileInput.value = "";
        fileInfo.classList.add("d-none");
        uploadArea.classList.remove("d-none");
        importBtn.disabled = true;
    }

    function handleFile(file) {
        if (file) {
            if (file.size > 5 * 1024 * 1024) { 
                alert('Ukuran file tidak boleh lebih dari 5MB'); 
                fileInput.value = "";
                return;
            }
            uploadArea.classList.add("d-none");
            fileInfo.classList.remove("d-none");
            fileName.textContent = file.name;
            importBtn.disabled = false;
        }
    }

    if (uploadArea) {
        uploadArea.addEventListener("click", () => fileInput.click());

        fileInput.addEventListener("change", () => {
            handleFile(fileInput.files[0]);
        });

        uploadArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            uploadArea.classList.add("drag-over");
        });

        uploadArea.addEventListener("dragleave", () => {
            uploadArea.classList.remove("drag-over");
        });

        uploadArea.addEventListener("drop", (event) => {
            event.preventDefault();
            uploadArea.classList.remove("drag-over");
            const file = event.dataTransfer.files[0];
            handleFile(file);
        });

        removeFileBtn.addEventListener("click", () => {
            resetAll();
        });
    }


    if (importForm) {
        importForm.addEventListener('submit', function() {
         
            if (!fileInput.files.length) {
                event.preventDefault(); 
                alert('Silakan pilih file terlebih dahulu.');
                return;
            }
 
            importBtn.disabled = true;
            btnSpinner.classList.remove('d-none');
            btnText.textContent = 'Memproses...';
        });
    }
});
</script>
@endpush