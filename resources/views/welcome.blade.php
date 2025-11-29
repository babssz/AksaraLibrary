@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')

@section('title', 'Beranda - Perpustakaan Universitas Aksara')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white rounded-2xl shadow-xl mb-12">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">SELAMAT DATANG DI</h1>
            <h2 class="text-2xl md:text-4xl font-bold text-accent mb-4">PERPUSTAKAAN UNIVERSITAS AKSARA</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Portal digital untuk akses koleksi perpustakaan, peminjaman buku, dan layanan akademik terpadu</p>
            
            @guest
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="bg-accent text-primary px-8 py-3 rounded-lg font-bold hover:bg-opacity-90 transition text-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login ke Sistem
                </a>
                <a href="{{ route('register') }}" class="bg-white text-primary px-8 py-3 rounded-lg font-bold hover:bg-opacity-90 transition text-lg">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Anggota
                </a>
            </div>
            @endguest
        </div>
    </div>
</section>

<!-- Discovery Search -->
<section class="mb-16" id="pencarian">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-2xl font-bold text-primary mb-6 text-center">Discovery Search</h3>
        
        <!-- Search Tabs -->
        <div class="flex justify-center mb-6 border-b border-gray-200">
            <button class="tab-button active px-6 py-3 font-semibold text-primary border-b-2 border-primary" data-tab="katalog">
                <i class="fas fa-book mr-2"></i>Katalog
            </button>
            <button class="tab-button px-6 py-3 font-semibold text-gray-600" data-tab="etd">
                <i class="fas fa-graduation-cap mr-2"></i>ETD
            </button>
            <button class="tab-button px-6 py-3 font-semibold text-gray-600" data-tab="jurnal">
                <i class="fas fa-newspaper mr-2"></i>Jurnal
            </button>
            <button class="tab-button px-6 py-3 font-semibold text-gray-600" data-tab="repository">
                <i class="fas fa-archive mr-2"></i>Repository
            </button>
        </div>

        <!-- Search Form -->
        <div class="max-w-4xl mx-auto">
            <form class="flex gap-4 mb-4">
                <div class="flex-1">
                    <input type="text" 
                           placeholder="Masukkan pencarian Anda (judul, penulis, subjek, ISBN)..."
                           class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-primary focus:ring-2 focus:ring-primary focus:ring-opacity-20 transition">
                </div>
                <button type="submit" class="bg-primary text-white px-8 py-4 rounded-xl hover:bg-opacity-90 transition font-semibold">
                    <i class="fas fa-search mr-2"></i>CARI
                </button>
            </form>
            
            <div class="text-center">
                <a href="#" class="text-primary hover:text-secondary transition font-semibold">
                    <i class="fas fa-search-plus mr-2"></i>Pencarian lanjutan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Layanan & Bantuan -->
<section class="mb-16" id="layanan">
    <h3 class="text-3xl font-bold text-primary mb-8 text-center">Layanan & Bantuan</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-hand-holding text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-primary mb-3">Peminjaman & Permintaan Koleksi</h4>
            <p class="text-gray-600">Layanan peminjaman buku fisik dan akses koleksi digital</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-concierge-bell text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-primary mb-3">Layanan & Bantuan</h4>
            <p class="text-gray-600">Bantuan referensi, training, dan konsultasi penelitian</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <div class="bg-accent text-primary w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <h4 class="text-xl font-bold text-primary mb-3">Informasi Publik</h4>
            <p class="text-gray-600">Akses informasi dan statistik perpustakaan</p>
        </div>
    </div>
</section>

<!-- Koleksi -->
<section class="mb-16" id="koleksi">
    <h3 class="text-3xl font-bold text-primary mb-8 text-center">Koleksi Unggulan</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <i class="fas fa-book text-4xl text-primary mb-4"></i>
            <h4 class="text-lg font-bold text-primary mb-2">Buku Teks</h4>
            <p class="text-gray-600 text-sm">10,000+ koleksi</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <i class="fas fa-newspaper text-4xl text-secondary mb-4"></i>
            <h4 class="text-lg font-bold text-primary mb-2">Jurnal</h4>
            <p class="text-gray-600 text-sm">5,000+ judul</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <i class="fas fa-graduation-cap text-4xl text-accent mb-4"></i>
            <h4 class="text-lg font-bold text-primary mb-2">Tesis & Disertasi</h4>
            <p class="text-gray-600 text-sm">2,500+ koleksi</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition">
            <i class="fas fa-database text-4xl text-primary mb-4"></i>
            <h4 class="text-lg font-bold text-primary mb-2">Digital Repository</h4>
            <p class="text-gray-600 text-sm">15,000+ dokumen</p>
        </div>
    </div>
</section>

<!-- Statistik -->
<section class="bg-primary text-white rounded-2xl p-8 mb-16">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div>
            <div class="text-3xl font-bold text-accent mb-2">50,000+</div>
            <div class="text-sm">Total Koleksi</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-accent mb-2">15,000+</div>
            <div class="text-sm">Anggota Aktif</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-accent mb-2">2,500+</div>
            <div class="text-sm">Peminjaman/Bulan</div>
        </div>
        <div>
            <div class="text-3xl font-bold text-accent mb-2">95%</div>
            <div class="text-sm">Kepuasan Pengguna</div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'text-primary', 'border-primary');
                btn.classList.add('text-gray-600');
            });
            
            // Add active class to clicked button
            this.classList.add('active', 'text-primary', 'border-primary');
            this.classList.remove('text-gray-600');
        });
    });
});
</script>
@endsection