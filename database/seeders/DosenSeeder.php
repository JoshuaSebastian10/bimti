<?php

namespace Database\Seeders;


use App\Models\Dosen;
use Illuminate\Database\Seeder;


class DosenSeeder extends Seeder
{
    public function run(): void
    {
        Dosen::create([
            'id' => '1',
            'nip' => '19651217 199203 1 002',
            'status_akun' => 'aktif',
            'user_id' => '3'
        ]);

        Dosen::create([
            'id' => '2',
            'nip' => '19841031 200812 2 002',
            'status_akun' => 'aktif',
            'user_id' => '4'
        ]);

        Dosen::create([
            'id' => '3',
            'nip' => '19730922 200812 1 001',            
            'status_akun' => 'aktif',
            'user_id' => '5'
        ]);

        Dosen::create([
            'id' => '4',
            'nip' => '19850914 201012 1 007',
            'status_akun' => 'aktif',
            'user_id' => '6'
        ]);

        Dosen::create([
            'id' => '5',
            'nip' => '19800331 2015041 001',
            'status_akun' => 'aktif',
            'user_id' => '7'
        ]);

        Dosen::create([
            'id' => '6',
            'nip' => '19861009 200812 2 004',
            'status_akun' => 'aktif',
            'user_id' => '8'
        ]);

        Dosen::create([
            'id' => '7',
            'nip' => '19830416 200812 2 002',
            'status_akun' => 'aktif',
            'user_id' => '9'
        ]);

        Dosen::create([
            'id' => '8',
            'nip' => '19840606 200912 1 007',            
            'status_akun' => 'aktif',
            'user_id' => '10'
        ]);

        Dosen::create([
            'id' => '9',
            'nip' => '19870531 201504 1 003',
            'status_akun' => 'aktif',
            'user_id' => '11'
        ]);

        Dosen::create([
            'id' => '10',
            'nip' => '19780910 201212 1 006',
            'status_akun' => 'aktif',
            'user_id' => '12'
        ]);

        Dosen::create([
            'id' => '11',
            'nip' => '19890726 202203 1 004',
            'status_akun' => 'aktif',
            'user_id' => '13'
        ]);

        Dosen::create([
            'id' => '12',
            'nip' => '19930827 202203 1 003',
            'status_akun' => 'aktif',
            'user_id' => '14'
        ]);

 
    }
}
