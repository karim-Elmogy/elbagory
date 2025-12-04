<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'description',
        'link',
        'image',
        'background_color',
        'text_color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getTextColorAttribute($value)
    {
        // إذا لم يتم تحديد text_color، نحدده تلقائياً بناءً على background_color
        if (empty($value)) {
            $bg = $this->background_color ?? '#404553';
            // إذا كان اللون أصفر (#ffd500 أو مشابه)، استخدم dark text
            if (strpos($bg, '#ffd500') !== false || strpos($bg, '#ffd') !== false) {
                return 'dark';
            }
            return 'light';
        }
        return $value;
    }
}
