@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa - Perpustakaan Universitas Aksara')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Dashboard Mahasiswa</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Akses koleksi dan layanan perpustakaan.</p>
    </div>
    
    <!-- Status Denda & Peringatan -->
    @if($hasBlockingDenda || $totalDenda > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-red-100 text-red-600 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-800">Status Denda Tertunggak</h3>
                    <p class="text-red-600">
                        @if($totalDenda > 0)
                            Total denda: <span class="font-bold">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                        @endif
                        
                        @if($jumlahBukuTerlambat > 0 && $totalDenda > 0)
                            - 
                        @endif
                        
                        @if($jumlahBukuTerlambat > 0)
                            <span class="font-bold">{{ $jumlahBukuTerlambat }} buku terlambat</span>
                        @endif
                    </p>
                </div>
            </div>
            
            @if($hasBlockingDenda)
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-ban mr-2"></i>Peminjaman Diblokir
            </div>
            @endif
        </div>
        
        @if($jumlahBukuTerlambat > 0)
        <div class="mt-4">
            <p class="text-sm text-red-700">
                <i class="fas fa-info-circle mr-1"></i>
                Anda memiliki {{ $jumlahBukuTerlambat }} buku yang terlambat dikembalikan. Segera kembalikan buku untuk menghindari denda tambahan.
            </p>
        </div>
        @endif
        
        @if($hasBlockingDenda)
        <div class="mt-4">
            <p class="text-sm text-red-700">
                <i class="fas fa-ban mr-1"></i>
                Peminjaman baru diblokir sampai buku yang terlambat dikembalikan. Silakan hubungi admin perpustakaan.
            </p>
        </div>
        @endif
    </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Actions & Status -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-primary mb-4">Akses Cepat</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('books.index') }}" 
                       class="bg-primary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition {{ $hasBlockingDenda ? 'opacity-50 cursor-not-allowed' : '' }}"
                       @if($hasBlockingDenda) onclick="return false;" title="Peminjaman diblokir karena ada denda tertunggak" @endif>
                        <i class="fas fa-search text-2xl mb-2"></i>
                        <p class="font-semibold text-sm">Cari Buku</p>
                        @if($hasBlockingDenda)
                        <p class="text-xs opacity-75 mt-1">Diblokir</p>
                        @endif
                    </a>
                    
                    <a href="{{ route('loans.history') }}" class="bg-accent text-primary p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                        <i class="fas fa-history text-2xl mb-2"></i>
                        <p class="font-semibold text-sm">Riwayat</p>
                    </a>
                </div>
            </div>
    
            <!-- Status Peminjaman Aktif -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-primary">Peminjaman Aktif</h3>
                    <span class="bg-primary text-white text-xs px-2 py-1 rounded-full font-semibold">
                        {{ $activeLoans->count() }}
                    </span>
                </div>
                
                @if($activeLoans->count() > 0)
                <div class="space-y-3">
                    @foreach($activeLoans as $loan)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border-l-4 {{ $loan->is_late ? 'border-red-500' : 'border-green-500' }}">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm line-clamp-1">{{ $loan->book->judul }}</h4>
                            <p class="text-xs text-gray-600">Jatuh tempo: {{ $loan->formatted_due_date }}</p>
                            @if($loan->is_late)
                            <p class="text-xs text-red-600 font-semibold mt-1">
                                Denda: Rp {{ number_format($loan->calculateDenda(), 0, ',', '.') }}
                            </p>
                            @endif
                        </div>
                        @if($loan->is_late)
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-semibold">
                                Terlambat {{ $loan->days_late }} hari
                            </span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                                Aktif
                            </span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-book-open text-3xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500 text-sm">Tidak ada peminjaman aktif</p>
                    <a href="{{ route('books.index') }}" class="text-secondary hover:text-primary text-xs font-semibold mt-2 inline-block">
                        Pinjam buku sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    
        <!-- Rekomendasi Buku -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-primary">Rekomendasi Untuk Anda</h3>
                    <a href="{{ route('books.index') }}" class="text-secondary hover:text-primary text-sm font-semibold {{ $hasBlockingDenda ? 'opacity-50 cursor-not-allowed' : '' }}"
                       @if($hasBlockingDenda) onclick="return false;" @endif>
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
    
                <!-- Buku Populer -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">📊 Buku Populer</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($popularBooks as $book)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary w-12 h-12 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book"></i>
                                </div>
                                @if($book->reviews_avg_rating)
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    <span class="text-sm font-semibold text-gray-700">{{ number_format($book->reviews_avg_rating, 1) }}</span>
                                </div>
                                @endif
                            </div>
                            <h4 class="font-semibold text-primary text-sm line-clamp-2">{{ $book->judul }}</h4>
                            <p class="text-xs text-gray-600 mt-1">by {{ $book->penulis }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-xs text-gray-500">Stok: {{ $book->stok }}</span>
                                <a href="{{ route('books.show', $book) }}" 
                                   class="text-xs text-secondary hover:text-primary font-semibold {{ $hasBlockingDenda ? 'opacity-50 cursor-not-allowed' : '' }}"
                                   @if($hasBlockingDenda) onclick="return false;" title="Tidak dapat meminjam karena ada denda tertunggak" @endif>
                                    {{ $hasBlockingDenda ? 'Diblokir' : 'Detail' }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
    
                <!-- Buku Baru -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">🆕 Buku Baru</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($newBooks as $book)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="bg-secondary bg-opacity-10 text-secondary w-12 h-12 rounded-lg flex items-center justify-center mb-3">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4 class="font-semibold text-primary text-sm line-clamp-2">{{ $book->judul }}</h4>
                            <p class="text-xs text-gray-600 mt-1">by {{ $book->penulis }}</p>
                            <p class="text-xs text-gray-500 mt-2">Tahun: {{ $book->tahun_terbit }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <span class="text-xs text-gray-500">Stok: {{ $book->stok }}</span>
                                <a href="{{ route('books.show', $book) }}" 
                                   class="text-xs text-secondary hover:text-primary font-semibold {{ $hasBlockingDenda ? 'opacity-50 cursor-not-allowed' : '' }}"
                                   @if($hasBlockingDenda) onclick="return false;" title="Tidak dapat meminjam karena ada denda tertunggak" @endif>
                                    {{ $hasBlockingDenda ? 'Diblokir' : 'Detail' }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection