<?php

namespace App\Http\Controllers\Imports;

use App\Imports\MahasiswaImport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

class DataMahasiswaImport extends Controller
{
    public function create()
    {
        return view('admin.importdatamahasiswa.index');
    }

   public function store(Request $request)
{
    set_time_limit(600);
    
    $request->validate([
        'file_mahasiswa' => 'required|file|mimes:xls,xlsx,csv',
    ]);

    $file = $request->file('file_mahasiswa');

    try {
        $headerRow = (new HeadingRowImport)->toArray($file)[0][0] ?? null;
        
        if (is_null($headerRow)) {
            return redirect()->back()->with('error', 'File yang Anda unggah error atau formatnya tidak bisa dibaca.');
        }

        $expectedHeaders = ['nim', 'nama_mahasiswa', 'email', 'dosen_pa'];
        if (count(array_diff($expectedHeaders, array_map('trim', $headerRow))) > 0) {
            $requiredHeaders = implode(', ', $expectedHeaders);
            $errorMessage = "Format header file tidak sesuai. Pastikan baris pertama berisi kolom: {$requiredHeaders}.";
            
            return redirect()->back()->with('error', $errorMessage)->with('suggest_template', true);
        }

        $rows = Excel::toCollection(new MahasiswaImport, $file)->first();

        if ($rows->isEmpty()) {
            return redirect()->back()->with('error', 'File yang Anda unggah hanya berisi header dan tidak ada baris data untuk diimpor.');
        }
        
        if ($rows->count() > 1500) {
            return redirect()->back()->with('error', 'Data yang dimasukkan terlalu banyak. Maksimal 1500 baris data per file.');
        }

        $import = new MahasiswaImport();
    
        $import->collection($rows);
        
        $importedCount = $import->getImportedCount();
        $skippedRows = $import->getSkippedRows();

        session()->flash('success', "{$importedCount} data mahasiswa berhasil diimpor.");
        if (!empty($skippedRows)) {
            session()->flash('import_warnings', $skippedRows);
            session()->flash('warning_summary', count($skippedRows) . " baris dilewati karena data duplikat atau Dosen PA tidak ditemukan.");
        }
        
        return redirect()->back();

    } catch (\Exception $e) {
        Log::error('Gagal total saat impor file: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal memproses file. Pastikan file tidak rusak dan formatnya benar.');
    }
}
}