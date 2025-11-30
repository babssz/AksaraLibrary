@extends('layouts.guest')

@section('title', 'Verifikasi Email - Perpustakaan Universitas Aksara')

@section('content')
    <div class="mb-4 text-sm text-gray-600">
        <i class="fas fa-envelope-open-text text-primary mr-1"></i>
        {{ __('Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan. Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
            <i class="fas fa-check-circle mr-1"></i>
            {{ __('Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.') }}
        </div>
    @endif

    <div class="mt-6 flex flex-col space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" 
                    class="w-full bg-primary text-white px-4 py-3 rounded-md hover:opacity-90 transition focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 font-semibold shadow-md">
                <i class="fas fa-paper-plane mr-2"></i>{{ __('Kirim Ulang Email Verifikasi') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" 
                    class="w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 font-semibold">
                <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Log Out') }}
            </button>
        </form>
    </div>
@endsection