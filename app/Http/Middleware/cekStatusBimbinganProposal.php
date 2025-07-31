<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class cekStatusBimbinganProposal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        {
            if (Auth::check() && Auth::user()->mahasiswa) {
                if ((Auth::user()->mahasiswa->status_bimbingan === 'proposal' || Auth::user()->mahasiswa->status_bimbingan === 'skripsi') && Auth::user()->mahasiswa->status_akun === 'aktif') {
                    return $next($request);
                }
            }
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Hubungi Admin Untuk Mengaktifkan Status Bimbingan Proposal');
        }
    }
}
