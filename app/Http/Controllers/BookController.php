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
            $totalBooks = Book::count();
            \Log::info("Total books in database: " . $totalBooks);
            $query = Book::query();
            
            if ($search = request('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', '%'.$search.'%')
                      ->orWhere('penulis', 'like', '%'.$search.'%')
                      ->orWhere('kategori', 'like', '%'.$search.'%');
                });
            }
            
            if ($kategori = request('kategori')) {
                $query->where('kategori', $kategori);
            }
            $books = $query->get();
        
            \Log::info("Books retrieved: " . $books->count());
            
            return view('books.index', compact('books'));
            
        } catch (\Exception $e) {
            \Log::error('Error in books index: ' . $e->getMessage());
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

            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('covers', 'public');
                $validated['cover'] = $coverPath;
            }

            $validated['rating'] = 0;
            $validated['created_by'] = auth()->id(); 
            $book = Book::create($validated);

            \Log::info('Book created with cover: ' . $book->cover);
            return redirect()->route('books.index')
                            ->with('success', 'Buku "'.$book->judul.'" berhasil ditambahkan!');

        } catch (\Exception $e) {
            \Log::error('Error creating book: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage())
                            ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
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

            if ($request->hasFile('cover')) {
                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }
                
                $coverPath = $request->file('cover')->store('covers', 'public');
                $validated['cover'] = $coverPath;
            } else {
                $validated['cover'] = $book->cover;
            }

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
            
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
                \Log::info('Cover file deleted: ' . $book->cover);
            }
            
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

    public function loan(Book $book): RedirectResponse
    {
        if (!auth()->user()->isMahasiswa()) {
            abort(403, 'Hanya mahasiswa yang dapat meminjam buku.');
        }

        if (Loan::userHasBlockingDenda(auth()->id())) {
            $totalDenda = Loan::getTotalDendaTertunggak(auth()->id());
            return redirect()->back()->with('error', 
                'Anda memiliki denda tertunggak sebesar Rp ' . number_format($totalDenda, 0, ',', '.') . 
                '. Silakan lunasi denda terlebih dahulu untuk meminjam buku baru.');
        }

        if (!$book->isAvailable()) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

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
                Loan::create([
                    'user_id' => auth()->id(),
                    'book_id' => $book->id,
                    'tanggal_pinjam' => now(), 
                    'tanggal_kembali' => null, 
                    'tanggal_jatuh_tempo' => now()->addDays($book->max_peminjaman_hari ?? 7),
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