<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function unread(Request $request)
    {
        $user = $request->user();
        $notifications = $user->unreadNotifications()->take(10)->get();

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function simulate(Request $request)
    {
        $user = $request->user();

        $messages = [
            [
                'title' => 'Pengajuan Anggaran Acara Baru',
                'message' => 'Desa Sukamaju mengusulkan anggaran Rp 45.000.000 untuk Pesta Rakyat.',
                'icon' => 'fa-solid fa-file-invoice-dollar',
            ],
            [
                'title' => 'Verifikasi Bukti Bendahara',
                'message' => 'Bendahara telah mengupload bukti pengeluaran operasional acara.',
                'icon' => 'fa-solid fa-check-double',
            ],
            [
                'title' => 'Data Desa Diperbarui',
                'message' => 'Kepala Desa Karanganyar telah memperbarui data kontak daerah.',
                'icon' => 'fa-solid fa-map-location-dot',
            ],
        ];

        $pick = $messages[array_rand($messages)];

        $user->notify(new SystemNotification(
            $pick['title'],
            $pick['message'],
            $pick['icon'],
            '/dashboard'
        ));

        return response()->json([
            'success' => true,
            'title' => $pick['title'],
            'message' => $pick['message'],
        ]);
    }
}
