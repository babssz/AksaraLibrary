<!-- resources/views/books/show.blade.php -->
@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')


@section('title', $book->judul)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3 p-8">
                    @if($book->cover)
                    <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}" 
                         class="w-full rounded-lg shadow-lg">
                    @else
                    <div class="w-full h-80 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-6xl text-primary opacity-40"></i>
                    </div>
                    @endif
                </div>
                
                <div class="md:w-2/3 p-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $book->judul }}</h1>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Penulis:</span>
                            <span class="text-gray-600">{{ $book->penulis }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Penerbit:</span>
                            <span class="text-gray-600">{{ $book->penerbit }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Tahun Terbit:</span>
                            <span class="text-gray-600">{{ $book->tahun_terbit }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Kategori:</span>
                            <span class="px-3 py-1 bg-secondary bg-opacity-20 text-secondary rounded-full text-sm">
                                {{ $book->kategori }}
                            </span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">ISBN:</span>
                            <span class="text-gray-600">{{ $book->isbn ?? '-' }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Stok:</span>
                            <span class="text-gray-600">{{ $book->stok }} buku</span>
                        </div>
                        
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-700 w-32">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $book->isAvailable() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $book->isAvailable() ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>
                    </div>
    
                    @if($book->deskripsi)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $book->deskripsi }}</p>
                    </div>
                    @endif
    
                    <div class="flex space-x-4">
                        @auth
                            @if(auth()->user()->isMahasiswa() && $book->isAvailable())
                            <form action="{{ route('books.loan', $book) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="bg-secondary hover:bg-opacity-90 text-white px-6 py-3 rounded-lg transition flex items-center">
                                    <i class="fas fa-hand-holding mr-2"></i>
                                    Pinjam Buku
                                </button>
                            </form>
                            @endif
                            
                            @if(auth()->user()->isAdmin() || auth()->user()->isPegawai())
                            <a href="{{ route('books.edit', $book) }}" 
                            class="bg-accent hover:bg-opacity-90 text-primary px-6 py-3 rounded-lg transition flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Buku
                            </a>
                            @endif
                        @else
                        <a href="{{ route('login') }}" 
                        class="bg-secondary hover:bg-opacity-90 text-white px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login untuk Meminjam
                        </a>
                        @endauth
                        
                        <a href="{{ route('books.index') }}" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="mt-8 bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Ulasan Pembaca</h2>
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-gray-800 mr-2">{{ number_format($book->rating, 1) }}</span>
                    <div class="flex">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= floor($book->rating) ? 'text-yellow-500' : 'text-gray-300' }} text-xl">★</span>
                        @endfor
                    </div>
                    <span class="text-gray-600 ml-2">({{ $reviews->count() }} ulasan)</span>
                </div>
            </div>
            
            @auth
                @if(auth()->user()->isMahasiswa())
                    @php
                        $userLoan = auth()->user()->loans()
                            ->where('book_id', $book->id)
                            ->where('status', 'dikembalikan')
                            ->whereDoesntHave('review')
                            ->first();
                    @endphp
                    @if($userLoan)
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3 text-lg"></i>
                            <p class="text-blue-800">Anda sudah mengembalikan buku ini. 
                                <a href="{{ route('reviews.create', $userLoan) }}" class="font-semibold underline hover:text-blue-900">
                                    Beri review sekarang
                                </a>
                            </p>
                        </div>
                    </div>
                    @endif
                @endif
            @endauth
            
            @if($reviews->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-comment-slash text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada ulasan untuk buku ini.</p>
                <p class="text-gray-400 text-sm">Jadilah yang pertama memberikan ulasan</p>
            </div>
            @else
            <div class="space-y-6">
                @foreach($reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-0">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                @endfor
                                <span class="text-sm text-gray-500 ml-2">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</div>
                    </div>
                    
                    @if($review->ulasan)
                    <p class="text-gray-700 leading-relaxed">{{ $review->ulasan }}</p>
                    @else
                    <p class="text-gray-500 italic">Tidak ada ulasan teks</p>
                    @endif
                    
                    @auth
                        @if(auth()->id() == $review->user_id || auth()->user()->isAdmin())
                        <div class="mt-3 flex space-x-4">
                            @if(auth()->id() == $review->user_id)
                            <a href="{{ route('reviews.edit', $review) }}" 
                               class="text-secondary hover:text-opacity-80 text-sm flex items-center transition-colors">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            @endif
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm flex items-center transition-colors"
                                        onclick="return confirm('Hapus review ini?')">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                        @endif
                    @endauth
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection