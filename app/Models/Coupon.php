<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
        'customer_type',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'usage_limit_per_user' => 'integer',
        'starts_at' => 'date',
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * التحقق من صلاحية الخصم
     */
    public function isValid($customerType = 'retail', $orderAmount = 0, $userId = null)
    {
        // التحقق من حالة الخصم
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'كود الخصم غير مفعل'];
        }

        // التحقق من تاريخ الصلاحية
        if ($this->starts_at && Carbon::now()->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'كود الخصم لم يبدأ بعد'];
        }

        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'كود الخصم منتهي الصلاحية'];
        }

        // التحقق من نوع العميل
        if ($this->customer_type !== 'all' && $this->customer_type !== $customerType) {
            return ['valid' => false, 'message' => 'كود الخصم غير متاح لنوع حسابك'];
        }

        // التحقق من الحد الأدنى للطلب
        if ($this->minimum_amount && $orderAmount < $this->minimum_amount) {
            return ['valid' => false, 'message' => 'الحد الأدنى للطلب: ' . number_format($this->minimum_amount, 2) . ' ج.م'];
        }

        // التحقق من حد الاستخدام
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'تم الوصول إلى حد الاستخدام المسموح'];
        }

        // التحقق من حد الاستخدام لكل مستخدم
        if ($userId && $this->usage_limit_per_user) {
            // الحصول على customer_id من user_id
            $customer = \App\Models\Customer::where('user_id', $userId)->first();
            if ($customer) {
                $userUsageCount = \App\Models\Order::where('customer_id', $customer->id)
                    ->where('coupon_id', $this->id)
                    ->count();
                
                if ($userUsageCount >= $this->usage_limit_per_user) {
                    return ['valid' => false, 'message' => 'لقد استخدمت هذا الكود بالفعل'];
                }
            }
        }

        return ['valid' => true, 'message' => 'كود الخصم صالح'];
    }

    /**
     * حساب قيمة الخصم
     */
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'percentage') {
            $discount = ($orderAmount * $this->value) / 100;
            
            // تطبيق الحد الأقصى للخصم إذا كان موجود
            if ($this->maximum_discount && $discount > $this->maximum_discount) {
                $discount = $this->maximum_discount;
            }
            
            return min($discount, $orderAmount); // لا يمكن أن يكون الخصم أكبر من قيمة الطلب
        } else {
            // خصم ثابت
            return min($this->value, $orderAmount);
        }
    }

    /**
     * زيادة عدد مرات الاستخدام
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Scope للخصومات النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', Carbon::now());
            })
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', Carbon::now());
            });
    }

    /**
     * Scope للبحث بالكود
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }
}
