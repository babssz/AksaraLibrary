<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users',
            'regex:/@aksara\.ac\.id$/i',  // ✅ GANTI DENGAN INI (sama seperti login)
        ],
        'password' => 'required|string|min:8|confirmed',
        'nim' => 'required|string|max:20|unique:users',
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:500',
    ], [
        'email.regex' => 'Hanya email dengan domain @aksara.ac.id yang diperbolehkan untuk registrasi.',
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'mahasiswa',
        'nim' => $validated['nim'],
        'phone' => $validated['phone'],
        'address' => $validated['address'],
    ]);

    return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
}
}