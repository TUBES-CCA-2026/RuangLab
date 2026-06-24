<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }
}
