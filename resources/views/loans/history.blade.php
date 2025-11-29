<!-- resources/views/loans/history.blade.php -->
@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Peminjaman Saya</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pinjam</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kembali</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($loans as $loan)
                    <tr>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $loan->book->judul }}</div>
                            <div class="text-xs text-gray-500">by {{ $loan->book->penulis }}</div>
                            @if($loan->denda > 0)
                            <div class="text-xs text-red-600">
                                Denda: Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                @if($loan->denda_lunas)
                                <span class="text-green-600">(Lunas)</span>
                                @else
                                <span class="text-red-600">(Belum Lunas)</span>
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $loan->tanggal_pinjam->format('d M Y') }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $loan->tanggal_jatuh_tempo->format('d M Y') }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $loan->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 
                                   ($loan->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col space-y-2">
                                @if($loan->status == 'dipinjam' && $loan->canBeRenewed())
                                <form action="{{ route('loans.renew', $loan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900 text-xs flex items-center"
                                            onclick="return confirm('Perpanjang peminjaman?')">
                                        <i class="fas fa-redo-alt mr-1"></i>
                                        Perpanjang
                                    </button>
                                </form>
                                @endif
                                
                                @if($loan->status == 'dikembalikan')
                                    @if(!$loan->review)
                                    <a href="{{ route('reviews.create', $loan) }}" class="text-blue-600 hover:text-blue-900 text-xs flex items-center">
                                        <i class="fas fa-star mr-1"></i>
                                        Beri Review
                                    </a>
                                    @else
                                    <a href="{{ route('reviews.edit', $loan->review) }}" class="text-green-600 hover:text-green-900 text-xs flex items-center">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit Review
                                    </a>
                                    @endif
                                @endif

                            
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($loans->isEmpty())
    <div class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg">Belum ada riwayat peminjaman</p>
        <a href="{{ route('books.index') }}" class="inline-block mt-4 bg-secondary text-white px-4 py-2 rounded-lg text-sm hover:bg-opacity-90 transition">
            <i class="fas fa-book mr-2"></i>Cari Buku untuk Dipinjam
        </a>
    </div>
    @endif
</div>
@endsection