<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BimbinganExport implements FromCollection, WithHeadings
{
    protected $bimbingans;
    public function __construct($bimbingans) { $this->bimbingans = $bimbingans; }
    public function collection() {
        return $this->bimbingans->map(function($b) {
            return [
                'Tanggal' => $b->tanggal_bimbingan_format,
                'Mahasiswa' => $b->mahasiswa->user->name,
                'Topik' => $b->topik,
                'Jenis' => $b->jenis_bimbingan,
                'Status' => $b->status,
            ];
        });
    }
    public function headings(): array {
        return ["Tanggal", "Mahasiswa", "Topik", "Jenis", "Status"];
    }
}