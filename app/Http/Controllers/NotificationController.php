<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Session; // <-- Tambahkan import Session di sini

class NotificationController extends Controller
{
    /**
     * Tampilkan notifikasi milik user, tandai is_read = 1 saat dibuka (PSPEC hal. 52)
     */
    public function index()
    {
        // KUNCI SINKRONISASI: Ambil user_id dari Session sesuai sistem auth kelompokmu
        $userId = Session::get('user_id'); 

        // Ambil notifikasi milik user
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('sent_at', 'desc')
            ->get();

        // Otomatis tandai is_read = 1 saat halaman diakses
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Mengarah ke folder resources/views/notifications/index.blade.php
        return view('notifications.index', compact('notifications'));
    }
}