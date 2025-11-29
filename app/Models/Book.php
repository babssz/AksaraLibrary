<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit', 'kategori', 
        'isbn', 'deskripsi', 'stok', 'max_peminjaman_hari', 'denda_per_hari', 'deskripsi',  'cover'
    ];


     public function getCoverUrlAttribute()
    {
        if ($this->cover) {
            return asset('storage/' . $this->cover);
        }
        return null;
    }
    
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }

    public function decreaseStock()
    {
        $this->decrement('stok');
    }

    public function increaseStock()
    {
        $this->increment('stok');
    }
}