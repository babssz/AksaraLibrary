@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai - Perpustakaan Universitas Aksara')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-primary">Dashboard Pegawai</h1>
            <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Kelola layanan perpustakaan.</p>
        </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-primary mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('books.index') }}" class="bg-primary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                        <i class="fas fa-book-medical text-2xl mb-2"></i>
                        <p class="font-semibold">Manajemen Buku</p>
                    </a>
                    <a href="{{ route('loans.index') }}" class="bg-secondary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                        <i class="fas fa-tasks text-2xl mb-2"></i>
                        <p class="font-semibold text-sm">Kelola Peminjaman</p>
                    </a>
                    <a href="{{ route('pegawai.notifications.log') }}" class="bg-gray-100 text-primary p-4 rounded-lg text-center hover:bg-gray-200 transition">
                        <i class="fas fa-history text-2xl mb-2"></i>
                        <p class="font-semibold text-sm">Log Notifikasi</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection