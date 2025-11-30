@extends('layouts.admin')

@section('title', 'Semua Aktivitas - Perpustakaan Universitas Aksara')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-primary">Semua Aktivitas Sistem</h1>
                <p class="text-gray-600">Monitor semua transaksi peminjaman dan pengembalian buku</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- Tabel Aktivitas -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentActivities as $activity)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->user->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $activity->user->nim ?? $activity->user->email }}
                                        @if($activity->user->role === 'mahasiswa')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-2">Mahasiswa</span>
                                        @elseif($activity->user->role === 'pegawai')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">Pegawai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $activity->book->judul }}</div>
                            <div class="text-sm text-gray-500">{{ $activity->book->penulis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $activity->tanggal_pinjam->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $activity->is_late ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $activity->tanggal_jatuh_tempo->format('d M Y') }}
                                @if($activity->is_late)
                                <div class="text-xs text-red-500">(+{{ $activity->days_late }} hari)</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $activity->status == 'dipinjam' ? 'bg-blue-100 text-blue-800' : 
                                   ($activity->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($activity->status) }}
                            </span>
                            @if($activity->is_late)
                            <span class="ml-2 px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Terlambat
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-history text-3xl mb-3"></i>
                            <p class="text-lg">Tidak ada aktivitas</p>
                            <p class="text-sm">Belum ada transaksi peminjaman buku</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        @if($recentActivities->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $recentActivities->links() }}
        </div>
        @endif
    </div>
    
    <!-- Summary -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-blue-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $recentActivities->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-green-100 text-green-600 p-3 rounded-lg mr-4">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-green-600">Selesai</p>
                    <p class="text-2xl font-bold text-green-800">
                        {{ $recentActivities->where('status', 'dikembalikan')->count() }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="bg-orange-100 text-orange-600 p-3 rounded-lg mr-4">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-orange-600">Aktif</p>
                    <p class="text-2xl font-bold text-orange-800">
                        {{ $recentActivities->where('status', 'dipinjam')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection