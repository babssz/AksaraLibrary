<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // ✅ PERBAIKI: huruf besar 'A' di App

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required', 
                'email',
                'regex:/@aksara\.ac\.id$/i' // ✅ VALIDASI DOMAIN AKSAYA
            ],
            'password' => 'required',
        ], [
            'email.regex' => 'Hanya email dengan domain @aksara.ac.id yang diperbolehkan untuk login.', // ✅ PESAN ERROR CUSTOM
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isMahasiswa()) {
                return redirect()->route('mahasiswa.dashboard');
            } else {
                return redirect()->route('pegawai.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}