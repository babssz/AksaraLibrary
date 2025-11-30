@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Semua Review Buku</h1>
</div>

@if(empty($reviews) || (is_object($reviews) && $reviews->isEmpty()))
<div class="text-center py-12 bg-white rounded-lg shadow">
    <i class="fas fa-comment-slash text-4xl text-gray-300 mb-4"></i>
    <p class="text-gray-500 text-lg">Belum ada review.</p>
    <p class="text-gray-400 text-sm mt-2">Review dari pengguna akan muncul di sini</p>
</div>
@else
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ulasan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($reviews as $review)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $review->user->email ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $review->book->judul ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= ($review->rating ?? 0) ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                        @endfor
                        <span class="ml-1 text-sm text-gray-600">({{ $review->rating ?? 0 }}/5)</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 max-w-xs truncate">
                        {{ $review->ulasan ?: '-' }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Hapus review ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection