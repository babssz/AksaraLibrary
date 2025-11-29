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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:mahasiswa,pegawai',
            'nim' => 'required_if:role,mahasiswa|nullable|string|max:20',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
        ]);
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'nim' => $validated['nim'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}