@extends('layouts.guest')

@section('title', 'Login - Perpustakaan Universitas Aksara')

@section('content')
    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-envelope mr-1"></i>{{ __('Email') }}
            </label>
            <input id="email" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="nama@aksara.ac.id" />
            @error('email')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Password') }}
            </label>
            <input id="password" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password"
                   placeholder="Masukkan password" />
            @error('password')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-primary focus:ring-primary" 
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-secondary hover:text-primary transition" 
                   href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit" 
                class="w-full bg-primary text-white px-4 py-3 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 font-semibold shadow-md">
            <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Masuk') }}
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                {{ __('Belum punya akun?') }}
                <a href="{{ route('register') }}" 
                   class="text-secondary hover:text-primary font-semibold transition">
                    {{ __('Daftar sekarang') }}
                </a>
            </p>
        </div>
    </form>
@endsection