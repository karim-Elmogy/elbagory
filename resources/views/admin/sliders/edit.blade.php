@extends('layouts.admin')

@section('title', 'تعديل Slider')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-edit"></i> تعديل Slider</h1>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('admin.sliders.update', $slider->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- المعلومات الأساسية -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">المعلومات الأساسية</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">العنوان <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}" required>
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $slider->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الرابط</label>
                        <input type="url" name="link" class="form-control" value="{{ old('link', $slider->link) }}" 
                               placeholder="https://example.com">
                        <small class="text-muted">رابط الصفحة التي سيتم الانتقال إليها عند النقر على الـ slider</small>
                        @error('link')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- الإعدادات -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">الإعدادات</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">لون الخلفية <span class="text-danger">*</span></label>
                        <input type="color" name="background_color" class="form-control form-control-color" 
                               value="{{ old('background_color', $slider->background_color) }}" required>
                        <input type="text" name="background_color_text" class="form-control mt-2" 
                               value="{{ old('background_color', $slider->background_color) }}" 
                               placeholder="#404553" pattern="^#[0-9A-Fa-f]{6}$">
                        <small class="text-muted">يمكنك اختيار اللون أو كتابة الكود مباشرة</small>
                        @error('background_color')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">لون النص</label>
                        <select name="text_color" class="form-select">
                            <option value="">تلقائي (حسب لون الخلفية)</option>
                            <option value="light" {{ old('text_color', $slider->text_color) == 'light' ? 'selected' : '' }}>فاتح (أبيض)</option>
                            <option value="dark" {{ old('text_color', $slider->text_color) == 'dark' ? 'selected' : '' }}>داكن (أسود)</option>
                        </select>
                        @error('text_color')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ترتيب العرض</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $slider->sort_order) }}" min="0">
                        <small class="text-muted">الأرقام الأصغر تظهر أولاً</small>
                        @error('sort_order')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                نشط
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- الصورة -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">الصورة</h5>
                    
                    @if($slider->image)
                        <div class="mb-3">
                            <label class="form-label">الصورة الحالية</label>
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}" 
                                 style="max-width: 100%; max-height: 200px; border-radius: 5px; display: block;">
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">{{ $slider->image ? 'استبدال الصورة' : 'صورة الـ Slider' }}</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">الصيغ المدعومة: JPG, PNG, GIF (حد أقصى 2MB)</small>
                        @error('image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview" 
                             style="max-width: 100%; max-height: 200px; border-radius: 5px; margin-top: 10px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> حفظ التغييرات
        </button>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>
</form>

@push('scripts')
<script>
    // مزامنة لون الخلفية بين color picker و text input
    document.querySelector('input[name="background_color"]').addEventListener('input', function(e) {
        document.querySelector('input[name="background_color_text"]').value = e.target.value;
    });
    
    document.querySelector('input[name="background_color_text"]').addEventListener('input', function(e) {
        if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
            document.querySelector('input[name="background_color"]').value = e.target.value;
        }
    });
    
    // معاينة الصورة قبل الرفع
    document.querySelector('input[name="image"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    });
</script>
@endpush

@endsection

