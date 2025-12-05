<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingRequest extends Model
{
    protected $fillable = [
        'customer_id',
        'request_number',
        'status',
        'notes',
        'admin_notes',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pricingRequest) {
            if (empty($pricingRequest->request_number)) {
                $pricingRequest->request_number = self::generateRequestNumber();
            }
        });
    }

    /**
     * إنشاء رقم طلب فريد
     */
    public static function generateRequestNumber(): string
    {
        $prefix = 'PRQ-';
        $date = now()->format('Ymd');
        $lastRequest = self::where('request_number', 'like', $prefix . $date . '%')
            ->orderBy('request_number', 'desc')
            ->first();

        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest->request_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PricingRequestItem::class);
    }

    /**
     * التحقق من إمكانية التعديل
     */
    public function canBeEdited(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * التحقق من إمكانية تحديد الأسعار
     */
    public function canBePriced(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * الحصول على إجمالي الأسعار
     */
    public function getTotalPrice(): float
    {
        return $this->items()->whereNotNull('price')->sum('price');
    }
}

