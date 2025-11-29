<!-- resources/views/books/index.blade.php -->
@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'Katalog Buku' : 'Manajemen Buku') : 'Katalog Buku')

@section('content')
@if(auth()->check() && auth()->user()->role === 'mahasiswa')
    <!-- HEADER UNTUK MAHASISWA -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Katalog Buku</h1>
        <p class="text-gray-600">Temukan buku yang Anda butuhkan</p>
    </div>
@elseif(!auth()->check())
    <!-- HEADER UNTUK GUEST -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Katalog Buku</h1>
        <p class="text-gray-600">Jelajahi koleksi buku perpustakaan kami</p>
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3 text-lg"></i>
                <p class="text-blue-800">
                    <span class="font-semibold">Login untuk meminjam buku</span> - 
                    Daftar akun mahasiswa untuk mengakses layanan peminjaman
                </p>
            </div>
        </div>
    </div>
@else
    <!-- HEADER UNTUK ADMIN & PEGAWAI -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Manajemen Buku</h1>
        <p class="text-gray-600">Kelola semua buku dalam sistem perpustakaan</p>
    </div>
@endif

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold {{ (auth()->check() && auth()->user()->role === 'mahasiswa') || !auth()->check() ? 'text-gray-800' : 'text-primary' }}">
            @if(!auth()->check())
                Daftar Buku
            @else
                {{ auth()->user()->role === 'mahasiswa' ? 'Daftar Buku' : 'Katalog Buku' }}
            @endif
        </h2>
        <p class="text-gray-600">Total {{ count($books) }} buku ditemukan</p>
    </div>
    
    <!-- TOMBOL TAMBAH HANYA UNTUK ADMIN & PEGAWAI -->
    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai'))
    <a href="{{ route('books.create') }}" 
       class="bg-secondary hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition flex items-center">
        <i class="fas fa-book-medical mr-2"></i>
        Tambah Buku
    </a>
    @endif

    <!-- TOMBOL LOGIN/REGISTER UNTUK GUEST -->
    @if(!auth()->check())
    <div class="flex space-x-3">
        <a href="{{ route('login') }}" 
           class="bg-secondary hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-sign-in-alt mr-2"></i>
            Login
        </a>
        <a href="{{ route('register') }}" 
           class="bg-primary hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-user-plus mr-2"></i>
            Daftar
        </a>
    </div>
    @endif
</div>

