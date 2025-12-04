<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * الحصول على قيمة إعداد
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * حفظ قيمة إعداد
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * تحويل القيمة حسب النوع
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'number':
                return (float) $value;
            default:
                return $value;
        }
    }

    /**
     * التحقق من تفعيل الضريبة
     */
    public static function isTaxEnabled(): bool
    {
        return self::get('tax_enabled', false);
    }

    /**
     * الحصول على نسبة الضريبة
     */
    public static function getTaxRate(): float
    {
        return self::get('tax_rate', 0);
    }
}
