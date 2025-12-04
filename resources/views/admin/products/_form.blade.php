<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">اسم المنتج</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">الكود</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $product->code ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">الباركود</label>
        <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">التصنيف</label>
        <select name="category_id" class="form-select" required>
            <option value="">اختر التصنيف</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">وحدة القياس</label>
        <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">حد إعادة الطلب</label>
        <input type="number" name="reorder_level" class="form-control" min="0" value="{{ old('reorder_level', $product->reorder_level ?? 0) }}" required>
    </div>

    <div class="col-md-12">
        <label class="form-label">وصف المنتج</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">سعر القطاعي</label>
        <input type="number" name="retail_price" step="0.01" min="0" class="form-control" value="{{ old('retail_price', $product->retail_price ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">سعر الجملة</label>
        <input type="number" name="wholesale_price" step="0.01" min="0" class="form-control" value="{{ old('wholesale_price', $product->wholesale_price ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">حد كمية الجملة</label>
        <input type="number" name="min_wholesale_quantity" min="1" class="form-control" value="{{ old('min_wholesale_quantity', $product->min_wholesale_quantity ?? 1) }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label">الكمية بالمخزون</label>
        <input type="number" name="stock_quantity" min="0" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">الصورة الرئيسية</label>
        <input type="file" name="main_image" class="form-control">
        @isset($product->main_image)
            <small class="text-muted d-block mt-2">الصورة الحالية:</small>
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="Product" class="img-thumbnail mt-1" style="max-height: 120px;" onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
        @else
            <small class="text-muted d-block mt-2">الصورة الافتراضية:</small>
            <img src="{{ asset('images/no-product.svg') }}" alt="No Image" class="img-thumbnail mt-1" style="max-height: 120px;">
        @endisset
    </div>
    <div class="col-md-4">
        <label class="form-label d-block">حالة المنتج</label>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label">متاح للبيع</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">منتج مميز</label>
        </div>
    </div>
</div>

