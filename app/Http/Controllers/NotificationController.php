<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        try {

        // ✅ GUNAKAN RELASI YANG SUDAH DIPERBAIKI
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
        
        \Log::info('Notifications found: ' . $notifications->count());
        
        return view('notifications.index', compact('notifications'));
        
    } catch (\Exception $e) {
        \Log::error('Notifications error: ' . $e->getMessage());
        $notifications = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10, 1);
        return view('notifications.index', compact('notifications'));
    }

    }

    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->where('id', $id)->first();
            
            if ($notification) {
                $notification->markAsRead();
                return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
            }
            
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem notifikasi sedang dalam perbaikan.');
        }
    }

    public function markAllAsRead()
    {
        try {
            auth()->user()->unreadNotifications->update();
            return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem notifikasi sedang dalam perbaikan.');
        }
    }

    public function destroy($id)
    {
        try {
            $notification = auth()->user()->notifications()->where('id', $id)->first();
            
            if ($notification) {
                $notification->delete();
                return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
            }
            
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem notifikasi sedang dalam perbaikan.');
        }
    }

    public function clearAll()
    {
        try {
            auth()->user()->notifications()->delete();
            return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem notifikasi sedang dalam perbaikan.');
        }
    }

    // NotificationController.php
    public function systemLog()
    {
        // ✅ AUTHORIZATION: Hanya pegawai dan admin yang bisa akses
        if (!auth()->user()->isPegawai() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya pegawai yang dapat mengakses log notifikasi sistem.');
        }

        // ✅ AMBIL SEMUA NOTIFIKASI SISTEM (dari semua user)
        $notifications = Notification::with('user')
                            ->orderBy('created_at', 'desc')
                            ->paginate(20);

        // ✅ STATISTIK NOTIFIKASI
        $stats = [
            'total' => Notification::count(),
            'today' => Notification::whereDate('created_at', today())->count(),
            'unread' => Notification::where('is_read', false)->count(),
            'types' => Notification::select('type', DB::raw('count(*) as count'))
                        ->groupBy('type')
                        ->get()
                        ->pluck('count', 'type')
        ];

        return view('notifications.system-log', compact('notifications', 'stats'));
    }
}