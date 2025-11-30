@extends('layouts.guest')

@section('title', 'Register - Perpustakaan Universitas Aksara')

@section('content')
    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-4 text-sm">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-user mr-1"></i>{{ __('Nama Lengkap') }}
            </label>
            <input id="name" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   placeholder="Nama lengkap" />
            @error('name')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-envelope mr-1"></i>{{ __('Email') }}
            </label>
            <input id="email" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="email"
                   placeholder="nama@aksara.ac.id" />
            @error('email')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- NIM -->
        <div class="mb-3">
            <label for="nim" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-id-card mr-1"></i>{{ __('NIM') }}
            </label>
            <input id="nim" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="text" 
                   name="nim" 
                   value="{{ old('nim') }}" 
                   required 
                   autocomplete="nim"
                   placeholder="Nomor Induk Mahasiswa" />
            @error('nim')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-3">
            <label for="phone" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-phone mr-1"></i>{{ __('Telepon') }}
            </label>
            <input id="phone" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="text" 
                   name="phone" 
                   value="{{ old('phone') }}" 
                   required 
                   autocomplete="tel"
                   placeholder="081234567890" />
            @error('phone')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Address -->
        <div class="mb-3">
            <label for="address" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-map-marker-alt mr-1"></i>{{ __('Alamat') }}
            </label>
            <textarea id="address" 
                      class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                      name="address" 
                      required 
                      rows="2"
                      placeholder="Alamat lengkap">{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-1 text-xs text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Password') }}
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

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-primary mb-1">
                <i class="fas fa-lock mr-1"></i>{{ __('Konfirmasi Password') }}
            </label>
            <input id="password_confirmation" 
                   class="w-full border-2 border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password"
                   placeholder="Ulangi password" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-secondary hover:text-primary transition" 
               href="{{ route('login') }}">
                <i class="fas fa-arrow-left mr-1"></i>{{ __('Sudah punya akun? Login') }}
            </a>

            <button type="submit" 
                    class="bg-primary text-white px-6 py-2 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-sm font-semibold shadow-md">
                <i class="fas fa-user-plus mr-1"></i>{{ __('Daftar') }}
            </button>
        </div>
    </form>
@endsection