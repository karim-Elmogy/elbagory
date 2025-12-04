<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * عرض جميع الإشعارات
     */
    public function index()
    {
        $userId = Auth::id();
        
        $notifications = Notification::where(function($query) use ($userId) {
            $query->where('user_id', $userId)->orWhereNull('user_id');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * الحصول على الإشعارات غير المقروءة (AJAX)
     */
    public function getUnread()
    {
        $userId = Auth::id();
        
        $notifications = Notification::getUnread($userId, 10);
        $unreadCount = Notification::getUnreadCount($userId);

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id)
    {
        $userId = Auth::id();
        
        $notification = Notification::where(function($query) use ($userId) {
            $query->where('user_id', $userId)->orWhereNull('user_id');
        })->findOrFail($id);

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تحديد الإشعار كمقروء');
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        $userId = Auth::id();
        
        Notification::markAllAsRead($userId);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة');
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        
        $notification = Notification::where(function($query) use ($userId) {
            $query->where('user_id', $userId)->orWhereNull('user_id');
        })->findOrFail($id);

        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم حذف الإشعار');
    }
}
