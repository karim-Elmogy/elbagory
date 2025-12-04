<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // تحويل القيم حسب النوع
                if ($setting->type === 'boolean') {
                    // للـ checkbox، نتحقق من القيمة المرسلة
                    // إذا كانت القيمة '1' أو '0' (من hidden input) نستخدمها مباشرة
                    // لكن إذا كانت القيمة '1' من checkbox، نستخدمها
                    $value = ($value == '1' || $value === '1') ? '1' : '0';
                }
                
                $setting->update(['value' => $value]);
            } else {
                // إنشاء إعداد جديد إذا لم يكن موجوداً
                Setting::set($key, $value, 'text', 'general');
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
