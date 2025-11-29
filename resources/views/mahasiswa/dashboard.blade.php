@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa - Perpustakaan Universitas Aksara')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-primary">Dashboard Mahasiswa</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Akses koleksi dan layanan perpustakaan.</p>
</div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-primary mb-4">Akses Cepat</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('books.index') }}" class="bg-primary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p class="font-semibold text-sm">Cari Buku</p>
            </a>
            <a href="{{ route('reviews.index') }}" class="bg-secondary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                <i class="fas fa-star text-2xl mb-2"></i>
                <p class="font-semibold text-sm">Beri Review</p>
            </a>
            <a href="{{ route('loans.history') }}" class="bg-accent text-primary p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                <i class="fas fa-history text-2xl mb-2"></i>
                <p class="font-semibold text-sm">Riwayat</p>
            </a>
        </div>
    </div>
</div>

<!-- Rekomendasi Buku -->
<div class="mt-8 bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-xl font-bold text-primary mb-4">Rekomendasi Untuk Anda</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="bg-primary bg-opacity-10 text-primary w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-book"></i>
            </div>
            <h4 class="font-semibold text-primary">Algoritma & Struktur Data</h4>
            <p class="text-sm text-gray-600 mt-1">Penulis: John Doe</p>
            <p class="text-xs text-gray-500 mt-2">Tersedia: 5 eksemplar</p>
        </div>
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="bg-secondary bg-opacity-10 text-secondary w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-laptop-code"></i>
            </div>
            <h4 class="font-semibold text-primary">Machine Learning Fundamentals</h4>
            <p class="text-sm text-gray-600 mt-1">Penulis: Jane Smith</p>
            <p class="text-xs text-gray-500 mt-2">Tersedia: 3 eksemplar</p>
        </div>
        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="bg-accent bg-opacity-10 text-accent w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                <i class="fas fa-database"></i>
            </div>
            <h4 class="font-semibold text-primary">Database System Concepts</h4>
            <p class="text-sm text-gray-600 mt-1">Penulis: Abraham Silberschatz</p>
            <p class="text-xs text-gray-500 mt-2">Tersedia: 2 eksemplar</p>
        </div>
    </div>
</div>
@endsection