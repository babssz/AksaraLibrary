@extends('layouts.guest')

@section('title', 'Reset Password - Perpustakaan Universitas Aksara')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-envelope mr-1"></i>{{ __('Email') }}
            </label>
            <input id="email" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="nama@aksara.ac.id" />
            @error('email')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Password Baru') }}
            </label>
            <input id="password" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="new-password"
                   placeholder="Minimal 8 karakter" />
            @error('password')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Konfirmasi Password') }}
            </label>
            <input id="password_confirmation" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password"
                   placeholder="Ulangi password baru" />
            @error('password_confirmation')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" 
                class="w-full bg-primary text-white px-4 py-3 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 font-semibold shadow-md">
            <i class="fas fa-key mr-2"></i>{{ __('Reset Password') }}
        </button>
    </form>
@endsection