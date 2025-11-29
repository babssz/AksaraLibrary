<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Book;
use App\Models\Review;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        try {
            // Debug: Cek apakah ada buku di database
            $totalBooks = Book::count();
            \Log::info("Total books in database: " . $totalBooks);
            
            // Query books dengan search dan filter
            $query = Book::query();
            
            // Search functionality
            if ($search = request('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', '%'.$search.'%')
                      ->orWhere('penulis', 'like', '%'.$search.'%')
                      ->orWhere('kategori', 'like', '%'.$search.'%');
                });
            }
            
            // Filter kategori
            if ($kategori = request('kategori')) {
                $query->where('kategori', $kategori);
            }
            
            // Get results
            $books = $query->get();
            
            // Debug info
            \Log::info("Books retrieved: " . $books->count());
            
            return view('books.index', compact('books'));
            
        } catch (\Exception $e) {
            \Log::error('Error in books index: ' . $e->getMessage());
            // Return empty collection jika error
            return view('books.index', ['books' => collect()]);
        }
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        try {
            // Validasi data
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
                'kategori' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:255',
                'stok' => 'required|integer|min:0',
                'max_peminjaman_hari' => 'required|integer|min:1',
                'denda_per_hari' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle cover upload
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('covers', 'public');
                $validated['cover'] = $coverPath;
            }

            // Tambahkan field default
            $validated['rating'] = 0; // default rating
            $validated['created_by'] = auth()->id(); // user yang membuat

            // Simpan ke database
            $book = Book::create($validated);

            \Log::info('Book created with cover: ' . $book->cover);
            // Redirect dengan success message
            return redirect()->route('books.index')
                            ->with('success', 'Buku "'.$book->judul.'" berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Error creating book: ' . $e->getMessage());
            
            // Redirect back dengan error
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            
            // ✅ TAMBAHKAN DATA REVIEWS
            $reviews = Review::where('book_id', $id)
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
            
            return view('books.show', compact('book', 'reviews'));
        } catch (\Exception $e) {
            return redirect()->route('books.index')
                            ->with('error', 'Buku tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        try {
            $book = Book::findOrFail($id);
            return view('books.edit', compact('book'));
        } catch (\Exception $e) {
            return redirect()->route('books.index')
                            ->with('error', 'Buku tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);

            // Validasi data
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
                'kategori' => 'required|string|max:255',
                'isbn' => 'nullable|string|max:255',
                'stok' => 'required|integer|min:0',
                'max_peminjaman_hari' => 'required|integer|min:1',
                'denda_per_hari' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'rating' => 'nullable|numeric|min:0|max:5',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Handle cover upload jika ada file baru
            if ($request->hasFile('cover')) {
                // Hapus cover lama jika ada
                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }
                
                $coverPath = $request->file('cover')->store('covers', 'public');
                $validated['cover'] = $coverPath;
            } else {
                // Pertahankan cover lama jika tidak ada upload baru
                $validated['cover'] = $book->cover;
            }

            // Update buku
            $book->update($validated);

            return redirect()->route('books.index')
                            ->with('success', 'Buku "'.$book->judul.'" berhasil diupdate!');

        } catch (\Exception $e) {
            \Log::error('Error updating book: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
            $bookTitle = $book->judul;
            
            \Log::info('Book to delete: ' . $bookTitle);
            
            // Hapus cover file jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
                \Log::info('Cover file deleted: ' . $book->cover);
            }
            
            // Hapus buku dari database
            $book->delete();
            \Log::info('Book successfully deleted from database');
            
            return redirect()->route('books.index')
                            ->with('success', 'Buku "'.$bookTitle.'" berhasil dihapus!');
                            
        } catch (\Exception $e) {
            \Log::error('Error deleting book: ' . $e->getMessage());
            return redirect()->route('books.index')
                            ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Handle book loan process - METHOD YANG HARUS DIPERBAIKI
     */
    public function loan(Book $book): RedirectResponse
    {


    
        // Authorization - hanya mahasiswa yang bisa pinjam
        if (!auth()->user()->isMahasiswa()) {
            abort(403, 'Hanya mahasiswa yang dapat meminjam buku.');
        }

        // Validation - cek ketersediaan buku
        if (!$book->isAvailable()) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // Validation - cek apakah user sudah meminjam buku yang sama
        $existingLoan = Loan::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereIn('status', ['dipinjam', 'diperpanjang'])
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'Anda sudah meminjam buku ini dan belum mengembalikannya.');
        }

        // Process loan
        try {
            DB::transaction(function () use ($book) {
                // ✅ PERBAIKI: Sesuaikan dengan nama kolom di Model Loan
                Loan::create([
                    'user_id' => auth()->id(),
                    'book_id' => $book->id,
                    'tanggal_pinjam' => now(), // ✅ GANTI: tanggal_pinjam
                    'tanggal_kembali' => null, // ✅ Biarkan null dulu
                    'tanggal_jatuh_tempo' => now()->addDays($book->max_peminjaman_hari ?? 7), // ✅ GANTI: tanggal_jatuh_tempo
                    'status' => 'dipinjam',
                    'denda' => 0,
                    'denda_lunas' => false,
                    'perpanjangan_ke' => 0
                ]);

                // Update book stock
                $book->decrement('stok');

                Notification::create([
                'user_id' => auth()->id(),
                'type' => 'peminjaman',
                'title' => 'Peminjaman Berhasil',
                'message' => "Buku \"{$book->judul}\" berhasil dipinjam. Jatuh tempo: " . 
                           now()->addDays($book->max_peminjaman_hari ?? 7)->format('d M Y'),
            ]   );
            });

            return redirect()->back()->with('success', 
                'Buku berhasil dipinjam. Harap dikembalikan sebelum ' . now()->addDays($book->max_peminjaman_hari ?? 7)->format('d M Y'));

        } catch (\Exception $e) {
            \Log::error('Error processing loan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses peminjaman: ' . $e->getMessage());
        }
    }
}