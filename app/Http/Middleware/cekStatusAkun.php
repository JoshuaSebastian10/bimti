<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class cekStatusAkun
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          
          if (Auth::check() && Auth::user()->mahasiswa) {
            if (Auth::user()->mahasiswa->status_akun === 'aktif') {
                return $next($request);
            }
        }
        return redirect()->route('mahasiswa.dashboard')->with('error', 'Akun Anda belum aktif untuk mengakses fitur ini.');
    }
}
