<!-- resources/views/loans/index.blade.php -->
@extends(auth()->user()->role === 'mahasiswa' ? 'layouts.app' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin'))

@section('title', auth()->user()->role === 'mahasiswa' ? 'Riwayat Peminjaman' : 'Manajemen Peminjaman')

@section('content')
@if(auth()->user()->role === 'mahasiswa')
    <!-- HEADER UNTUK MAHASISWA -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Peminjaman</h1>
        <p class="text-gray-600">Lihat riwayat peminjaman dan status buku Anda</p>
    </div>
@elseif(auth()->user()->role === 'pegawai')
    <!-- HEADER UNTUK PEGAWAI -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Manajemen Peminjaman</h1>
        <p class="text-gray-600">Kelola peminjaman dan pengembalian buku</p>
    </div>
@else
    <!-- HEADER UNTUK ADMIN -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Manajemen Peminjaman</h1>
        <p class="text-gray-600">Monitor semua aktivitas peminjaman sistem</p>
    </div>
@endif

<!-- ... (bagian stats cards dan filter tetap sama) ... -->

<!-- TABEL PEMINJAMAN YANG DIPERBAIKI -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto"> <!-- ✅ TAMBAHKAN INI UNTUK SCROLL HORIZONTAL -->
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary text-white">
                <tr>
                    @if(in_array(auth()->user()->role, ['admin', 'pegawai']))
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Mahasiswa</th>
                    @endif
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Buku</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Pinjam</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Jatuh Tempo</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Denda</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($loans as $loan)
                <tr class="hover:bg-gray-50 transition duration-150">
                    @if(in_array(auth()->user()->role, ['admin', 'pegawai']))
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-secondary bg-opacity-20 text-secondary rounded-full flex items-center justify-center font-semibold text-xs">
                                {{ substr($loan->user->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-[120px]">{{ $loan->user->name }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-[120px]">{{ $loan->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    @endif
                    
                    <td class="px-3 py-3">
                        <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]">{{ $loan->book->judul }}</div>
                        <div class="text-xs text-gray-500 truncate max-w-[150px]">Oleh: {{ $loan->book->penulis }}</div>
                    </td>
                    
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $loan->tanggal_pinjam->format('d/m/Y') }}</div>
                    </td>
                    
                    <td class="px-3 py-3 whitespace-nowrap">
                        <div class="text-sm {{ $loan->isLate() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                            {{ $loan->tanggal_jatuh_tempo->format('d/m/Y') }}
                            @if($loan->isLate())
                                <div class="text-xs text-red-500">(+{{ $loan->daysLate() }}hr)</div>
                            @endif
                        </div>
                    </td>
                    
                    <td class="px-3 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $loan->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 
                               ($loan->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            @if($loan->status == 'dipinjam')
                            📖
                            @elseif($loan->status == 'dikembalikan')
                            ✅
                            @else
                            ⚠️
                            @endif
                            {{ $loan->status == 'dipinjam' ? 'Pinjam' : 
                               ($loan->status == 'dikembalikan' ? 'Kembali' : $loan->status) }}
                        </span>
                    </td>
                    
                    <td class="px-3 py-3 whitespace-nowrap">
                        @if($loan->denda > 0)
                        <div class="text-sm font-semibold text-red-600">
                            Rp{{ number_format($loan->denda, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500 {{ $loan->denda_lunas ? 'text-green-600' : 'text-red-600' }}">
                            {{ $loan->denda_lunas ? 'Lunas' : 'Belum' }}
                        </div>
                        @else
                        <div class="text-sm text-green-600">-</div>
                        @endif
                    </td>
                    
                    <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col space-y-1">
                            <!-- ACTION UNTUK MAHASISWA -->
                            @if(auth()->user()->role === 'mahasiswa')
                                @if($loan->status == 'dipinjam' && !$loan->isLate())
                                <form action="{{ route('loans.renew', $loan) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-800 text-xs flex items-center"
                                            onclick="return confirm('Perpanjang peminjaman?')">
                                        <i class="fas fa-redo mr-1"></i>
                                        Perpanjang
                                    </button>
                                </form>
                                @endif
                            @endif
                            
                            <!-- ACTION UNTUK PEGAWAI -->
                            @if(auth()->user()->role === 'pegawai' && $loan->status == 'dipinjam')
                            <form action="{{ route('loans.return', $loan) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-800 text-xs flex items-center"
                                        onclick="return confirm('Konfirmasi pengembalian buku: {{ $loan->book->judul }}?')">
                                    <i class="fas fa-check mr-1"></i>
                                    Kembalikan
                                </button>
                            </form>
                            @endif
                            
                            <!-- ACTION UNTUK ADMIN -->
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-xs flex items-center"
                                        onclick="return confirm('Hapus data peminjaman?')">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                            @endif

                            <!-- REVIEW ACTION -->
                            @if($loan->status == 'dikembalikan')
                                @if(!$loan->review && auth()->user()->role === 'mahasiswa')
                                <a href="{{ route('reviews.create', $loan) }}" class="text-yellow-600 hover:text-yellow-800 text-xs flex items-center">
                                    <i class="fas fa-star mr-1"></i>
                                    Review
                                </a>
                                @elseif($loan->review)
                                <a href="{{ route('reviews.edit', $loan->review) }}" class="text-green-600 hover:text-green-800 text-xs flex items-center">
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

@if(count($loans) === 0)
<div class="text-center py-12 bg-white rounded-xl shadow-lg">
    <i class="fas fa-book-open text-4xl text-gray-300 mb-4"></i>
    <p class="text-gray-500 text-lg">
        @if(auth()->user()->role === 'mahasiswa')
        Belum ada riwayat peminjaman.
        @else
        Tidak ada data peminjaman.
        @endif
    </p>
    @if(auth()->user()->role === 'mahasiswa')
    <a href="{{ route('books.index') }}" class="text-secondary hover:text-opacity-80 font-medium mt-2 inline-block">
        <i class="fas fa-plus mr-1"></i>
        Pinjam buku pertama
    </a>
    @endif
</div>
@endif

<!-- PAGINATION -->
@if($loans instanceof \Illuminate\Pagination\LengthAwarePaginator && $loans->hasPages())
<div class="mt-6">
    {{ $loans->links() }}
</div>
@endif
@endsection