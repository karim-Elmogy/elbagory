@extends('layouts.admin')

@section('title', 'تعديل تصنيف')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-edit"></i> تعديل تصنيف: {{ $category->name }}</h1>
    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- المعلومات الأساسية -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">المعلومات الأساسية</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">اسم التصنيف <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الرابط (Slug)</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}" 
                               placeholder="سيتم إنشاؤه تلقائياً من الاسم">
                        <small class="text-muted">مثال: electronics-computers</small>
                        @error('slug')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
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
                                <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                        <input type="number" name="sort_order" class="form-control" 
                               value="{{ old('sort_order', $category->sort_order) }}" min="0">
                        <small class="text-muted">الأرقام الأصغر تظهر أولاً</small>
                        @error('sort_order')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
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
                    
                    @if($category->image)
                        <div class="mb-3">
                            <label class="form-label">الصورة الحالية</label>
                            <div>
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                     style="max-width: 100%; max-height: 200px; border-radius: 5px;">
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label">تغيير الصورة</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                        @error('image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="imagePreview" style="display: none;">
                        <label class="form-label">معاينة الصورة الجديدة</label>
                        <img id="previewImg" src="" alt="Preview" 
                             style="max-width: 100%; max-height: 200px; border-radius: 5px; margin-top: 10px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-secondary btn-lg">
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

