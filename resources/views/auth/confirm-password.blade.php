@extends('layouts.guest')

@section('title', 'Konfirmasi Password - Perpustakaan Universitas Aksara')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        <i class="fas fa-shield-alt text-primary mr-1"></i>
        {{ __('Ini adalah area aman. Silakan konfirmasi password Anda sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Password') }}
            </label>
            <input id="password" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="password" 
                   name="password" 
                   required 
                   autocomplete="current-password"
                   placeholder="Masukkan password Anda" />
            @error('password')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" 
                class="w-full bg-primary text-white px-4 py-3 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 font-semibold shadow-md">
            <i class="fas fa-check mr-2"></i>{{ __('Konfirmasi') }}
        </button>
    </form>
@endsection