<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard') - Perpustakaan Universitas Aksara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                            primary: '#1B3C53',      // Navy Blue - warna utama
                            secondary: '#234C6A',    // Medium Blue - warna sekunder
                            accent: '#456882',       // Light Blue - aksen
                            background: '#E3E3E3',   // Light Gray background
                            danger: '#DC2626',       // Red
                            success: '#16A34A',      // Green
                            warning: '#F59E0B',      // Orange
                            info: '#3B82F6',         
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background min-h-screen">
    <!-- Header -->
    <!-- Header -->
<header class="bg-primary text-white shadow-lg">
    <div class="container mx-auto px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-white text-primary px-3 py-1 rounded-lg font-bold text-lg">
                    UA
                </div>
                <div>
                    <h1 class="text-xl font-bold">UNIVERSITAS AKSARA</h1>
                    <p class="text-accent text-sm">ADMIN PANEL</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- User Dropdown untuk Admin -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-accent hover:text-white transition">
                        <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                            <i class="fas fa-user-shield text-primary text-sm"></i>
                        </div>
                        <span>Admin: {{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 border border-gray-200">
                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                            <div class="font-medium">{{ auth()->user()->name }}</div>
                            <div class="text-gray-500 text-xs">{{ auth()->user()->email }}</div>
                            <div class="text-gray-500 text-xs">Role: Admin</div>
                        </div>
                        
                        <!-- TAMBAHKAN MENU EDIT PROFILE -->
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
</header>

    <!-- Navigation -->
    <nav class="bg-secondary text-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex space-x-8 py-3">
                <a href="{{ url('/') }}" class="hover:text-accent transition flex items-center space-x-1">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-accent transition flex items-center space-x-1">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
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
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">UNIVERSITAS AKSARA</h3>
                    <p class="text-accent">Jl. Perpustakaan No. 123</p>
                    <p class="text-accent">Kota Aksara, 12345</p>
                    <p class="text-accent">Telp: (021) 123-4567</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Admin Panel</h4>
                    <ul class="space-y-2 text-accent">
                        <li><a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">Dashboard</a></li>
                        <li><a href="{{ route('admin.users.index') }}" class="hover:text-white transition">Manajemen User</a></li>
                        <li><a href="{{ route('books.index') }}" class="hover:text-white transition">Manajemen Buku</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Sistem</h4>
                    <ul class="space-y-2 text-accent">
                        <li><a href="{{ route('loans.index') }}" class="hover:text-white transition">Peminjaman</a></li>
                        <li><a href="{{ route('reviews.index') }}" class="hover:text-white transition">Review</a></li>
                        <li><a href="{{ route('notifications.index') }}" class="hover:text-white transition">Notifikasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <p class="text-accent">Admin Helpdesk: admin@univ-aksara.ac.id</p>
                    <p class="text-accent">Emergency: (021) 123-4567 ext. 123</p>
                </div>
            </div>
            <div class="border-t border-accent mt-8 pt-6 text-center text-accent">
                <p>&copy; 2024 Perpustakaan Universitas Aksara - Admin Panel</p>
            </div>
        </div>
    </footer>
</body>
</html>