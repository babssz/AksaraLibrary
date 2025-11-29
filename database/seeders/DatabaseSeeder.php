<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data existing
        User::truncate();

        // Data Admin
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@aksara.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Gedung Perpustakaan Lt. 2, Universitas Aksara',
            'email_verified_at' => now(),
        ]);

        // Data Mahasiswa
        $mahasiswas = [
            [
                'name' => 'Ahmad Budiman',
                'email' => 'ahmad@mhs.aksara.ac.id',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nim' => '202401001',
                'phone' => '081234567891',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@mhs.aksara.ac.id',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nim' => '202401002',
                'phone' => '081234567892',
                'address' => 'Jl. Sudirman No. 45, Bandung',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@mhs.aksara.ac.id',
                'password' => Hash::make('mahasiswa123'),
                'role' => 'mahasiswa',
                'nim' => '202401003',
                'phone' => '081234567893',
                'address' => 'Jl. Gajah Mada No. 67, Surabaya',
            ]
        ];

        foreach ($mahasiswas as $mahasiswa) {
            User::create(array_merge($mahasiswa, ['email_verified_at' => now()]));
        }

        // Data Pegawai
        $pegawais = [
            [
                'name' => 'Dr. Sri Mulyani, M.Kom.',
                'email' => 'sri@aksara.ac.id',
                'password' => Hash::make('pegawai123'),
                'role' => 'pegawai',
                'nip' => '198001011',
                'phone' => '081234567894',
                'address' => 'Jl. Akademik No. 12, Kampus Aksara',
            ],
            [
                'name' => 'Prof. Bambang Susilo, M.T.',
                'email' => 'bambang@aksara.ac.id',
                'password' => Hash::make('pegawai123'),
                'role' => 'pegawai',
                'nip' => '198001012',
                'phone' => '081234567895',
                'address' => 'Jl. Pendidikan No. 23, Kampus Aksara',
            ]
        ];

        foreach ($pegawais as $pegawai) {
            User::create(array_merge($pegawai, ['email_verified_at' => now()]));
        }

    }
}