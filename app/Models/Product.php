<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;
                
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $product->slug = $slug;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $counter = 1;
                
                while (static::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $product->slug = $slug;
            }
        });
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
    protected $fillable = [
        'name',
        'slug',
        'code',
        'barcode',
        'description',
        'category_id',
        'unit',
        'retail_price',
        'wholesale_price',
        'min_wholesale_quantity',
        'stock_quantity',
        'reorder_level',
        'is_active',
        'is_featured',
        'main_image',
        'images',
    ];

    protected $casts = [
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getPriceForCustomer($customerType = 'retail'): float
    {
        return $customerType === 'wholesale' ? $this->wholesale_price : $this->retail_price;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function hasStock($quantity): bool
    {
        return $this->stock_quantity >= $quantity;
    }
    
    public function getSlugAttribute($value)
    {
        if (empty($value) && !empty($this->attributes['name'])) {
            $baseSlug = Str::slug($this->attributes['name']);
            $slug = $baseSlug;
            $counter = 1;
            
            while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $this->attributes['slug'] = $slug;
            $this->save();
            
            return $slug;
        }
        
        return $value;
    }
}
