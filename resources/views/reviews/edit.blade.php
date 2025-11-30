@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Review Buku</h1>
            <p class="text-gray-600 mb-6">Edit rating dan ulasan untuk buku: <strong>{{ $review->book->judul }}</strong></p>
    
            <form action="{{ route('reviews.update', $review) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-3">Rating</label>
                    <div class="flex space-x-1" id="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="text-3xl rating-star {{ $i <= old('rating', $review->rating) ? 'text-yellow-500' : 'text-gray-300' }}"
                                data-rating="{{ $i }}">
                            ★
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', $review->rating) }}">
                    @error('rating')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="mb-6">
                    <label for="ulasan" class="block text-gray-700 mb-2">Ulasan</label>
                    <textarea name="ulasan" id="ulasan" rows="6" 
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                              placeholder="Bagikan pengalaman Anda membaca buku ini...">{{ old('ulasan', $review->ulasan) }}</textarea>
                    @error('ulasan')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
    
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('books.show', $review->book) }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                        Batal
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">
                        Update Review
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating-input');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                stars.forEach(s => {
                    const starRating = s.getAttribute('data-rating');
                    if (starRating <= rating) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-500');
                    } else {
                        s.classList.remove('text-yellow-500');
                        s.classList.add('text-gray-300');
                    }
                });
            });
        });
    });
    </script>
</div>
@endsection