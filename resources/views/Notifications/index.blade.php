@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-primary">Notifikasi</h1>
    <p class="text-gray-600">Kelola notifikasi sistem Anda</p>
</div>

<!-- @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif -->

<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-xl font-semibold text-primary">Daftar Notifikasi</h2>
        <p class="text-gray-600">Total {{ $notifications->total() }} notifikasi</p>
    </div>
    
    <div class="flex space-x-3">
        @if($notifications->count() > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="bg-secondary hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-check-double mr-2"></i>
                Tandai Semua Dibaca
            </button>
        </form>
        
        <form action="{{ route('notifications.clear-all') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition flex items-center"
                    onclick="return confirm('Yakin hapus semua notifikasi?')">
                <i class="fas fa-trash mr-2"></i>
                Hapus Semua
            </button>
        </form>
        @endif
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    @if($notifications->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($notifications as $notification)
            <div class="p-6 hover:bg-gray-50 transition duration-150 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            @if(!$notification->is_read)
                            <span class="bg-primary text-white text-xs px-2 py-1 rounded-full mr-3">BARU</span>
                            @endif
                            <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <!-- ✅ PERBAIKI: Tampilkan title dan message dari model -->
                        <h3 class="font-semibold text-gray-800 mb-1">{{ $notification->title }}</h3>
                        <p class="text-gray-600">{{ $notification->message }}</p>
                        
                        <div class="mt-2">
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                @if($notification->type === 'peminjaman') bg-green-100 text-green-800
                                @elseif($notification->type === 'pengembalian') bg-blue-100 text-blue-800
                                @elseif($notification->type === 'pengingat') bg-yellow-100 text-yellow-800
                                @elseif($notification->type === 'keterlambatan') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $notification->type }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 ml-4">
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm flex items-center">
                                <i class="fas fa-check mr-1"></i>
                                Tandai Dibaca
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm flex items-center"
                                    onclick="return confirm('Yakin hapus notifikasi ini?')">
                                <i class="fas fa-trash mr-1"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-12">
            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Tidak ada notifikasi</p>
            <p class="text-gray-400 text-sm">Semua notifikasi akan muncul di sini</p>
        </div>
    @endif
</div>
@endsection