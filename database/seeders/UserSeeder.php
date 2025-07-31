<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'id' => 1,
            'name' => 'admin',
            'email' => 'joshuaturu@gmail.com',
            'password' => bcrypt('admin')
        ]);
        $admin->assignRole('admin');

        $mahasiswa = User::create([
            'id' => 2,
            'name' => 'joshua',
            'email' => 'mahasiswa@email.com',
            'password' => bcrypt('joshua')
        ]);
        $mahasiswa->assignRole('mahasiswa');
    
        
        $dosen = User::create([
            'id' =>3,
            'name' => 'Prof. Dr. Ing. Drs. Parabelem T. D. Rompas, MT',
            'email' => 'dosen1@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen->assignRole('dosen');

        $dosen2 = User::create([
            'id' => 4,
            'name' => 'Dr. Irene R. H. T. Tangkawarow, ST, MISD',
            'email' => 'dosen2@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen2->assignRole('dosen');

        $dosen3 = User::create([
            'id' => 5,
            'name' => 'Dr. Audy A. Kenap, ST, M.sc',
            'email' => 'dosen3@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen3->assignRole('dosen');

        $dosen4 = User::create([
            'id' => 6,
            'name' => 'Dr. Efraim R. S. Moningkey, ST, MT',
            'email' => 'dosen4@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen4->assignRole('dosen');

        $dosen5 = User::create([
            'id' => 7,
            'name' => 'Dr. Glenn D. P. Maramis, M.CompSc',
            'email' => 'dosen5@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen5->assignRole('dosen');

        $dosen6 = User::create([
            'id' => 8,
            'name' => 'Ir. Gladly C. Rorimpandey ST, MISD',
            'email' => 'dosen6@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen6->assignRole('dosen');

        $dosen7 = User::create([
            'id' => 9,
            'name' => 'Vivi Peggie Rantung, ST, MISD',
            'email' => 'dosen7@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen7->assignRole('dosen');

        $dosen8 = User::create([
            'id' => 10,
            'name' => 'Quido C. Kainde ST, MM, MT',
            'email' => 'dosen8@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen8->assignRole('dosen');

        $dosen9 = User::create([
            'id' => 11,
            'name' => 'Kristofel Santa, S.ST, M.MT',
            'email' => 'dosen9@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen9->assignRole('dosen');

        $dosen10 = User::create([
            'id' => 12,
            'name' => 'Sondy C. Kumajas, ST. MT',
            'email' => 'dosen10@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen10->assignRole('dosen');

        $dosen11 = User::create([
            'id' => 13,
            'name' => 'Alfiansyah Hasibuan, S.Kom, M.Kom',
            'email' => 'dosen11@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen11->assignRole('dosen');

        $dosen12 = User::create([
            'id' => 14,
            'name' => 'Medi H. Tinambunan, S.Kom, M.Kom',
            'email' => 'dosen12@gmail.com',
            'password' => bcrypt('dosen')
        ]);
        $dosen12->assignRole('dosen');

    }

}
