<?php

use App\Models\User;
use App\Models\Bimbingan;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Notifications\DemoNotification;
use App\Http\Controllers\MailController;
use App\Livewire\Mahasiswa\BimbinganSaya;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\JadwalDosenController;
use App\Http\Controllers\Imports\DataMahasiswaImport;
use App\Http\Controllers\LampiranBimbinganController;
use App\Http\Controllers\Admin\ManajemenBimbinganController;
use App\Http\Controllers\Laporan\LaporanBimbinganController;
use App\Http\Controllers\Admin\DataUsers\DataAdminController;
use App\Http\Controllers\Admin\DataUsers\DataDosenController;
use App\Http\Controllers\Admin\DataUsers\DataMahasiswaController;
use App\Http\Controllers\Dosen\Bimbingan\detailMahasiswaBimbingan;
use App\Http\Controllers\Dosen\Bimbingan\daftarBimbinganController;
use App\Http\Controllers\Admin\DashboardController as adminDashboard;
use App\Http\Controllers\Dosen\DashboardController as dosenDashboard;
use App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganSayaController;
use App\Http\Controllers\Dosen\Bimbingan\mahasiswaBimbinganController;
use App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganSkripsiController;
use App\Http\Controllers\Dosen\jadwalBimbingan\jadwalBimbinganController;
use App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganAkademikController;
use App\Http\Controllers\Mahasiswa\Bimbingan\BimbinganProposalController;
use App\Http\Controllers\Mahasiswa\DashboardController as mahasiswaDashboard;

Route::get('/', function () {
    return view('welcome');
});



// Route::get('/dashboard', function s() {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->middleware('auth','role:admin')->group(function(){
    Route::get('/',[adminDashboard::class, 'index'])->name('dashboard');
    Route::get('mahasiswa/import',[DataMahasiswaImport::class, 'create'])->name('mahasiswa.import.create');
    Route::post('mahasiswa/import',[DataMahasiswaImport::class, 'store'])->name('mahasiswa.import.store');
    Route::get('dataMahasiswa',[DataMahasiswaController::class, 'index'])->name('dataMahasiswa.index');
    Route::get('dataMahasiswa/create',[DataMahasiswaController::class, 'create'])->name('dataMahasiswa.create');
    Route::post('dataMahasiswa',[DataMahasiswaController::class, 'store'])->name('dataMahasiswa.store');
    Route::get('dataMahasiswa{mahasiswa}/edit',[DataMahasiswaController::class, 'edit'])->name('dataMahasiswa.edit');
    Route::put('dataMahasiswa{mahasiswa}',[DataMahasiswaController::class, 'update'])->name('dataMahasiswa.update');
    Route::get('dataDosen',[DataDosenController::class, 'index'])->name('dataDosen.index');
    Route::get('dataDosen/create',[DataDosenController::class, 'create'])->name('dataDosen.create');
    Route::post('dataDosen',[DataDosenController::class, 'store'])->name('dataDosen.store');
    Route::get('datadosen{dosen}/edit',[DatadosenController::class, 'edit'])->name('dataDosen.edit');
    Route::put('datadosen{dosen}',[DatadosenController::class, 'update'])->name('dataDosen.update');
    Route::get('dataAdmin',[DataAdminController::class, 'index'])->name('dataAdmin.index');
    Route::get('dataAdmin/create',[DataAdminController::class, 'create'])->name('dataAdmin.create');
    Route::post('dataAdmin',[DataAdminController::class, 'store'])->name('dataAdmin.store');
    Route::get('dataAdmin{admin}/edit',[DataAdminController::class, 'edit'])->name('dataAdmin.edit');
    Route::put('dataAdmin{admin}',[DataAdminController::class, 'update'])->name('dataAdmin.update');

    Route::get('jadwalDosen',[JadwalDosenController::class, 'index'])->name('jadwalDosen');
    Route::get('manajemenBimbingan',[ManajemenBimbinganController::class, 'index'])->name('manajemenBimbingan');

    
});

Route::prefix('dosen')->name('dosen.')->middleware('auth','role:dosen')->group(function(){
    Route::get('/',[dosenDashboard::class,'index'])->name('dashboard');
    Route::get('JadwalBimbingan',[jadwalBimbinganController::class,'index'])->name('jadwalBimbingan');
    Route::get('daftarBimbingan',[daftarBimbinganController::class, 'index'])->name('daftarBimbingan');
    Route::get('mahasiswaBimbingan',[mahasiswaBimbinganController::class, 'index'])->name('mahasiswaBimbingan');
    Route::get('mahasiswaBimbingan{mahasiswa}/detail',[mahasiswaBimbinganController::class, 'detail'])->name('mahasiswaBimbingan.detail');
    // Route::get('laporan-bimbingan/export', [LaporanBimbinganController::class, 'export'])->name('laporan.bimbingan.export');

});

Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('auth', 'role:mahasiswa')->group(function(){
    Route::get('/',[mahasiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('BimbinganAkademik/create',[BimbinganAkademikController::class, 'index'])->middleware('status-akun')->name('bimbinganAkademik');
    Route::get('BimbinganProposal/create',[BimbinganProposalController::class, 'index'])->middleware('status-akun', 'status-bimbingan-proposal')->name('bimbinganProposal.create');
    Route::get('BimbinganSkripsi/create',[BimbinganSkripsiController::class, 'index'])->middleware('status-akun','status-bimbingan-skripsi')->name('bimbinganSkripsi.create');

    Route::get('BimbinganSaya',[BimbinganSayaController::class, 'index'])->name('bimbinganSaya');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('bimbingan/{bimbingan}/lampiran', [LampiranBimbinganController::class, 'show'])->name('bimbingan.lampiran.show');    

    Route::get('laporan-bimbingan/export', [LaporanBimbinganController::class, 'export'])->name('laporan.bimbingan.export');
});

Route::get('/tes-pusher', function () {
    $p = new \Pusher\Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        ['cluster' => env('PUSHER_APP_CLUSTER', 'ap1'), 'useTLS' => true]
    );
    $p->trigger('tes-channel', 'tes-event', ['pesan' => 'halo dari server']);
    return 'ok';
});
// Route "pintar" untuk /dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('dosen')) {
        return redirect()->route('dosen.dashboard');
    }

    if ($user->hasRole('mahasiswa')) {
        return redirect()->route('mahasiswa.dashboard');
    }

    // Sebagai fallback jika user tidak punya role
    return redirect('/login');

})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
