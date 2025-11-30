<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Support\Facades\Hash;

class DendaTestSeeder extends Seeder
{
    public function run(): void
    {
        // Cek atau buat user khusus testing
        $user = User::where('email', 'denda_test@univ.ac.id')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Mahasiswa Denda Test',
                'email' => 'denda_test@univ.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
                'nim' => 'TEST001',
                'phone' => '081234567890',
                'address' => 'Alamat testing'
            ]);
        }

        // Hapus peminjaman lama jika ada
        Loan::where('user_id', $user->id)->delete();

        // Ambil buku yang tersedia
        $books = Book::where('stok', '>', 0)->take(3)->get();

        if ($books->count() >= 3) {
            // **TEST CASE 1: Peminjaman aktif normal (tidak terlambat)**
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $books[0]->id,
                'tanggal_pinjam' => now()->subDays(2),
                'tanggal_jatuh_tempo' => now()->addDays(5),
                'status' => 'dipinjam',
            ]);

            // **TEST CASE 2: Peminjaman terlambat 3 hari (denda Rp 15.000)**
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $books[1]->id,
                'tanggal_pinjam' => now()->subDays(10),
                'tanggal_jatuh_tempo' => now()->subDays(3),
                'status' => 'dipinjam',
            ]);

            // **TEST CASE 3: Peminjaman terlambat 10 hari (BLOKIR PEMINJAMAN)**
            Loan::create([
                'user_id' => $user->id,
                'book_id' => $books[2]->id,
                'tanggal_pinjam' => now()->subDays(20),
                'tanggal_jatuh_tempo' => now()->subDays(10),
                'status' => 'dipinjam',
            ]);

            $this->command->info('🎯 DATA TESTING DENDA BERHASIL DIBUAT!');
            $this->command->info('');
            $this->command->info('👤 LOGIN DETAILS:');
            $this->command->info('   Email: denda_test@univ.ac.id');
            $this->command->info('   Password: password123');
            $this->command->info('');
            $this->command->info('📊 TEST SCENARIOS:');
            $this->command->info('   ✅ Test Case 1: Peminjaman normal (tidak terlambat)');
            $this->command->info('   ✅ Test Case 2: Peminjaman terlambat 3 hari (denda Rp 15.000)');
            $this->command->info('   ✅ Test Case 3: Peminjaman terlambat 10 hari (BLOKIR PEMINJAMAN)');
            $this->command->info('');
            $this->command->info('🎯 EXPECTED RESULTS:');
            $this->command->info('   Total Denda: Rp 65.000');
            $this->command->info('   Buku Terlambat: 2');
            $this->command->info('   Status Blokir: YA');
            $this->command->info('   Tombol Pinjam: DIBLOKIR');
            
        } else {
            $this->command->error('❌ Tidak ada cukup buku untuk testing! Minimal butuh 3 buku.');
        }
    }
}