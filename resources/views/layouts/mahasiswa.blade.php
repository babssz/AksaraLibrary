<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa - @yield('title', 'Dashboard') - Perpustakaan Universitas Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                            primary: '#1B3C53',      
                            secondary: '#234C6A',    
                            accent: '#456882',       
                            background: '#E3E3E3',   
                            danger: '#DC2626',       
                            success: '#16A34A',      
                            warning: '#F59E0B',      
                            info: '#3B82F6',         
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background min-h-screen">
<header class="bg-primary text-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-white text-primary px-3 py-1 rounded-lg font-bold text-lg">
                    UA
                </div>
                <div>
                    <h1 class="text-xl font-bold">UNIVERSITAS AKSARA</h1>
                    <p class="text-accent text-sm">MAHASISWA PANEL</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                @php
                    $unreadCount = 0;
                    try {
                        if (auth()->check() && \Schema::hasTable('notifications')) {
                            $unreadCount = auth()->user()->unreadNotifications->count();
                        }
                    } catch (Exception $e) {
                        $unreadCount = 0;
                    }
                @endphp
                
                <a href="{{ route('notifications.index') }}" class="relative text-white hover:text-yellow-400 transition">
                    <i class="fas fa-bell text-lg"></i>
                    @if($unreadCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center animate-pulse">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </a>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-white hover:text-yellow-400 transition">
                        <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                            <i class="fas fa-user-graduate text-white text-sm"></i>
                        </div>
                        <span>Mahasiswa: {{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-gray-500 text-xs">{{ auth()->user()->email }}</div>
                            @if(auth()->user()->nim)
                            <div class="text-gray-500 text-xs">NIM: {{ auth()->user()->nim }}</div>
                            @endif
                            <div class="text-gray-500 text-xs">Role: Mahasiswa</div>
                        </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-edit text-primary text-sm"></i>
                                <span>Edit Profile</span>
                            </a>
                        
                        <div class="border-t my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center space-x-2 px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-sign-out-alt text-primary"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="bg-secondary text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex space-x-8 py-3">
            <a href="{{ url('/') }}" class="hover:text-yellow-400 transition flex items-center space-x-2 font-semibold {{ request()->is('/') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white' }}">
                <span>Beranda</span>
            </a>
            <a href="{{ route('books.index') }}" class="hover:text-yellow-400 transition flex items-center space-x-2 font-semibold {{ request()->is('books*') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white' }}">
                <span>Koleksi Buku</span>
            </a>
            <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-yellow-400 transition flex items-center space-x-2 font-semibold {{ request()->is('mahasiswa*') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white' }}">
                <span>Dashboard</span>
            </a>
        </div>
    </div>
    </nav>
</header>


        <main>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif
    
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif
    
            @yield('content')
        </main>
 
   
<footer class="bg-primary text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-white text-primary px-3 py-2 rounded-lg font-bold text-lg shadow-md">
                        UA
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-white">UNIVERSITAS AKSARA</h3>
                        <p class="text-yellow-400 text-xs font-semibold">Perpustakaan Digital</p>
                    </div>
                </div>
                <p class="text-sm text-gray-300 leading-relaxed">
                    Portal perpustakaan digital Universitas Aksara yang menyediakan akses mudah ke koleksi buku dan layanan peminjaman online.
                </p>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4 text-yellow-400">Link Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-yellow-400 transition flex items-center text-white">
                            <i class="fas fa-chevron-right mr-2 text-xs"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('books.index') }}" class="hover:text-yellow-400 transition flex items-center text-white">
                            <i class="fas fa-chevron-right mr-2 text-xs"></i>Koleksi Buku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-yellow-400 transition flex items-center text-white">
                            <i class="fas fa-chevron-right mr-2 text-xs"></i>Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="hover:text-yellow-400 transition flex items-center text-white">
                            <i class="fas fa-chevron-right mr-2 text-xs"></i>Register
                        </a>
                    </li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4 text-yellow-400">Kontak</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-yellow-400"></i>
                        <span class="text-white">Jl. Pendidikan No. 123<br>Makassar, Sulawesi Selatan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone mt-1 mr-3 text-yellow-400"></i>
                        <span class="text-white">(0411) 123-4567</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mt-1 mr-3 text-yellow-400"></i>
                        <span class="text-white">perpustakaan@aksara.ac.id</span>
                    </li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold text-lg mb-4 text-yellow-400">Jam Operasional</h4>
                <ul class="space-y-2 text-sm text-white">
                    <li class="flex justify-between">
                        <span>Senin - Jumat</span>
                        <span class="font-semibold">08:00 - 17:00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sabtu</span>
                        <span class="font-semibold">08:00 - 14:00</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Minggu</span>
                        <span class="font-semibold text-yellow-400">Tutup</span>
                    </li>
                </ul>
                <div class="mt-4">
                    <h5 class="font-semibold mb-2 text-yellow-400">Ikuti Kami</h5>
                    <div class="flex space-x-3">
                        <a href="#" class="bg-white bg-opacity-10 hover:bg-yellow-400 hover:bg-opacity-100 hover:text-primary w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-white bg-opacity-10 hover:bg-yellow-400 hover:bg-opacity-100 hover:text-primary w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-white bg-opacity-10 hover:bg-yellow-400 hover:bg-opacity-100 hover:text-primary w-10 h-10 rounded-full flex items-center justify-center transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-white border-opacity-20 pt-6 text-center text-sm">
            <p class="text-white">&copy; {{ date('Y') }} Perpustakaan Universitas Aksara. All Rights Reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>