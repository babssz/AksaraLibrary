<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
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
        
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background min-h-screen font-sans text-gray-900 antialiased">
    <header class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="bg-white text-primary px-3 py-2 rounded-lg font-bold text-lg shadow-md">
                        UA
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">UNIVERSITAS AKSARA</h1>
                        <p class="text-accent text-sm">PERPUSTAKAAN DAN ARSIP</p>
                    </div>
                </div>
                
                @auth
                <div class="flex items-center space-x-4">
                    <span class="text-yellow-400 font-semibold">Halo, {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-accent text-white px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold shadow-md">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
                @else
                <div class="space-x-2">
                    <a href="{{ route('login') }}" class="bg-accent text-white px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold inline-block shadow-md">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-secondary text-white px-4 py-2 rounded-lg hover:opacity-90 transition font-semibold inline-block shadow-md">
                        <i class="fas fa-user-plus mr-2"></i>Register
                    </a>
                </div>
                @endauth
            </div>
        </div>
        <nav class="bg-secondary text-white shadow-md ">
            <div class="container mx-auto px-4">
                <div class="flex space-x-8 py-3">
                    <a href="{{ url('/') }}" class="hover:text-yellow-400 transition flex items-center space-x-2 font-semibold {{ request()->is('/') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white' }}">
                        <span>Beranda</span>
                    </a>
                    <a href="{{ route('books.index') }}" class="hover:text-yellow-400 transition flex items-center space-x-2 font-semibold {{ request()->is('books*') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'text-white' }}">
                        <span>Koleksi Buku</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>


        <div class="min-h-screen bg-background">
            @if (isset($header))
                <header class="bg-primary shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                @yield('content')
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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