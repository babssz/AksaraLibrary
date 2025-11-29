<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Loan;

class NotificationService
{
    public static function sendDueDateReminders()
    {
        $loans = Loan::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<=', now()->addDays(1))
            ->where('tanggal_jatuh_tempo', '>', now())
            ->with(['user', 'book'])
            ->get();

        foreach ($loans as $loan) {
            Notification::create([
                'user_id' => $loan->user_id,
                'type' => 'pengingat',
                'title' => 'Pengingat Jatuh Tempo',
                'message' => "Peminjaman buku \"{$loan->book->judul}\" akan jatuh tempo besok (" . $loan->tanggal_jatuh_tempo->format('d M Y') . ").",
            ]);
        }

        return count($loans);
    }

    public static function sendLateNotifications()
    {
        $loans = Loan::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now())
            ->with(['user', 'book'])
            ->get();

        foreach ($loans as $loan) {
            $daysLate = now()->diffInDays($loan->tanggal_jatuh_tempo);
            $denda = $daysLate * $loan->book->denda_per_hari;

            Notification::create([
                'user_id' => $loan->user_id,
                'type' => 'keterlambatan',
                'title' => 'Peminjaman Terlambat',
                'message' => "Peminjaman buku \"{$loan->book->judul}\" terlambat {$daysLate} hari. Denda: Rp " . number_format($denda, 0, ',', '.'),
            ]);
        }

        return count($loans);
    }

    public static function sendStaffReminders()
    {
        $criticalLoans = Loan::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->subDays(3))
            ->with(['user', 'book'])
            ->get();

        $pegawai = \App\Models\User::where('role', 'pegawai')->get();

        foreach ($pegawai as $staff) {
            foreach ($criticalLoans as $loan) {
                $daysLate = now()->diffInDays($loan->tanggal_jatuh_tempo);
                
                Notification::create([
                    'user_id' => $staff->id,
                    'type' => 'staff_reminder',
                    'title' => 'Peminjaman Kritis',
                    'message' => "Peminjaman oleh {$loan->user->name} untuk buku \"{$loan->book->judul}\" sudah terlambat {$daysLate} hari.",
                ]);
            }
        }

        return count($criticalLoans);
    }
}