<!-- SEARCH & FILTER -->
<div class="bg-white p-6 rounded-xl shadow-lg mb-6">
    <form action="{{ route('books.index') }}" method="GET" class="flex gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
            <input type="text" name="search" placeholder="Cari judul, penulis, atau kategori..." 
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
        </div>
        
        <div class="w-48">
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <option value="">Semua Kategori</option>
                <option value="Teknologi" {{ request('kategori') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                <option value="Komputer" {{ request('kategori') == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                <option value="Database" {{ request('kategori') == 'Database' ? 'selected' : '' }}>Database</option>
                <option value="Sains" {{ request('kategori') == 'Sains' ? 'selected' : '' }}>Sains</option>
                <option value="Fiksi" {{ request('kategori') == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
            </select>
        </div>
        
        <button type="submit" class="bg-primary hover:bg-opacity-90 text-white px-6 py-2 rounded-lg transition flex items-center h-[42px]">
            <i class="fas fa-search mr-2"></i>
            Cari
        </button>
        
        @if(request()->has('search') || request()->has('kategori'))
        <a href="{{ route('books.index') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center h-[42px]">
            <i class="fas fa-times mr-2"></i>
            Reset
        </a>
        @endif
    </form>
</div>

<!-- BOOKS GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($books as $book)
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100">
        <!-- COVER BUKU -->
        @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" 
                class="w-full h-48 object-cover rounded-t-xl">
        @else
            <div class="w-full h-48 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-t-xl flex items-center justify-center">
                <i class="fas fa-book text-4xl text-primary opacity-40"></i>
            </div>
        @endif
        
        <div class="p-5">
            <!-- JUDUL BUKU -->
            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2 hover:text-primary transition-colors">
                {{ $book->judul }}
            </h3>
            
            <!-- INFO BUKU -->
            <p class="text-gray-600 text-sm mb-1 flex items-center">
                <i class="fas fa-user-edit text-secondary mr-2"></i>
                {{ $book->penulis }}
            </p>
            <p class="text-gray-500 text-xs mb-3 flex items-center">
                <i class="fas fa-calendar text-gray-400 mr-2"></i>
                {{ $book->tahun_terbit }} • 
                <i class="fas fa-tag text-gray-400 ml-2 mr-1"></i>
                {{ $book->kategori }}
            </p>
            
            <!-- RATING & STATUS -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                    <i class="fas fa-star text-yellow-500 text-sm"></i>
                    <span class="text-sm font-medium text-gray-700 ml-1">{{ number_format($book->rating, 1) }}</span>
                </div>
                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $book->isAvailable() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    <i class="fas fa-{{ $book->isAvailable() ? 'check' : 'times' }} mr-1"></i>
                    {{ $book->isAvailable() ? 'Tersedia' : 'Dipinjam' }}
                </span>
            </div>
            
            <!-- ACTION BUTTONS -->
            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                <!-- BUTTON DETAIL (SEMUA ROLE & GUEST) -->
                <a href="{{ route('books.show', $book) }}" 
                   class="text-primary hover:text-opacity-80 font-medium text-sm flex items-center transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    Detail
                </a>
                
                <!-- ACTION UNTUK GUEST -->
                @if(!auth()->check())
                    @if($book->isAvailable())
                    <a href="{{ route('login') }}" 
                       class="text-secondary hover:text-opacity-80 text-sm flex items-center transition-colors">
                        <i class="fas fa-hand-holding mr-1"></i>
                        Login untuk Pinjam
                    </a>
                    @else
                    <span class="text-gray-400 text-sm flex items-center">
                        <i class="fas fa-lock mr-1"></i>
                        Tidak Tersedia
                    </span>
                    @endif
                
                <!-- ACTION UNTUK MAHASISWA -->

                
                <!-- ACTION UNTUK ADMIN & PEGAWAI -->
                @elseif(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai'))
                <div class="flex space-x-3">
                    <a href="{{ route('books.edit', $book->id) }}" 
                    class="text-secondary hover:text-opacity-80 text-sm flex items-center transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </a>
                    <form action="{{ route('books.delete', $book) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 text-sm flex items-center transition-colors"
                                onclick="return confirm('Yakin hapus buku {{ $book->judul }}?')">
                            <i class="fas fa-trash mr-1"></i>
                            Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- EMPTY STATE -->
@if(count($books) === 0)
<div class="text-center py-12 bg-white rounded-xl shadow-lg">
    <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
    <p class="text-gray-500 text-lg mb-2">Tidak ada buku ditemukan.</p>
    @if(request()->has('search') || request()->has('kategori'))
    <p class="text-gray-400 text-sm">Coba ubah kata kunci pencarian atau filter</p>
    @else
        <!-- TOMBOL TAMBAH HANYA UNTUK ADMIN & PEGAWAI -->
        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai'))
        <a href="{{ route('books.create') }}" class="text-secondary hover:text-opacity-80 font-medium mt-2 inline-block">
            <i class="fas fa-plus mr-1"></i>
            Tambah buku pertama
        </a>
        @elseif(!auth()->check())
        <p class="text-gray-400 text-sm">Silakan login untuk mengakses lebih banyak fitur</p>
        @endif
    @endif
</div>
@endif

<!-- INFO UNTUK GUEST -->
@if(!auth()->check() && count($books) > 0)
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full mr-4">
            <i class="fas fa-info-circle text-lg"></i>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-blue-800 mb-2">Ingin Meminjam Buku?</h3>
            <p class="text-blue-700 mb-3">
                Daftar sebagai mahasiswa untuk mengakses layanan peminjaman buku, memberikan review, 
                dan menikmati semua fitur perpustakaan digital.
            </p>
            <div class="flex space-x-3">
                <a href="{{ route('register') }}" class="bg-primary hover:bg-opacity-90 text-white px-6 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="bg-secondary hover:bg-opacity-90 text-white px-6 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection