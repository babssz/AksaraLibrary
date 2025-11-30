<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Loan;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('user', 'book')->get();
        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        return view('loans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function show($id): View
    {
        return view('loans.show', ['id' => $id]);
    }

    public function edit($id): View
    {
        return view('loans.edit', ['id' => $id]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy($id): RedirectResponse
    {
        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function history()
    {
        $loans = Loan::where('user_id', auth()->id())
                    ->with('book')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return view('loans.history', compact('loans'));
    }

    public function renew(Loan $loan): RedirectResponse
    {
        // Authorization - hanya pemilik loan yang bisa perpanjang
        if ($loan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }

        if (!$loan->canBeRenewed()) {
            return redirect()->back()->with('error', 'Peminjaman tidak dapat diperpanjang.');
        }

        // Logic perpanjangan
        $loan->update([
            'tanggal_jatuh_tempo' => $loan->tanggal_jatuh_tempo->addDays(7),
            'perpanjangan_ke' => $loan->perpanjangan_ke + 1
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil diperpanjang.');
    }

    public function returnBook(Loan $loan): RedirectResponse
    {
        if (!auth()->user()->isPegawai() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya pegawai atau admin yang dapat memproses pengembalian buku.');
        }

        if ($loan->status !== 'dipinjam') {
            return redirect()->back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($loan) {
                $denda = $loan->calculateDenda();
                $loan->update([
                    'status' => 'dikembalikan',
                    'tanggal_kembali' => now(),
                    'denda' => $denda,
                    'diproses_oleh' => auth()->id() // ✅ CATAT SIAPA YANG PROSES
                ]);

                $loan->book->increment('stok');
                $message = $denda > 0 
                    ? "Buku \"{$loan->book->judul}\" telah berhasil dikembalikan. Denda: Rp " . number_format($denda, 0, ',', '.') . " (Harap lunasi di perpustakaan)"
                    : "Buku \"{$loan->book->judul}\" telah berhasil dikembalikan. Terima kasih!";

                Notification::create([
                    'user_id' => $loan->user_id,
                    'type' => 'pengembalian',
                    'title' => 'Pengembalian Berhasil',
                    'message' => $message,
                ]);
            });

            return redirect()->back()->with('success', 'Buku berhasil dikembalikan.');

        } catch (\Exception $e) {
            \Log::error('Error returning book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }
}