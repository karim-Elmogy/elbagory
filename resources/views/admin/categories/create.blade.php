@extends('layouts.admin')

@section('title', 'إضافة تصنيف جديد')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-plus"></i> إضافة تصنيف جديد</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <!-- المعلومات الأساسية -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">المعلومات الأساسية</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الرابط (Slug)</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" 
                               placeholder="سيتم إنشاؤه تلقائياً من الاسم">
                        <small class="text-muted">مثال: electronics-computers</small>
                        @error('slug')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        @error('description')
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
                        <label class="form-label">التصنيف الأب</label>
                        <select name="parent_id" class="form-select">
                            <option value="">لا يوجد (تصنيف رئيسي)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ترتيب العرض</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        <small class="text-muted">الأرقام الأصغر تظهر أولاً</small>
                        @error('sort_order')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
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
                    
                    <div class="mb-3">
                        <label class="form-label">صورة التصنيف</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">الصيغ المدعومة: JPG, PNG, GIF</small>
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
            <i class="fas fa-save"></i> حفظ التصنيف
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-times"></i> إلغاء
        </a>
    </div>
</form>

@push('scripts')
<script>
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

