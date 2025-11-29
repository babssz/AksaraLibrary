<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send scheduled notifications to users';

    public function handle(): void
    {
        $this->info('Mengirim notifikasi jatuh tempo...');
        $dueDateCount = NotificationService::sendDueDateReminders();
        
        $this->info('Mengirim notifikasi keterlambatan...');
        $lateCount = NotificationService::sendLateNotifications();
        
        $this->info('Mengirim notifikasi staff...');
        $staffCount = NotificationService::sendStaffReminders();

        $this->info("Notifikasi berhasil dikirim!");
        $this->info("- {$dueDateCount} pengingat jatuh tempo");
        $this->info("- {$lateCount} notifikasi keterlambatan"); 
        $this->info("- {$staffCount} reminder untuk staff");
    }
}