<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * إنشاء إشعار جديد
     */
    public static function createNotification($title, $message, $type = 'info', $userId = null, $link = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }

    /**
     * تحديد الإشعار كمقروء
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public static function markAllAsRead($userId = null)
    {
        $query = self::where('is_read', false);
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }
        
        return $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * الحصول على الإشعارات غير المقروءة
     */
    public static function getUnread($userId = null, $limit = 10)
    {
        $query = self::where('is_read', false)->orderBy('created_at', 'desc');
        
        if ($userId) {
            $query->where(function($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            });
        } else {
            $query->whereNull('user_id');
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * عدد الإشعارات غير المقروءة
     */
    public static function getUnreadCount($userId = null)
    {
        $query = self::where('is_read', false);
        
        if ($userId) {
            $query->where(function($q) use ($userId) {
                $q->where('user_id', $userId)->orWhereNull('user_id');
            });
        } else {
            $query->whereNull('user_id');
        }
        
        return $query->count();
    }
}
