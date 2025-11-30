<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use App\Models\Loan;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->admin(); 
        } elseif ($user->isMahasiswa()) {
            return $this->mahasiswa();
        } else {
            return view('pegawai.dashboard');
        }
    }


        public function admin(): View
    {
        // Statistik Pengguna
        $totalUsers = User::count();
        $mahasiswaCount = User::where('role', 'mahasiswa')->count();
        $pegawaiCount = User::where('role', 'pegawai')->count();
        $adminCount = User::where('role', 'admin')->count();

        // Statistik Buku
        $totalBooks = Book::count();
        $availableBooks = Book::where('stok', '>', 0)->count();
        $outOfStockBooks = Book::where('stok', 0)->count();

        // Statistik Peminjaman
        $activeLoans = Loan::where('status', 'dipinjam')->count();
        $overdueLoans = Loan::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->count();
        $completedLoans = Loan::where('status', 'dikembalikan')->count();

        // Peminjaman Terlambat Detail
        $overdueLoansList = Loan::with(['user', 'book'])
            ->where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->startOfDay())
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->get();

        // Aktivitas Terbaru
        $recentActivities = Loan::with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        // Perlu Tindakan
        $pendingActions = $overdueLoans; 

        return view('admin.users.dashboard', compact(
            'totalUsers',
            'mahasiswaCount', 
            'pegawaiCount',
            'adminCount',
            'totalBooks',
            'availableBooks',
            'outOfStockBooks',
            'activeLoans',
            'overdueLoans',
            'completedLoans',
            'overdueLoansList',
            'recentActivities',
            'pendingActions'
        ));
    }

    public function mahasiswa(): View
    {
        $user = auth()->user();
        
        // Data untuk rekomendasi buku
        $popularBooks = Book::withCount(['reviews as reviews_count'])
            ->withAvg('reviews', 'rating')
            ->where('stok', '>', 0)
            ->orderBy('reviews_avg_rating', 'desc')
            ->orderBy('reviews_count', 'desc')
            ->take(3)
            ->get();

        $newBooks = Book::where('stok', '>', 0)
            ->latest()
            ->take(3)
            ->get();

        // Data peminjaman aktif
        $activeLoans = $user->loans()
            ->with('book')
            ->where('status', 'dipinjam')
            ->latest()
            ->take(5)
            ->get();

        $totalDenda = 0;
        $jumlahBukuTerlambat = 0;
        $hasBlockingDenda = false;

        foreach ($activeLoans as $loan) {
            if ($loan->is_late) {
                $jumlahBukuTerlambat++;
                $denda = $loan->calculateDenda();
                $totalDenda += $denda;
                
                if ($loan->tanggal_jatuh_tempo && $loan->tanggal_jatuh_tempo->diffInDays(now()) > 7) {
                    $hasBlockingDenda = true;
                }
            }
        }

        if ($totalDenda > 50000) {
            $hasBlockingDenda = true;
        }

        return view('mahasiswa.dashboard', compact(
            'popularBooks', 
            'newBooks', 
            'activeLoans',
            'totalDenda',
            'jumlahBukuTerlambat',
            'hasBlockingDenda'
        ));
    }

    public function pegawai(): View
    {
        return view('pegawai.dashboard');
    }

    public function allActivities(): View
    {
        $recentActivities = Loan::with(['user', 'book'])
            ->latest()
            ->paginate(20); 

        return view('admin.users.activities', compact('recentActivities'));
    }
}