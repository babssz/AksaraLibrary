@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', 'Beranda - Perpustakaan Universitas Aksara')

@section('content')



<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-primary text-white py-20 mb-12 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 text-white">SELAMAT DATANG DI</h1>
                <h2 class="text-2xl md:text-4xl font-bold text-yellow-400 mb-4">PERPUSTAKAAN UNIVERSITAS AKSARA</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto text-white">Portal digital untuk akses koleksi perpustakaan, peminjaman buku, dan layanan akademik terpadu</p>
                <a href="{{ route('books.index') }}" class="bg-accent text-white px-8 py-4 rounded-lg font-bold text-lg hover:opacity-90 transition inline-block shadow-lg">
                    <i class="fas fa-search mr-2"></i>Jelajahi Koleksi
                </a>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 pb-16">
        <!-- SEARCH & FILTER -->
        <div class="bg-white p-6 rounded-xl shadow-lg mb-12 border-t-4 border-secondary">
            <form action="{{ route('books.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-primary"></i>Cari Buku
                    </label>
                    <input type="text" name="search" placeholder="Cari judul, penulis, atau kategori..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition">
                </div>
                
                <div class="w-full md:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-filter mr-1 text-primary"></i>Kategori
                    </label>
                    <select name="kategori" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition">
                        <option value="">Semua Kategori</option>
                        @php
                            $categories = \App\Models\Book::distinct()->whereNotNull('kategori')->pluck('kategori');
                        @endphp
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('kategori') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="bg-secondary hover:opacity-90 text-white px-8 py-3 rounded-lg transition flex items-center font-semibold w-full md:w-auto justify-center shadow-md">
                    <i class="fas fa-search mr-2"></i>
                    Cari Buku
                </button>
                
                @if(request()->has('search') || request()->has('kategori'))
                <a href="{{ route('welcome') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition flex items-center font-semibold w-full md:w-auto justify-center shadow-md">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Buku Terbaru -->
        <section class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-bold text-primary flex items-center">
                    <i class="fas fa-sparkles mr-3 text-yellow-500"></i>Buku Terbaru
                </h3>
                <a href="{{ route('books.index') }}" class="text-secondary hover:text-primary font-semibold flex items-center group">
                    Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $newBooks = \App\Models\Book::withAvg('reviews', 'rating')
                        ->withCount('reviews')
                        ->where('stok', '>', 0)
                        ->latest()
                        ->take(4)
                        ->get();
                @endphp
                
                @foreach($newBooks as $book)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group border-t-4 border-accent">
                    <div class="h-64 bg-gray-200 relative overflow-hidden">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-book text-primary text-5xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white rounded-full px-3 py-1 text-xs font-bold shadow-lg">
                            <i class="fas fa-box text-primary mr-1"></i>{{ $book->stok }}
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h4 class="font-bold text-primary text-lg mb-2 line-clamp-2 min-h-[3.5rem]">{{ $book->judul }}</h4>
                        <p class="text-gray-600 text-sm mb-3"><i class="fas fa-user-edit mr-1"></i>{{ $book->penulis }}</p>
                        
                        @if($book->reviews_avg_rating)
                        <div class="flex items-center mb-4">
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($book->reviews_avg_rating) ? 'text-yellow-500' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 ml-2">({{ $book->reviews_count }})</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500"><i class="fas fa-calendar mr-1"></i>{{ $book->tahun_terbit }}</span>
                            <a href="{{ route('books.show', $book) }}" 
                               class="bg-secondary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition shadow-md">
                                <i class="fas fa-info-circle mr-1"></i>Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($newBooks->isEmpty())
                <div class="col-span-4 text-center py-12">
                    <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada buku tersedia</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Buku Populer -->
        <section class="mb-16">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-bold text-primary flex items-center">
                    <i class="fas fa-fire mr-3 text-orange-500"></i>Buku Populer
                </h3>
                <a href="{{ route('books.index') }}?sort=popular" class="text-secondary hover:text-primary font-semibold flex items-center group">
                    Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $popularBooks = \App\Models\Book::withAvg('reviews', 'rating')
                        ->withCount('reviews')
                        ->where('stok', '>', 0)
                        ->orderBy('reviews_avg_rating', 'desc')
                        ->orderBy('reviews_count', 'desc')
                        ->take(4)
                        ->get();
                @endphp
                
                @foreach($popularBooks as $book)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group border-t-4 border-secondary">
                    <div class="h-64 bg-gray-200 relative overflow-hidden">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full bg-blue-50 flex items-center justify-center">
                                <i class="fas fa-book text-secondary text-5xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-accent text-white rounded-full px-3 py-1 text-xs font-bold shadow-lg">
                            ⭐ {{ number_format($book->reviews_avg_rating ?? 0, 1) }}
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h4 class="font-bold text-primary text-lg mb-2 line-clamp-2 min-h-[3.5rem]">{{ $book->judul }}</h4>
                        <p class="text-gray-600 text-sm mb-3"><i class="fas fa-user-edit mr-1"></i>{{ $book->penulis }}</p>
                        
                        <div class="flex items-center mb-4">
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($book->reviews_avg_rating) ? 'text-yellow-500' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 ml-2">({{ $book->reviews_count }})</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500"><i class="fas fa-box mr-1"></i>Stok: {{ $book->stok }}</span>
                            <a href="{{ route('books.show', $book) }}" 
                               class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition shadow-md">
                                <i class="fas fa-hand-holding mr-1"></i>Pinjam
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                
                @if($popularBooks->isEmpty())
                <div class="col-span-4 text-center py-12">
                    <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada buku dengan rating</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Statistik -->
        <section class="bg-primary text-white rounded-2xl p-10 mb-16 shadow-xl">
            <h3 class="text-3xl font-bold text-center mb-8 text-yellow-400">Statistik Perpustakaan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @php
                    $totalBooks = \App\Models\Book::count();
                    $totalUsers = \App\Models\User::count();
                    $totalLoans = \App\Models\Loan::where('status', 'active')->count();
                    $totalReviews = \App\Models\Review::count();
                @endphp
                
                <div class="text-center">
                    <div class="bg-white bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-400">
                        <i class="fas fa-book text-4xl text-yellow-400"></i>
                    </div>
                    <div class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($totalBooks) }}+</div>
                    <div class="text-sm text-white">Total Koleksi Buku</div>
                </div>
                <div class="text-center">
                    <div class="bg-white bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-400">
                        <i class="fas fa-users text-4xl text-yellow-400"></i>
                    </div>
                    <div class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($totalUsers) }}+</div>
                    <div class="text-sm text-white">Anggota Aktif</div>
                </div>
                <div class="text-center">
                    <div class="bg-white bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-400">
                        <i class="fas fa-hand-holding text-4xl text-yellow-400"></i>
                    </div>
                    <div class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($totalLoans) }}+</div>
                    <div class="text-sm text-white">Sedang Dipinjam</div>
                </div>
                <div class="text-center">
                    <div class="bg-white bg-opacity-10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-400">
                        <i class="fas fa-star text-4xl text-yellow-400"></i>
                    </div>
                    <div class="text-4xl font-bold text-yellow-400 mb-2">{{ number_format($totalReviews) }}+</div>
                    <div class="text-sm text-white">Review & Rating</div>
                </div>
            </div>
        </section>

        <!-- Layanan & Bantuan -->
        <section class="mb-16">
            <h3 class="text-3xl font-bold text-primary mb-8 text-center">Layanan & Bantuan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition-all duration-300 border-t-4 border-primary transform hover:-translate-y-2">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-primary mb-3">Peminjaman Buku</h4>
                    <p class="text-gray-600">Pinjam buku fisik dengan mudah melalui sistem online</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition-all duration-300 border-t-4 border-secondary transform hover:-translate-y-2">
                    <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-primary mb-3">Pencarian Cerdas</h4>
                    <p class="text-gray-600">Temukan buku dengan pencarian berdasarkan judul, penulis, kategori</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-2xl transition-all duration-300 border-t-4 border-accent transform hover:-translate-y-2">
                    <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-primary mb-3">Review & Rating</h4>
                    <p class="text-gray-600">Berikan ulasan dan rating untuk buku yang telah dibaca</p>
                </div>
            </div>
        </section>
    </div>
</div>


@endsection