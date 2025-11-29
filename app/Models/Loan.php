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
        'perpanjangan_ke', 'diproses_oleh' // ✅ TAMBAHKAN FIELD INI
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

    // ✅ TAMBAHKAN RELASI UNTUK PEGAWAI YANG MEMPROSES PENGEMBALIAN
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function isLate()
    {
        return now()->greaterThan($this->tanggal_jatuh_tempo) && $this->status === 'dipinjam';
    }

    public function calculateDenda()
    {
        if ($this->isLate()) {
            $daysLate = now()->diffInDays($this->tanggal_jatuh_tempo);
            return $daysLate * $this->book->denda_per_hari;
        }
        return 0;
    }

    public function canBeRenewed()
    {
        return !$this->isLate() && $this->perpanjangan_ke < 2;
    }

    // ✅ TAMBAHKAN METHOD UNTUK MENDAPATKAN JUMLAH HARI TELAT
    public function daysLate()
    {
        if ($this->isLate()) {
            return now()->diffInDays($this->tanggal_jatuh_tempo);
        }
        return 0;
    }

    // ✅ TAMBAHKAN METHOD UNTUK STATUS DENDA
    public function getStatusDendaAttribute()
    {
        if ($this->denda > 0) {
            return $this->denda_lunas ? 'lunas' : 'tertunggak';
        }
        return 'tidak ada';
    }
}