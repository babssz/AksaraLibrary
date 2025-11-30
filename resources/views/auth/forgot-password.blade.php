@extends('layouts.guest')

@section('title', 'Lupa Password - Perpustakaan Universitas Aksara')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        <i class="fas fa-info-circle text-primary mr-1"></i>
        {{ __('Lupa password? Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link reset password.') }}
    </div>

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            <i class="fas fa-check-circle mr-1"></i>{{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-6">
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
                   placeholder="nama@aksara.ac.id" />
            @error('email')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" 
                class="w-full bg-primary text-white px-4 py-3 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 font-semibold shadow-md">
            <i class="fas fa-paper-plane mr-2"></i>{{ __('Kirim Link Reset Password') }}
        </button>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" 
               class="text-secondary hover:text-primary text-sm font-semibold transition inline-flex items-center">
                <i class="fas fa-arrow-left mr-1"></i>
                {{ __('Kembali ke Login') }}
            </a>
        </div>
    </form>
@endsection