@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'Katalog Buku' : 'Manajemen Buku') : 'Katalog Buku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="{{ !auth()->check() ? '' : 'min-h-screen bg-white-50' }}">
        <div class="{{ !auth()->check() ? '' : 'container mx-auto px-4 py-8' }}">
            
            <div class="mb-8">
                @if(auth()->check() && auth()->user()->role === 'mahasiswa')
                    <div class="text-center mb-8">
                        <h1 class="text-4xl font-bold text-primary mb-4">Katalog Buku</h1>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">Temukan buku yang Anda butuhkan untuk mendukung studi dan pengembangan diri</p>
                    </div>
                @elseif(!auth()->check())
                    <div class="text-center mb-8">
                        <h1 class="text-4xl font-bold text-primary mb-4">Katalog Buku Perpustakaan</h1>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">Jelajahi koleksi buku perpustakaan Universitas Aksara</p>
                        
                        <div class="flex justify-center space-x-4 mt-6">
                            <a href="{{ route('login') }}" 
                               class="bg-secondary hover:opacity-90 text-white px-8 py-3 rounded-lg transition flex items-center shadow-md font-semibold">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-primary hover:opacity-90 text-white px-8 py-3 rounded-lg transition flex items-center shadow-md font-semibold">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar
                            </a>
                        </div>
                        
                        <div class="bg-blue-50 border-2 border-primary rounded-xl p-6 mt-6 max-w-4xl mx-auto">
                            <div class="flex items-center justify-center">
                                <i class="fas fa-info-circle text-primary text-2xl mr-4"></i>
                                <div class="text-left">
                                    <p class="text-primary font-semibold text-lg">Login untuk meminjam buku</p>
                                    <p class="text-primary">Daftar akun mahasiswa untuk mengakses layanan peminjaman buku</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h1 class="text-3xl font-bold text-primary mb-2">Manajemen Buku</h1>
                            <p class="text-gray-600">Kelola semua buku dalam sistem perpustakaan</p>
                        </div>
                        <a href="{{ route('books.create') }}" 
                           class="bg-secondary hover:opacity-90 text-white px-6 py-3 rounded-lg transition flex items-center shadow-lg font-semibold">
                            <i class="fas fa-book-medical mr-2"></i>
                            Tambah Buku
                        </a>
                    </div>
                @endif
            </div>
    
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-l-4 border-primary">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex flex-wrap items-center gap-6 mb-4 md:mb-0">
                        <div class="flex items-center">
                            <div class="bg-primary bg-opacity-10 w-12 h-12 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-book text-primary text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Buku</p>
                                <p class="text-2xl font-bold text-primary">{{ count($books) }}</p>
                            </div>
                        </div>
                        
                        @if(request()->has('search'))
                        <div class="flex items-center">
                            <div class="bg-secondary bg-opacity-10 w-12 h-12 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-search text-secondary text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Hasil Pencarian</p>
                                <p class="text-lg font-semibold text-gray-700">"{{ request('search') }}"</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(request()->has('kategori'))
                        <div class="flex items-center">
                            <div class="bg-accent bg-opacity-10 w-12 h-12 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-filter text-accent text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kategori</p>
                                <p class="text-lg font-semibold text-gray-700">{{ request('kategori') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @if(request()->has('search') || request()->has('kategori'))
                    <a href="{{ route('books.index') }}" 
                       class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition flex items-center font-semibold shadow-md">
                        <i class="fas fa-times mr-2"></i>
                        Reset Filter
                    </a>
                    @endif
                </div>
            </div>
    
            <div class="bg-white rounded-xl shadow-lg mb-8 border-t-4 border-primary">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-primary mb-6 flex items-center">
                        <i class="fas fa-search mr-3 text-primary"></i>
                        Cari & Filter Buku
                    </h3>
                    <form action="{{ route('books.index') }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-12 md:gap-4">
                        <div class="md:col-span-7">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-keyboard mr-1 text-primary"></i>Kata Kunci
                            </label>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari judul, penulis, penerbit, atau ISBN..." 
                                   value="{{ request('search') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition">
                        </div>
                        
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-1 text-primary"></i>Kategori
                            </label>
                            <select name="kategori" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition">
                                <option value="">Semua Kategori</option>
                                @php
                                    $categories = \App\Models\Book::distinct()->whereNotNull('kategori')->pluck('kategori');
                                @endphp
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2 flex items-end">
                            <button type="submit" 
                                    class="w-full bg-primary hover:opacity-90 text-white px-6 py-3 rounded-lg transition flex items-center justify-center font-semibold shadow-md">
                                <i class="fas fa-search mr-2"></i>
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    
            @if(count($books) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @foreach($books as $book)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 border-t-4 border-secondary overflow-hidden group transform hover:-translate-y-1">
                    <div class="relative overflow-hidden h-56">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" 
                                 alt="{{ $book->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                                <i class="fas fa-book text-5xl text-primary opacity-40"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold shadow-lg {{ $book->isAvailable() ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                <i class="fas fa-{{ $book->isAvailable() ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                {{ $book->isAvailable() ? 'Tersedia' : 'Dipinjam' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-primary mb-2 line-clamp-2 hover:text-secondary transition-colors min-h-[3.5rem]">
                            {{ $book->judul }}
                        </h3>
                        
                        <div class="space-y-1 mb-3">
                            <p class="text-gray-600 text-sm flex items-center">
                                <i class="fas fa-user-edit text-secondary mr-2 w-4"></i>
                                <span class="truncate">{{ $book->penulis }}</span>
                            </p>
                            <p class="text-gray-500 text-xs flex items-center">
                                <i class="fas fa-building text-gray-400 mr-2 w-4"></i>
                                <span class="truncate">{{ $book->penerbit }}</span>
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4 text-xs text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $book->tahun_terbit }}
                            </span>
                            <span class="px-2 py-1 bg-blue-50 text-primary rounded-full font-medium">
                                {{ $book->kategori }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
                                <i class="fas fa-star text-yellow-500 text-sm mr-1"></i>
                                <span class="text-sm font-bold text-gray-700">{{ number_format($book->rating, 1) }}</span>
                            </div>
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-box mr-1"></i>
                                Stok: <span class="font-semibold">{{ $book->stok }}</span>
                            </span>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('books.show', $book) }}" 
                               class="flex-1 text-center bg-primary hover:opacity-90 text-white py-2 rounded-lg text-sm font-semibold transition shadow-md">
                                <i class="fas fa-eye mr-1"></i>
                                Detail
                            </a>
                            
                            @if(!auth()->check())
                                @if($book->isAvailable())
                                <a href="{{ route('login') }}" 
                                   class="flex-1 text-center bg-secondary hover:opacity-90 text-white py-2 rounded-lg text-sm font-semibold transition shadow-md">
                                    <i class="fas fa-sign-in-alt mr-1"></i>
                                    Login
                                </a>
                                @endif
                            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai')
                                <a href="{{ route('books.edit', $book->id) }}" 
                                   class="flex-1 text-center bg-secondary hover:opacity-90 text-white py-2 rounded-lg text-sm font-semibold transition shadow-md">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
    
            @if(count($books) === 0)
            <div class="bg-white rounded-xl shadow-lg p-12 text-center mb-12">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Buku Ditemukan</h3>
                    
                    @if(request()->has('search') || request()->has('kategori'))
                        <p class="text-gray-500 mb-4">Tidak ada buku yang sesuai dengan pencarian Anda.</p>
                        <a href="{{ route('books.index') }}" 
                           class="inline-flex items-center bg-primary hover:opacity-90 text-white px-6 py-3 rounded-lg transition font-semibold shadow-md">
                            <i class="fas fa-redo mr-2"></i>
                            Lihat Semua Buku
                        </a>
                    @else
                        @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pegawai'))
                            <p class="text-gray-500 mb-4">Belum ada buku dalam sistem. Tambahkan buku pertama Anda!</p>
                            <a href="{{ route('books.create') }}" 
                               class="inline-flex items-center bg-secondary hover:opacity-90 text-white px-6 py-3 rounded-lg transition font-semibold shadow-md">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Buku Pertama
                            </a>
                        @else
                            <p class="text-gray-500">Koleksi buku sedang dalam pembaruan.</p>
                        @endif
                    @endif
                </div>
            </div>
            @endif
    
        </div>
    </div>
    
    @if(!auth()->check())
        </div>
        </div>
    @endif
</div>

@endsection