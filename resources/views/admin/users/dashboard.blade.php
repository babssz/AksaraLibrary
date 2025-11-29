@extends('layouts.admin')

@section('title', 'Dashboard Admin - Perpustakaan Universitas Aksara')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-primary">Dashboard Admin</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Kelola sistem perpustakaan dari sini.</p>
</div>


<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-primary mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.users.index') }}" class="bg-primary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                <i class="fas fa-users text-2xl mb-2"></i>
                <p class="font-semibold">Manajemen User</p>
            </a>
            <a href="{{ route('books.index') }}" class="bg-secondary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                <i class="fas fa-book-medical text-2xl mb-2"></i>
                <p class="font-semibold">Manajemen Buku</p>
            </a>


        </div>
    </div>
</div>
@endsection