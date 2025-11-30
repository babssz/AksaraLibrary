<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required', 
            'string', 
            'lowercase', 
            'email', 
            'max:255', 
            'unique:'.User::class,
            'regex:/@aksara\.ac\.id$/i'  
        ],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'nim' => ['required', 'string', 'max:20', 'unique:users'],
        'phone' => ['required', 'string', 'max:15'],
        'address' => ['required', 'string', 'max:500'],
    ], [
        'email.regex' => 'Hanya email dengan domain @aksara.ac.id yang diperbolehkan untuk registrasi.',  // ✅ PESAN ERROR
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'mahasiswa',
        'nim' => $request->nim,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    event(new Registered($user));

    return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan login.');
}
}