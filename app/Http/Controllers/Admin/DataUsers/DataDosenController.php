<?php

namespace App\Http\Controllers\Admin\DataUsers;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DataDosenController extends Controller
{
    public function index(){
        return view('admin.dataUsers.dosen.index');
    }

    

    public function create(){

        return view('admin.dataUsers.dosen.create');
    }


    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:100|unique:dosens,nip',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8',
            'status_akun' => 'required|in:aktif,nonAktif',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'nip.required' => 'nip wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        
        'nip.unique' => 'nip ini sudah terdaftar di sistem.',
        'email.unique' => 'Alamat email ini sudah digunakan.',

        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
         
            DB::transaction(function () use ($request) {
                // Buat record User terlebih dahulu
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                // Beri role 'mahasiswa'
                $user->assignRole('dosen');


                Dosen::create([
                    'nip' => $request->nip,
                    'status_akun' => $request->status_akun,
                    'user_id' => $user->id, 
                ]);
            });
        } catch (\Exception $e) {

            Log::error('Gagal membuat Data Dosen baru: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }

    
        return redirect()->route('admin.dataDosen.index')->with('success', 'Data Dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen){
        return view('admin.dataUsers.dosen.edit', compact('dosen'));
    }

    
    public function update(Request $request, Dosen $dosen)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:100|unique:dosens,nip,' . $dosen->id,
            'email' => 'required|email|max:255|unique:users,email,' . $dosen->user_id,
            'password' => 'nullable|string|min:8',
            'status_akun' => 'required|in:aktif,nonAktif',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'nip.required' => 'nip wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        
        'nip.unique' => 'nip ini sudah terdaftar di sistem.',
        'email.unique' => 'Alamat email ini sudah digunakan.',

        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
            DB::transaction(function () use ($request, $dosen) {

                $dosen->user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);


                if ($request->filled('password')) {
                    $dosen->user->update([
                        'password' => Hash::make($request->password),
                    ]);
                }


                $dosen->update([
                    'nip' => $request->nip,
                    'status_akun' => $request->status_akun, 
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate Dosen: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }

        return redirect()->route('admin.dataDosen.index')->with('success', 'Data Dosen berhasil diperbarui.');
    }
    
    
}
