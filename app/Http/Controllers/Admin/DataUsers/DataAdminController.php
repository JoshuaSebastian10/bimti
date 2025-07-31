<?php

namespace App\Http\Controllers\Admin\DataUsers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DataAdminController extends Controller
{
    public function index(){
        return view('admin.dataUsers.admin.index');
    }

    public function create(){

        return view('admin.dataUsers.admin.create');
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.unique' => 'Alamat email ini sudah digunakan.',
        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
         
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password), 
                ]);
            
                $user->assignRole('admin');
            });
        } catch (\Exception $e) {
        
            Log::error('Gagal membuat Data Admin baru: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }

        return redirect()->route('admin.dataAdmin.index')->with('success', 'Data Admin berhasil ditambahkan.');
    }

    public function edit(User $admin){
       
        return view('admin.DataUsers.admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'. $admin->id,
            'password' => 'nullable|string|min:8',
        ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'email.required' => 'Alamat email wajib diisi.',
        'email.unique' => 'Alamat email ini sudah digunakan.',
        'email.email' => 'Format email tidak valid.',
        'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        try {
            DB::transaction(function () use ($request, $admin) {
                $admin->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);


                if ($request->filled('password')) {
                    $admin->update([
                        'password' => Hash::make($request->password),
                    ]);
                }

            });
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate admin: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }

        return redirect()->route('admin.dataAdmin.index')->with('success', 'Data Admin berhasil diperbarui.');
    }

}
