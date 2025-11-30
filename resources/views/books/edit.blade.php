@extends('layouts.admin')

@section('title', 'Edit Buku')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Edit Buku</h1>
        <p class="text-gray-600">Edit informasi buku "{{ $book->judul }}"</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
    
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
    
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku *</label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $book->judul) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               placeholder="Masukkan judul buku"
                               required>
                    </div>
    
                    <div>
                        <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis *</label>
                        <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $book->penulis) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               placeholder="Nama penulis buku"
                               required>
                    </div>
    
                    <div>
                        <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit *</label>
                        <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $book->penerbit) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               placeholder="Nama penerbit"
                               required>
                    </div>
    
                    <div>
                        <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit *</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               min="1900" max="{{ date('Y') }}" 
                               placeholder="{{ date('Y') }}"
                               required>
                    </div>
    
                    <div>
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                        <select name="kategori" id="kategori" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                required>
                            <option value="">Pilih Kategori</option>
                            <option value="Teknologi" {{ old('kategori', $book->kategori) == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="Komputer" {{ old('kategori', $book->kategori) == 'Komputer' ? 'selected' : '' }}>Komputer</option>
                            <option value="Database" {{ old('kategori', $book->kategori) == 'Database' ? 'selected' : '' }}>Database</option>
                            <option value="Sains" {{ old('kategori', $book->kategori) == 'Sains' ? 'selected' : '' }}>Sains</option>
                            <option value="Fiksi" {{ old('kategori', $book->kategori) == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                            <option value="Non-Fiksi" {{ old('kategori', $book->kategori) == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                            <option value="Pendidikan" {{ old('kategori', $book->kategori) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Bahasa" {{ old('kategori', $book->kategori) == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                        </select>
                    </div>
                </div>
    
                <div class="space-y-6">
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               placeholder="ISBN buku (opsional)">
                    </div>
    
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">Stok *</label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $book->stok) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               min="0" 
                               placeholder="Jumlah stok"
                               required>
                    </div>
    
                    <div>
                        <label for="max_peminjaman_hari" class="block text-sm font-medium text-gray-700 mb-2">Max Peminjaman (hari) *</label>
                        <input type="number" name="max_peminjaman_hari" id="max_peminjaman_hari" value="{{ old('max_peminjaman_hari', $book->max_peminjaman_hari) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               min="1" 
                               placeholder="Maksimal hari peminjaman"
                               required>
                    </div>
    
                    <div>
                        <label for="denda_per_hari" class="block text-sm font-medium text-gray-700 mb-2">Denda per Hari (Rp) *</label>
                        <input type="number" name="denda_per_hari" id="denda_per_hari" value="{{ old('denda_per_hari', $book->denda_per_hari) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               min="0" step="500" 
                               placeholder="Jumlah denda per hari"
                               required>
                    </div>
    
                    <div>
                        <label for="cover" class="block text-sm font-medium text-gray-700 mb-2">Cover Buku</label>
                        
                        @if($book->cover)
                        <div class="mb-3">
                            <p class="text-sm text-gray-600 mb-2">Cover Saat Ini:</p>
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->judul }}" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                        @endif
    
                        <input type="file" name="cover" id="cover" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-opacity-90"
                               accept="image/*">
                        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengubah cover</p>
                    </div>
    
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <input type="number" name="rating" id="rating" value="{{ old('rating', $book->rating) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                               min="0" max="5" step="0.1" 
                               placeholder="Rating buku">
                    </div>
                </div>
            </div>
    
            <div class="mt-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Buku</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                          placeholder="Deskripsi singkat tentang buku...">{{ old('deskripsi', $book->deskripsi) }}</textarea>
            </div>
    
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('books.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition duration-150 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit" 
                        class="bg-secondary hover:bg-opacity-90 text-white px-6 py-3 rounded-lg transition duration-150 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection