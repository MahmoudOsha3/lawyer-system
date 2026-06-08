<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'type'    => $n->data['type'],
                'title'   => $n->data['title'],
                'body'    => $n->data['body'],
                'is_read' => !is_null($n->read_at),
                'time'    => $this->relativeTime($n->created_at),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(string $id)
    {
        Auth::user()
            ->notifications()
            ->findOrFail($id)
            ->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }

    private function relativeTime($date): string
    {
        $diff = now()->diffInMinutes($date);
        if ($diff < 60)  return "منذ {$diff} دقيقة";
        $hours = now()->diffInHours($date);
        if ($hours < 24) return "منذ {$hours} ساعة";
        $days = now()->diffInDays($date);
        if ($days === 1) return "أمس";
        return "منذ {$days} أيام";
    }
}
