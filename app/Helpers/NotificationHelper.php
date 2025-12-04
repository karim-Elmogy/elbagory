<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    /**
     * إنشاء إشعار للمستخدم
     */
    public static function notify($title, $message, $type = 'info', $userId = null, $link = null)
    {
        return Notification::createNotification($title, $message, $type, $userId, $link);
    }

    /**
     * إشعار نجاح
     */
    public static function success($title, $message, $userId = null, $link = null)
    {
        return self::notify($title, $message, 'success', $userId, $link);
    }

    /**
     * إشعار تحذير
     */
    public static function warning($title, $message, $userId = null, $link = null)
    {
        return self::notify($title, $message, 'warning', $userId, $link);
    }

    /**
     * إشعار خطأ
     */
    public static function error($title, $message, $userId = null, $link = null)
    {
        return self::notify($title, $message, 'error', $userId, $link);
    }

    /**
     * إشعار معلومات
     */
    public static function info($title, $message, $userId = null, $link = null)
    {
        return self::notify($title, $message, 'info', $userId, $link);
    }

    /**
     * إشعار للجميع
     */
    public static function notifyAll($title, $message, $type = 'info', $link = null)
    {
        return self::notify($title, $message, $type, null, $link);
    }
}

