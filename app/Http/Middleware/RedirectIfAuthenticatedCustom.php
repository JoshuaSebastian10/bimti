<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
             /** @var \App\Models\User $user */
            $user = Auth::user();
    
            if ($user->hasRole('admin')) {
                return redirect('/admin');
            }
            elseif($user->hasRole('dosen')){
                return redirect('/dosen');
            }
            elseif($user->hasRole('mahasiswa')){
                return redirect('/mahasiswa');
            }
            return redirect()->intended('/'); 
        }
    
        return $next($request);
    }
    
}
