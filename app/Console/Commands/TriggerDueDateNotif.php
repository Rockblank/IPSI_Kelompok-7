<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LoanController; // Diarahkan ke LoanController sesuai poin 2

class TriggerDueDateNotif extends Command
{
    // Nama command yang dipanggil di terminal atau routes/console.php
    protected $signature = 'app:trigger-due-date-notif';

    protected $description = 'Mengecek peminjaman yang jatuh tempo dan membuat notifikasi otomatis';

    public function handle()
    {
        $this->info('Memulai pengecekan buku jatuh tempo...');
        
        // Memanggil fungsi trigger yang nanti kita taruh di LoanController
        $count = LoanController::triggerNotifications();
        
        $this->info("Pengecekan selesai! {$count} notifikasi baru ditambahkan.");
    }
}