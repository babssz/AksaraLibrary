<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Loan;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockingDenda
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->isMahasiswa()) {
            $hasBlockingDenda = Loan::userHasBlockingDenda($user->id);
            
            if ($hasBlockingDenda && $this->shouldBlockRoute($request)) {
                return redirect()->route('mahasiswa.dashboard')
                    ->with('error', 'Anda tidak dapat meminjam buku karena memiliki denda tertunggak yang melebihi batas. Silakan hubungi admin perpustakaan.');
            }
        }

        return $next($request);
    }

    private function shouldBlockRoute(Request $request)
    {
        $blockedRoutes = [
            'books.borrow',
            'loans.store',
            'books.show' // Untuk mencegah akses detail buku jika ingin meminjam
        ];

        return $request->routeIs($blockedRoutes);
    }
}