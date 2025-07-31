<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation
{
    private $dosens;
    public int $importedCount = 0;
    public array $skippedRows = [];

    public function __construct()
    {
        $this->dosens = Dosen::with('user')->get()->mapWithKeys(function ($dosen) {
            return [$dosen->user->name => $dosen];
        });
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) 
        {
              if ($row->filter()->isNotEmpty() === false) {
                continue; 
            }
            
            $rowNumber = $index + 2; 
            
            $mahasiswaExists = Mahasiswa::where('nim', $row['nim'])->exists();
            $userExists = User::where('email', $row['email'])->exists();

            if ($mahasiswaExists || $userExists) {
                // Catat baris yang dilewati
                $this->skippedRows[] = "Baris {$rowNumber}: NIM '{$row['nim']}' atau Email '{$row['email']}' sudah ada.";
                continue; 
            }
            
            $dosen = $this->dosens[$row['dosen_pa']] ?? null;
            if (!$dosen) {
                $this->skippedRows[] = "Baris {$rowNumber}: Dosen PA '{$row['dosen_pa']}' tidak ditemukan.";
                continue;
            }

           $user = User::create([
                    'name'     => $row['nama_mahasiswa'],
                    'email'    => $row['email'],
                    'password' => bcrypt('mahasiswa'),
                ]);

            $user->assignRole('mahasiswa');
            $user->mahasiswa()->create([
                    'nim'              => $row['nim'],
                    'dosen_pa_id'      => $dosen->id,
                    'status_bimbingan' => 'akademik',
                    'status_akun'      => 'nonAktif',
                ]);
            $this->importedCount++;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
    
    public function rules(): array
    {
        return [
            'nim' => 'required|numeric',
            'nama_mahasiswa' => 'required|string',
            'email' => 'required|email',
            'dosen_pa' => 'required|string',
        ];
    }
}