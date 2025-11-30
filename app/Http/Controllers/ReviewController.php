<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Review;
use App\Models\Loan;

class ReviewController extends Controller
{
    public function index(): View
    {
         $reviews = Review::with(['user', 'book'])
                    ->orderBy('created_at', 'desc')
                    ->get(); 
    
    return view('reviews.index', compact('reviews'));
    }

    public function create(Loan $loan): View
    {
        if ($loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        if ($loan->status !== 'dikembalikan') {
            abort(403, 'Hanya buku yang sudah dikembalikan yang bisa direview.');
        }

        if ($loan->review) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk buku ini.');
        }

        return view('reviews.create', compact('loan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000'
        ]);

        $loan = Loan::findOrFail($validated['loan_id']);
        
        if ($loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        if ($loan->status !== 'dikembalikan') {
            return redirect()->back()->with('error', 'Hanya buku yang sudah dikembalikan yang bisa direview.');
        }

        if ($loan->review) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk buku ini.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $loan->book_id,
            'loan_id' => $loan->id,
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan']
        ]);

        return redirect()->route('books.show', $loan->book_id)
                        ->with('success', 'Review berhasil ditambahkan!');
    }

    public function show($id): View
    {
        return view('reviews.show', ['id' => $id]);
    }

    public function edit(Review $review): View
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        $review->load('book');

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        return redirect()->route('reviews.index')->with('success', 'Review berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        return redirect()->route('reviews.index')->with('success', 'Review berhasil dihapus.');
    }
}