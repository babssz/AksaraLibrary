@extends(auth()->check() ? (auth()->user()->role === 'mahasiswa' ? 'layouts.mahasiswa' : (auth()->user()->role === 'pegawai' ? 'layouts.pegawai' : 'layouts.admin')) : 'layouts.app')


@section('title', 'Profile - Perpustakaan Universitas Aksara')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header dengan tombol kembali -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-primary">{{ __('Profile') }}</h1>
                    <p class="text-gray-600 mt-2">Kelola informasi profile dan keamanan akun Anda</p>
                </div>
                
                <!-- Tombol Kembali -->
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                @elseif(auth()->user()->isMahasiswa())
                    <a href="{{ route('mahasiswa.dashboard') }}" 
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                @elseif(auth()->user()->isPegawai())
                    <a href="{{ route('pegawai.dashboard') }}" 
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition flex items-center space-x-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Dashboard</span>
                    </a>
                @endif
            </div>
    
            <div class="space-y-6">
                <!-- Update Profile Information -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>
    
                <!-- Update Password -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
    
                <!-- Delete Account -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection