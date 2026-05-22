<?php

use Illuminate\Support\Facades\Schedule;

// Kirim notifikasi denda keterlambatan setiap hari pukul 08.00
Schedule::command('notifications:overdue')->dailyAt('08:00');
