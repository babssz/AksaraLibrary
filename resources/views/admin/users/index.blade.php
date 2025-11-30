<!-- resources/views/admin/users/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-primary">Manajemen User</h1>
        <p class="text-gray-600">Kelola semua pengguna sistem perpustakaan</p>
    </div>
    
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-primary">Daftar Pengguna</h2>
            <p class="text-gray-600">Total {{ $users->count() }} user terdaftar</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
           class="bg-secondary hover:bg-opacity-90 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-user-plus mr-2"></i>
            Tambah User
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-secondary bg-opacity-20 text-secondary rounded-full flex items-center justify-center font-semibold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role == 'admin' ? 'bg-primary bg-opacity-20 text-primary' : 
                                   ($user->role == 'pegawai' ? 'bg-secondary bg-opacity-20 text-secondary' : 'bg-accent bg-opacity-20 text-gray-700') }}">
                                <i class="fas fa-{{ $user->role == 'admin' ? 'crown' : ($user->role == 'pegawai' ? 'user-tie' : 'user-graduate') }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $user->is_active ? 'check' : 'times' }} mr-1"></i>
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="text-secondary hover:text-opacity-80 transition duration-150 flex items-center">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                                
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 transition duration-150 flex items-center"
                                            onclick="return confirm('Yakin hapus user {{ $user->name }}?')">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-400 flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    Diri Sendiri
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->isEmpty())
    <div class="text-center py-12 bg-white rounded-xl shadow-lg">
        <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg">Belum ada user terdaftar.</p>
        <a href="{{ route('admin.users.create') }}" class="text-secondary hover:text-opacity-80 font-medium mt-2 inline-block">
            Tambah user pertama
        </a>
    </div>
    @endif
</div>
@endsection