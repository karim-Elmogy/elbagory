<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class AdminSliderController extends Controller
{
    /**
     * عرض قائمة الـ sliders
     */
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * عرض نموذج إنشاء slider جديد
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * حفظ slider جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_color' => 'required|string|max:7',
            'text_color' => 'nullable|in:light,dark',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'العنوان مطلوب',
            'link.url' => 'الرابط يجب أن يكون رابط صحيح',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2MB',
            'background_color.required' => 'لون الخلفية مطلوب',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم إنشاء الـ slider بنجاح');
    }

    /**
     * عرض تفاصيل slider
     */
    public function show($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * عرض نموذج تعديل slider
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * تحديث slider
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_color' => 'required|string|max:7',
            'text_color' => 'nullable|in:light,dark',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'العنوان مطلوب',
            'link.url' => 'الرابط يجب أن يكون رابط صحيح',
            'image.image' => 'يجب أن يكون الملف صورة',
            'image.mimes' => 'نوع الصورة يجب أن يكون: jpeg, png, jpg, gif',
            'image.max' => 'حجم الصورة يجب أن يكون أقل من 2MB',
            'background_color.required' => 'لون الخلفية مطلوب',
        ]);

        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إن وجدت
            if ($slider->image && file_exists(storage_path('app/public/' . $slider->image))) {
                unlink(storage_path('app/public/' . $slider->image));
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $slider->sort_order;

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم تحديث الـ slider بنجاح');
    }

    /**
     * حذف slider
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        // حذف الصورة إن وجدت
        if ($slider->image && file_exists(storage_path('app/public/' . $slider->image))) {
            unlink(storage_path('app/public/' . $slider->image));
        }
        
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'تم حذف الـ slider بنجاح');
    }
}
