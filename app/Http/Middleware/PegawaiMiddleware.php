<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PegawaiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Fixed logic - check if user is authenticated first
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is NOT pegawai (assuming isPegawai() method exists)
        if (!auth()->user()->isPegawai()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}