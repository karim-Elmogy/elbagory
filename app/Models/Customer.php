<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'type',
        'company_name',
        'tax_number',
        'address',
        'detailed_address',
        'preferred_payment_method',
        'credit_limit',
        'status',
        'customer_code',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isWholesale(): bool
    {
        return $this->type === 'wholesale';
    }

    public function isRetail(): bool
    {
        return $this->type === 'retail';
    }
}
