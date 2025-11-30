@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', 'Beri Review')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Beri Review untuk Buku</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-lg">{{ $loan->book->judul }}</h3>
            <p class="text-gray-600">by {{ $loan->book->penulis }}</p>
            <p class="text-sm text-gray-500">Dipinjam: {{ $loan->tanggal_pinjam->format('d M Y') }}</p>
        </div>

        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="loan_id" value="{{ $loan->id }}">

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <div class="flex space-x-1" id="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="text-2xl text-gray-300 hover:text-yellow-400 rating-star" data-rating="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-value" value="0" required>
                <p class="text-sm text-gray-500 mt-1">Pilih rating dari 1-5 bintang</p>
                @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="ulasan" class="block text-sm font-medium text-gray-700 mb-2">
                    Ulasan (opsional)
                </label>
                <textarea name="ulasan" id="ulasan" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent"
                          placeholder="Bagikan pengalaman Anda membaca buku ini...">{{ old('ulasan') }}</textarea>
                @error('ulasan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('books.show', $loan->book_id) }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-secondary text-white px-4 py-2 rounded hover:bg-opacity-90 transition">
                    Kirim Review
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating-value').value = rating;
            
            document.querySelectorAll('.rating-star').forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-500');
                } else {
                    s.classList.remove('text-yellow-500');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });
</script>
@endsection