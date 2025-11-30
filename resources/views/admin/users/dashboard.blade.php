@extends('layouts.admin')

@section('title', 'Dashboard Admin - Perpustakaan Universitas Aksara')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Dashboard Admin</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! Kelola sistem perpustakaan dari sini.</p>
    </div>
    
    <!-- Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $mahasiswaCount }} mahasiswa</span> • 
                        <span class="text-blue-600">{{ $pegawaiCount }} pegawai</span>
                    </p>
                </div>
                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
    
        <!-- Total Books -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Buku</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalBooks }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $availableBooks }} tersedia</span> • 
                        <span class="text-red-600">{{ $outOfStockBooks }} habis</span>
                    </p>
                </div>
                <div class="bg-green-100 text-green-600 p-3 rounded-lg">
                    <i class="fas fa-book text-xl"></i>
                </div>
            </div>
        </div>
    
        <!-- Active Loans -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Peminjaman Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $activeLoans }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-red-600">{{ $overdueLoans }} terlambat</span> • 
                        <span class="text-green-600">{{ $completedLoans }} selesai</span>
                    </p>
                </div>
                <div class="bg-orange-100 text-orange-600 p-3 rounded-lg">
                    <i class="fas fa-exchange-alt text-xl"></i>
                </div>
            </div>
        </div>
    
        <!-- Pending Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Perlu Tindakan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $pendingActions }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Peminjaman terlambat</p>
                </div>
                <div class="bg-purple-100 text-purple-600 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-primary mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.users.index') }}" class="bg-primary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                    <i class="fas fa-users text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Manajemen User</p>
                </a>
                
                <a href="{{ route('books.index') }}" class="bg-secondary text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                    <i class="fas fa-book-medical text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Manajemen Buku</p>
                </a>
                
                
                <a href="{{ route('admin.users.activities') }}" class="bg-purple-500 text-white p-4 rounded-lg text-center hover:bg-opacity-90 transition">
                    <i class="fas fa-chart-bar text-2xl mb-2"></i>
                    <p class="font-semibold text-sm">Laporan</p>
                </a>
            </div>
        </div>
    
        <!-- Peminjaman Terlambat -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-primary">Peminjaman Terlambat</h3>
                <span class="bg-red-100 text-red-800 text-sm px-3 py-1 rounded-full font-semibold">
                    {{ $overdueLoans }} buku
                </span>
            </div>
            
            @if($overdueLoansList->count() > 0)
            <div class="space-y-3">
                @foreach($overdueLoansList->take(5) as $loan)
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 text-sm">{{ $loan->user->name ?? 'User' }}</h4>
                        <p class="text-xs text-gray-600">{{ $loan->book->judul ?? 'Buku' }}</p>
                        <p class="text-xs text-red-600 font-semibold mt-1">
                            Terlambat {{ $loan->days_late }} hari • Denda: Rp {{ number_format($loan->calculateDenda(), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-3xl text-green-400 mb-2"></i>
                <p class="text-gray-500">Tidak ada peminjaman terlambat</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Aktivitas Terbaru -->
    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-primary">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.users.activities') }}" class="text-secondary hover:text-primary text-sm font-semibold">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @if($recentActivities->count() > 0)
                        @foreach($recentActivities->take(5) as $activity)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'User' }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->user->nim ?? $activity->user->email }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $activity->book->judul ?? 'Buku' }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->book->penulis ?? 'Penulis' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $activity->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $activity->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 
                                       ($activity->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-history text-2xl mb-2"></i>
                                <p>Tidak ada aktivitas terbaru</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection