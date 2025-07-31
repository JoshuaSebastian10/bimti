<?php

namespace App\Http\Controllers\Admin\DataUsers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Exists;

class DataMahasiswaController extends Controller
{   
    public function index(){
        return view('admin.dataUsers.mahasiswa.index');
    }

    public function create(){
        $dosens = User::role('dosen')->orderBy('name')->get();
        return view('admin.dataUsers.mahasiswa.create', compact('dosens'));
    }
    public function store(Request $request)
    {
  
            $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'dosen_pa_id' => 'required|exists:dosens,id',
            'status_akun' => 'required|in:aktif,nonAktif',
            'status_bimbingan' => 'required|in:akademik,skripsi',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'nim.required' => 'NIM wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        
        'nim.unique' => 'NIM ini sudah terdaftar di sistem.',
        'email.unique' => 'Alamat email ini sudah digunakan.',

        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        'dosen_pa_id.exists' => 'Dosen PA yang dipilih tidak valid.',
    ]);

        try {
          
           
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password), 
                ]);
              
                $user->assignRole('mahasiswa');

 
                Mahasiswa::create([
                    'nim' => $request->nim,
                    'status_bimbingan' => $request->status_bimbingan,
                    'status_akun' => $request->status_akun,
                    'user_id' => $user->id, 
                    'dosen_pa_id' => $request->dosen_pa_id,
                    'no_hp' => null,
                ]);
            });
        } catch (\Exception $e) {

            Log::error('Gagal membuat mahasiswa baru: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }

      
        return redirect()->route('admin.dataMahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }
    

    public function edit(Mahasiswa $mahasiswa){
        $dosens = User::role('dosen')->orderBy('name')->get();
        return view('admin.dataUsers.mahasiswa.edit', compact('mahasiswa','dosens'));
    }   

    
    public function update(Request $request, Mahasiswa $mahasiswa)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:mahasiswas,nim,' . $mahasiswa->id,
            'email' => 'required|email|max:255|unique:users,email,' . $mahasiswa->user_id,
            'password' => 'nullable|string|min:8', 
            'dosen_pa_id' => 'required|exists:users,id',
            'status_akun' => 'required|in:aktif,nonAktif',
            'status_bimbingan' => 'required|in:akademik,skripsi',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'nim.required' => 'NIM wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        
        'nim.unique' => 'NIM ini sudah terdaftar di sistem.',
        'email.unique' => 'Alamat email ini sudah digunakan.',

        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        'dosen_pa_id.exists' => 'Dosen PA yang dipilih tidak valid.',
    ]);
        try {
            DB::transaction(function () use ($request, $mahasiswa) {

                $mahasiswa->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);

               
                if ($request->filled('password')) {
                    $mahasiswa->user->update([
                        'password' => Hash::make($request->password),
                    ]);
                }


                $mahasiswa->update([
                    'nim' => $request->nim,
                    'dosen_pa_id' => $request->dosen_pa_id,
                    'status_akun' => $request->status_akun,
                    'status_bimbingan' => $request->status_bimbingan,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate mahasiswa: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }

        return redirect()->route('admin.dataMahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }


}
