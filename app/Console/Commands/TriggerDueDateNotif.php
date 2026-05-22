<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\LoanController;
use Illuminate\Console\Command;

class SendOverdueNotifications extends Command
{
    protected $signature   = 'notifications:overdue';
    protected $description = 'Kirim notifikasi denda keterlambatan pengembalian buku (Rp2.000/hari)';

    public function handle(): void
    {
        LoanController::sendOverdueNotifications();
        $this->info('Notifikasi denda berhasil dikirim.');
    }
}
