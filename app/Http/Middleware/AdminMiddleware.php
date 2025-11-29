<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login dan role-nya admin atau pegawai
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai')) {
            return $next($request);
        }

        // Jika bukan admin/pegawai, redirect dengan error
        return redirect('/')->with('error', 'Akses ditolak. Hanya admin dan pegawai yang bisa mengakses halaman ini.');
    }
}