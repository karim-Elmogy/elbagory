<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRequestItem extends Model
{
    protected $fillable = [
        'pricing_request_id',
        'product_name',
        'quantity',
        'unit',
        'notes',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    public function pricingRequest(): BelongsTo
    {
        return $this->belongsTo(PricingRequest::class);
    }

    /**
     * الحصول على السعر الإجمالي للعنصر
     */
    public function getTotalPrice(): float
    {
        if ($this->price === null) {
            return 0;
        }
        return $this->price * $this->quantity;
    }
}

