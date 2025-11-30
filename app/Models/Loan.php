<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'tanggal_pinjam', 'tanggal_kembali',
        'tanggal_jatuh_tempo', 'status', 'denda', 'denda_lunas', 
        'perpanjangan_keh', 'diproses_oleh'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_jatuh_tempo' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * CEK APAKAH LOAN TERLAMBAT
     */
    public function getIsLateAttribute()
    {
        return $this->status === 'dipinjam' && 
               $this->tanggal_jatuh_tempo && 
               now()->startOfDay()->gt($this->tanggal_jatuh_tempo);
    }

    /**
     * HITUNG DENDA BERDASARKAN KETERLAMBATAN (HARI BULAT)
     */
    public function calculateDenda()
    {
        if ($this->is_late && $this->tanggal_jatuh_tempo) {
            $daysLate = $this->days_late; // Pakai accessor days_late yang sudah dibulatkan
            $dendaPerHari = $this->book->denda_per_hari ?? 5000;
            return $daysLate * $dendaPerHari;
        }
        return 0;
    }

    /**
     * HARI KETERLAMBATAN (BULAT, TANPA KOMMA)
     */
    public function getDaysLateAttribute()
    {
        if ($this->is_late && $this->tanggal_jatuh_tempo) {
            // Gunakan startOfDay() untuk menghitung hari bulat
            return now()->startOfDay()->diffInDays($this->tanggal_jatuh_tempo->startOfDay());
        }
        return 0;
    }

    /**
     * CEK APAKAH USER PUNYA DENDA TERTUNGGAK YANG MEMBLOKIR PEMINJAMAN
     */
/**
 * CEK APAKAH USER PUNYA DENDA TERTUNGGAK YANG MEMBLOKIR PEMINJAMAN
 */
    public static function userHasBlockingDenda($userId)
    {
        $loans = self::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->get();

        $totalDenda = 0;
        $hasBlocking = false;

        foreach ($loans as $loan) {
            $denda = $loan->calculateDenda();
            $totalDenda += $denda;
            
            // Blokir jika terlambat lebih dari 7 hari
            if ($loan->days_late > 7) {
                $hasBlocking = true;
                \Log::info("BLOKIR: User {$userId} terlambat {$loan->days_late} hari untuk buku {$loan->book->judul}");
            }
        }

        // Blokir jika total denda > Rp 50.000
        if ($totalDenda > 50000) {
            $hasBlocking = true;
            \Log::info("BLOKIR: User {$userId} total denda Rp {$totalDenda}");
        }

        return $hasBlocking;
    }

    /**
     * TOTAL DENDA TERTUNGGAK USER
     */
    public static function getTotalDendaTertunggak($userId)
    {
        $loans = self::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->get();

        $total = 0;
        foreach ($loans as $loan) {
            $total += $loan->calculateDenda();
        }
        return $total;
    }

    /**
     * JUMLAH BUKU YANG TERLAMBAT
     */
    public static function getJumlahBukuTerlambat($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->count();
    }

    /**
     * CEK APAKAH BISA DIPERPANJANG
     */
    public function canBeRenewed()
    {
        return !$this->is_late && $this->perpanjangan_ke < 2;
    }

    /**
     * STATUS DENDA
     */
    public function getStatusDendaAttribute()
    {
        if ($this->denda > 0) {
            return $this->denda_lunas ? 'lunas' : 'tertunggak';
        }
        return 'tidak ada';
    }

    /**
     * ACCESSOR UNTUK FORMATTED DUE DATE
     */
    public function getFormattedDueDateAttribute()
    {
        return $this->tanggal_jatuh_tempo 
            ? $this->tanggal_jatuh_tempo->format('d M Y') 
            : 'Belum ditentukan';
    }
}