@extends('layouts.pegawai')

@section('title', 'Log Notifikasi Sistem')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-primary">Log Notifikasi Sistem</h1>
            <p class="text-gray-600">Monitor semua notifikasi yang dikirimkan sistem kepada pengguna</p>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Notifikasi</p>
                        <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-full">
                        <i class="fas fa-bell text-primary text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Hari Ini</p>
                        <p class="text-2xl font-bold text-primary">{{ $stats['today'] }}</p>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-3 rounded-full">
                        <i class="fas fa-calendar-day text-secondary text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Belum Dibaca</p>
                        <p class="text-2xl font-bold text-primary">{{ $stats['unread'] }}</p>
                    </div>
                    <div class="bg-accent bg-opacity-10 p-3 rounded-full">
                        <i class="fas fa-envelope text-accent text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Jenis Notifikasi</p>
                        <p class="text-2xl font-bold text-primary">{{ count($stats['types']) }}</p>
                    </div>
                    <div class="bg-green-500 bg-opacity-10 p-3 rounded-full">
                        <i class="fas fa-tags text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <form action="{{ route('pegawai.notifications.log') }}" method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pengguna/Isi</label>
                    <input type="text" name="search" placeholder="Cari nama pengguna atau isi notifikasi..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">Semua Jenis</option>
                        <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                        <option value="pengembalian" {{ request('type') == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        <option value="pengingat" {{ request('type') == 'pengingat' ? 'selected' : '' }}>Pengingat</option>
                        <option value="keterlambatan" {{ request('type') == 'keterlambatan' ? 'selected' : '' }}>Keterlambatan</option>
                    </select>
                </div>
    
                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">Semua Status</option>
                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                    </select>
                </div>
                
                <button type="submit" class="bg-primary hover:bg-opacity-90 text-white px-6 py-2 rounded-lg transition flex items-center h-[42px]">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
                
                @if(request()->hasAny(['search', 'type', 'status']))
                <a href="{{ route('pegawai.notifications.log') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center h-[42px]">
                    <i class="fas fa-times mr-2"></i>
                    Reset
                </a>
                @endif
            </form>
        </div>
    
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pesan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-secondary bg-opacity-20 text-secondary rounded-full flex items-center justify-center font-semibold text-xs">
                                    {{ substr($notification->user->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $notification->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $notification->user->role }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($notification->type === 'peminjaman') bg-green-100 text-green-800
                                @elseif($notification->type === 'pengembalian') bg-blue-100 text-blue-800
                                @elseif($notification->type === 'pengingat') bg-yellow-100 text-yellow-800
                                @elseif($notification->type === 'keterlambatan') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $notification->type }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $notification->title }}</div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md">{{ $notification->message }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $notification->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $notification->created_at->format('H:i') }}</div>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $notification->is_read ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                <i class="fas fa-{{ $notification->is_read ? 'check' : 'envelope' }} mr-1"></i>
                                {{ $notification->is_read ? 'Dibaca' : 'Belum Dibaca' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
    
        @if($notifications->isEmpty())
        <div class="text-center py-12 bg-white rounded-xl shadow-lg">
            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada data notifikasi</p>
            <p class="text-gray-400 text-sm">Semua notifikasi sistem akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection