<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            Permission::create(['name' => 'tambah-user']);
            Permission::create(['name' => 'edit-user']);
            Permission::create(['name' => 'hapus-user']);
            Permission::create(['name' => 'lihat-user']);
            Permission::create(['name' => 'kelola-roles']);
    
            Permission::create(['name' => 'tambah-bimbingan']);
            Permission::create(['name' => 'edit-bimbingan']);
            Permission::create(['name' => 'setujui-bimbingan']);
            Permission::create(['name' => 'tolak-bimbingan']);
            Permission::create(['name' => 'history-bimbingan']);
            Permission::create(['name' => 'hapus-bimbingan']);
            Permission::create(['name' => 'lihat-bimbingan']);
    
    
            Permission::create(['name' => 'buat-jadwal-bimbingan']);
            Permission::create(['name' => 'edit-jadwal-bimbingan']);
            Permission::create(['name' => 'hapus-jadwal-bimbingan']);
            Permission::create(['name' => 'lihat-jadwal']);
    
    
            Role::create(['name' => 'admin']);
            Role::create(['name' => 'mahasiswa']);
            Role::create(['name' => 'dosen']);
    
            $roleAdmin = Role::findByName('admin');
            $roleAdmin->givePermissionTo('tambah-user');
            $roleAdmin->givePermissionTo('edit-user');
            $roleAdmin->givePermissionTo('lihat-user');
            $roleAdmin->givePermissionTo('kelola-roles');
            $roleAdmin->givePermissionTo('history-bimbingan');
            $roleAdmin->givePermissionTo('lihat-bimbingan');
            $roleAdmin->givePermissionTo('buat-jadwal-bimbingan');
            $roleAdmin->givePermissionTo('edit-jadwal-bimbingan');
            $roleAdmin->givePermissionTo('hapus-jadwal-bimbingan');
            $roleAdmin->givePermissionTo('lihat-jadwal');
    
            $roleMahasiswa = Role::findByname('mahasiswa');
            $roleMahasiswa->givePermissionTo('tambah-bimbingan');
            $roleMahasiswa->givePermissionTo('edit-bimbingan');
            $roleMahasiswa->givePermissionTo('history-bimbingan');
            $roleMahasiswa->givePermissionTo('hapus-bimbingan');
            $roleMahasiswa->givePermissionTo('lihat-bimbingan');
            $roleMahasiswa->givePermissionTo('lihat-jadwal');
    
            $roleDosen = Role::findByName('dosen');
            $roleDosen->givePermissionTo('setujui-bimbingan');
            $roleDosen->givePermissionTo('tolak-bimbingan');
            $roleDosen->givePermissionTo('history-bimbingan');
            $roleDosen->givePermissionTo('lihat-bimbingan');
            $roleDosen->givePermissionTo('buat-jadwal-bimbingan');
            $roleDosen->givePermissionTo('edit-jadwal-bimbingan');
            $roleDosen->givePermissionTo('hapus-jadwal-bimbingan');
            $roleDosen->givePermissionTo('lihat-jadwal');
            $roleDosen->givePermissionTo('edit-bimbingan');
        }
    }
}
